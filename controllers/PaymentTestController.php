<?php
// controllers/PaymentTestController.php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Url;

class PaymentTestController extends Controller
{
    public $layout = 'main';
    
    public function actionIndex()
{
    $amount = Yii::$app->request->get('amount', 0);
    $orderId = Yii::$app->request->get('order_id', '');
    
    if (!$orderId && Yii::$app->session->has('last_order_id')) {
        $orderId = Yii::$app->session->get('last_order_id');
        $amount = Yii::$app->session->get('last_order_amount', 0);
    }
    
    // Получаем выбранные способы
    $payId = Yii::$app->session->get('last_order_pay');
    $deliveryId = Yii::$app->session->get('last_order_delivery');
    
    // Получаем названия
    $payModel = \app\models\Pay::findOne($payId);
    $deliveryModel = \app\models\Delivery::findOne($deliveryId);
    
    $payName = $payModel ? $payModel->name : 'Не выбрано';
    $deliveryName = $deliveryModel ? $deliveryModel->name : 'Не выбрано';
    
    // ПРОВЕРКА ДЛЯ ОТЛАДКИ
    Yii::debug("Pay ID: $payId, Pay Name: $payName", 'payment');
    
    if ($amount <= 0 || !$orderId) {
        Yii::$app->session->setFlash('error', 'Данные заказа не найдены');
        return $this->redirect(['cart/index']);
    }
    
    // Если оплата при получении — пропускаем имитацию
    // Проверяем по названию, а не по ID
    if (stripos($payName, 'получени') !== false || $payId == 3) {
        Yii::$app->session->remove('cart');
        Yii::$app->session->remove('last_order_id');
        Yii::$app->session->remove('last_order_amount');
        Yii::$app->session->remove('last_order_pay');
        Yii::$app->session->remove('last_order_delivery');
        
        return $this->render('success-cod', [
            'orderId' => $orderId,
            'amount' => $amount,
            'deliveryName' => $deliveryName,
        ]);
    }
    
    return $this->render('index', [
        'amount' => $amount,
        'orderId' => $orderId,
        'payName' => $payName,
        'deliveryName' => $deliveryName,
    ]);
}
    
    public function actionProcess()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Получаем данные из сессии
        $orderId = Yii::$app->session->get('last_order_id');
        $amount = Yii::$app->session->get('last_order_amount', 0);
        
        if (!$orderId || $amount <= 0) {
            return [
                'success' => false,
                'message' => 'Данные заказа не найдены',
            ];
        }
        
        // Имитация обработки платежа
        sleep(2);
        
        // ЗДЕСЬ МОЖНО ОБНОВИТЬ СТАТУС ЗАКАЗА В БАЗЕ ДАННЫХ
        // Например: Orderbook::updateStatus($orderId, 'paid');
        
        return [
            'success' => true,
            'orderId' => $orderId,
            'message' => 'Оплата прошла успешно',
            'redirect' => Url::to(['payment-test/success', 'id' => $orderId]),
        ];
    }
    
    public function actionSuccess($id)
    {
        // Получаем сумму из сессии
        $amount = Yii::$app->session->get('last_order_amount', 0);
        
        // Очищаем корзину и сессионные данные
        Yii::$app->session->remove('cart');
        Yii::$app->session->remove('last_order_id');
        Yii::$app->session->remove('last_order_amount');
        
        return $this->render('success', [
            'orderId' => $id,
            'amount' => $amount,
        ]);
    }
}