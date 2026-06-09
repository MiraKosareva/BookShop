<?php

namespace app\controllers;

use Yii;
use app\models\Review;
use app\models\Book;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ReviewController implements the CRUD actions for Review model.
 */
class ReviewController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete', 'my'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['view', 'index'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
{
    $query = Review::find()->with('book', 'user')->orderBy(['created_at' => SORT_DESC]);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 10,
        ],
    ]);

    return $this->render('index', [
        'dataProvider' => $dataProvider,
    ]);
}


    /**
     * Создание нового отзыва
     */
    public function actionCreate($book_id = null)
    {
    $model = new Review();
    $model->scenario = 'create';
    $book = null;
    
    if ($book_id) {
        $book = Book::findOne($book_id);
        if (!$book) {
            throw new NotFoundHttpException('Книга не найдена.');
        }
    
        if ($book->hasUserReview()) {
            Yii::$app->session->setFlash('warning', 'Вы уже оставляли отзыв на эту книгу.');
            return $this->redirect(['book/view', 'id' => $book_id]);
        }

        $model->book_id = $book_id;
    }
    
    if ($model->load(Yii::$app->request->post())) {
        if (empty($model->book_id) && $book) {
            $model->book_id = $book->id;
        }
        
        if (empty($model->book_id)) {
            Yii::$app->session->setFlash('error', 'Не указана книга для отзыва.');
            return $this->refresh();
        }
        
        $model->user_id = Yii::$app->user->id;
        $model->created_at = time();
        $model->updated_at = time();
        $model->status = Review::STATUS_PENDING;
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Спасибо за ваш отзыв! Он появится после проверки модератором.');
            return $this->redirect(['book/view', 'id' => $model->book_id]);
        } else {
            Yii::error('Ошибка сохранения отзыва: ' . print_r($model->errors, true));
            Yii::$app->session->setFlash('error', 'Пожалуйста, исправьте ошибки в форме.');
        }
    } else {
        $post = Yii::$app->request->post();
        if ($post && !$model->load($post)) {
            Yii::error('Не удалось загрузить данные формы: ' . print_r($post, true));
        }
    }
    
    return $this->render('create', [
        'model' => $model,
        'book' => $book,
    ]);
    }

    /**
     * Редактирование отзыва
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if (!$model->canEdit()) {
            throw new \yii\web\ForbiddenHttpException('У вас нет прав для редактирования этого отзыва.');
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Отзыв успешно обновлен.');
            return $this->redirect(['book/view', 'id' => $model->book_id]);
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление отзыва
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if (!$model->canEdit()) {
            throw new \yii\web\ForbiddenHttpException('У вас нет прав для удаления этого отзыва.');
        }
        
        $book_id = $model->book_id;
        $model->delete();
        
        Yii::$app->session->setFlash('success', 'Отзыв успешно удален.');
        return $this->redirect(['book/view', 'id' => $book_id]);
    }

    /**
     * Мои отзывы
     */
    public function actionMy()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Review::find()->where(['user_id' => Yii::$app->user->id]),
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        return $this->render('my', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Находит модель отзыва по ID
     */
    protected function findModel($id)
    {
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('Отзыв не найден.');
    }
}