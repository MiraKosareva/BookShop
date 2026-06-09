<?php

namespace app\controllers;

use app\models\Book;
use app\models\Review;
use app\models\ReviewSearch;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AdminController extends Controller
{

    public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'only' => ['index', 'reviews', 'review-view', 'review-update', 'review-approve', 
                      'review-reject', 'review-delete', 'mass-approve', 'review-stats'],
            'rules' => [
                [
                    'actions' => ['index', 'reviews', 'review-view', 'review-update', 'review-approve', 
                                 'review-reject', 'review-delete', 'mass-approve', 'review-stats'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function ($rule, $actions) {
                        return Yii::$app->user->identity->isAdmin();
                    }
                ],
            ],
        ],
        'verbs' => [
            'class' => VerbFilter::class,
            'actions' => [
                'logout' => ['post'],
                'review-approve' => ['post'],
                'review-reject' => ['post'],
                'review-delete' => ['post'],
                'mass-approve' => ['post'],
            ],
        ],
    ];
}

    public function actionIndex()
    {
        // Статистика для дашборда
        $pendingCount = Review::find()->where(['status' => Review::STATUS_PENDING])->count();
        $approvedCount = Review::find()->where(['status' => Review::STATUS_APPROVED])->count();
        $rejectedCount = Review::find()->where(['status' => Review::STATUS_REJECTED])->count();
        $totalBooks = Book::find()->count();
        $totalUsers = User::find()->count();
        
        // Последние 5 отзывов на модерации
        $recentReviews = Review::find()
            ->where(['status' => Review::STATUS_PENDING])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();
        
        // Последние добавленные книги
        $recentBooks = Book::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();
        
        return $this->render('index', [
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'totalBooks' => $totalBooks,
            'totalUsers' => $totalUsers,
            'recentReviews' => $recentReviews,
            'recentBooks' => $recentBooks,
        ]);
    }
    

    /**
     * Список отзывов для модерации
     */
    public function actionReviews()
    {
        $searchModel = new ReviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('reviews', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр отзыва
     */
    public function actionReviewView($id)
    {
        $model = $this->findReviewModel($id);

        return $this->render('review-view', [
            'model' => $model,
        ]);
    }

    /**
     * Редактирование отзыва
     */
    public function actionReviewUpdate($id)
    {
        $model = $this->findReviewModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Отзыв успешно обновлен.');
            return $this->redirect(['review-view', 'id' => $model->id]);
        }

        return $this->render('review-update', [
            'model' => $model,
        ]);
    }

    /**
     * Одобрить отзыв
     */
    public function actionReviewApprove($id)
    {
        $model = $this->findReviewModel($id);
        $model->status = Review::STATUS_APPROVED;
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Отзыв одобрен и опубликован.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при одобрении отзыва.');
        }
        
        return $this->redirect(['reviews']);
    }

    /**
     * Отклонить отзыв
     */
    public function actionReviewReject($id)
    {
        $model = $this->findReviewModel($id);
        $model->status = Review::STATUS_REJECTED;
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Отзыв отклонен.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при отклонении отзыва.');
        }
        
        return $this->redirect(['reviews']);
    }

    /**
     * Удалить отзыв
     */
    public function actionReviewDelete($id)
    {
        $model = $this->findReviewModel($id);
        $model->delete();

        Yii::$app->session->setFlash('success', 'Отзыв успешно удален.');
        return $this->redirect(['reviews']);
    }

    /**
     * Массовое одобрение всех отзывов на модерации
     */
    public function actionMassApprove()
    {
        $updated = Review::updateAll(
            ['status' => Review::STATUS_APPROVED],
            ['status' => Review::STATUS_PENDING]
        );
        
        Yii::$app->session->setFlash('success', "Одобрено {$updated} отзывов.");
        return $this->redirect(['reviews']);
    }

    /**
     * Статистика по отзывам
     */
    public function actionReviewStats()
    {
        // Статистика по статусам
        $statusStats = Review::find()
            ->select(['status', 'COUNT(*) as count'])
            ->groupBy('status')
            ->asArray()
            ->all();
        
        // Статистика по рейтингам
        $ratingStats = Review::find()
            ->select(['rating', 'COUNT(*) as count'])
            ->where(['status' => Review::STATUS_APPROVED])
            ->groupBy('rating')
            ->orderBy(['rating' => SORT_DESC])
            ->asArray()
            ->all();
        
        // Топ книг по отзывам
        $topBooks = Review::find()
            ->select(['book_id', 'COUNT(*) as review_count', 'AVG(rating) as avg_rating'])
            ->where(['status' => Review::STATUS_APPROVED])
            ->groupBy('book_id')
            ->orderBy(['review_count' => SORT_DESC])
            ->limit(10)
            ->with('book')
            ->asArray()
            ->all();

        return $this->render('review-stats', [
            'statusStats' => $statusStats,
            'ratingStats' => $ratingStats,
            'topBooks' => $topBooks,
        ]);
    }

    public function actionMessages()
{
    $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => \app\models\ContactMessage::find()->orderBy(['created_at' => SORT_DESC]),
        'pagination' => ['pageSize' => 20],
    ]);

    return $this->render('messages', ['dataProvider' => $dataProvider]);
}

public function actionMessageView($id)
{
    $model = \app\models\ContactMessage::findOne($id);
    
    if ($model->status == \app\models\ContactMessage::STATUS_NEW) {
        $model->status = \app\models\ContactMessage::STATUS_READ;
        $model->save(false);
    }
    
    return $this->render('message-view', ['model' => $model]);
}

    /**
     * Находит модель отзыва по ID
     */
    protected function findReviewModel($id)
    {
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('Отзыв не найден.');
    }
}

