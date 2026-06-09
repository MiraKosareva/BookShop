<?php
// controllers/ProfileController.php

namespace app\controllers;

use app\models\Book;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\User;
use Symfony\Component\BrowserKit\Response;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $orders = $user->getOrders()->all();
        
        return $this->render('index', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    public function actionEdit()
{
    $model = Yii::$app->user->identity;

    if ($model->load(Yii::$app->request->post())) {
        // Получаем загруженный файл
        $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');
        
        if ($model->validate()) {
            // Если загружен новый аватар
            if ($model->avatarFile) {
                $fileName = 'avatar_' . Yii::$app->user->id . '_' . time() . '.' . $model->avatarFile->extension;
                $uploadPath = Yii::getAlias('@webroot/uploads/avatars/');
                
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $filePath = $uploadPath . $fileName;
                
                if ($model->avatarFile->saveAs($filePath)) {
                    // Удаляем старый аватар если есть
                    if ($model->avatar && file_exists(Yii::getAlias('@webroot') . '/' . ltrim($model->avatar, '/'))) {
                        @unlink(Yii::getAlias('@webroot') . '/' . ltrim($model->avatar, '/'));
                    }
                    $model->avatar = '/uploads/avatars/' . $fileName;
                }
            }
            
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Профиль успешно обновлён!');
                return $this->redirect(['index']);
            }
        }
    }

    return $this->render('edit', [
        'model' => $model,
    ]);
}

    public function actionOrders()
    {
        $user = Yii::$app->user->identity;
        $orders = $user->getOrders()->all();
        
        return $this->render('orders', [
            'orders' => $orders,
        ]);
    }

    public function actionOrderView($id)
    {
        $order = \app\models\Orderbook::find()
            ->where(['id' => $id, 'id_user' => Yii::$app->user->id])
            ->one();

        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден');
        }

        return $this->render('order-view', [
            'order' => $order,
        ]);
    }
}