<?php

namespace app\controllers;

use Yii;
use app\models\Review;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class AdminReviewController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Review::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 20],
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionApprove($id)
    {
        $review = $this->findModel($id);
        $review->status = Review::STATUS_APPROVED;
        
        if ($review->save()) {
            Yii::$app->session->setFlash('success', 'Отзыв одобрен.');
        }
        
        return $this->redirect(['index']);
    }
    
    public function actionReject($id)
    {
        $review = $this->findModel($id);
        $review->status = Review::STATUS_REJECTED;
        
        if ($review->save()) {
            Yii::$app->session->setFlash('success', 'Отзыв отклонен.');
        }
        
        return $this->redirect(['index']);
    }
    
    protected function findModel($id)
    {
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('Отзыв не найден.');
    }
}