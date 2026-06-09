<?php
// views/payment-test/success.php

use yii\helpers\Html;

$this->title = 'Оплата прошла успешно';
$this->params['breadcrumbs'][] = ['label' => 'Оформление заказа', 'url' => ['/cart/checkout']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container text-center py-5">
    <div style="width: 100px; height: 100px; background: #28a745; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 50px; margin: 0 auto 30px; box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);">
        ✓
    </div>
    
    <h1 class="mb-3">✅ Оплата прошла успешно!</h1>
    <p class="lead text-muted mb-4">Ваш заказ #<?= $orderId ?> оплачен и принят в обработку</p>
    
    <div class="card mx-auto mb-4" style="max-width: 500px;">
        <div class="card-body text-start">
            <h5 class="card-title border-bottom pb-3 mb-3">Детали заказа</h5>
            <div class="row mb-2">
                <div class="col-6"><strong>Номер заказа:</strong></div>
                <div class="col-6 text-end">#<?= $orderId ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-6"><strong>Сумма оплаты:</strong></div>
                <div class="col-6 text-end text-success"><?= number_format($amount, 0, '', ' ') ?> ₽</div>
            </div>
            <div class="row mb-2">
                <div class="col-6"><strong>Дата и время:</strong></div>
                <div class="col-6 text-end"><?= date('d.m.Y H:i:s') ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-6"><strong>Статус:</strong></div>
                <div class="col-6 text-end"><span class="badge bg-success">Оплачено</span></div>
            </div>
            <div class="row">
                <div class="col-6"><strong>Режим оплаты:</strong></div>
                <div class="col-6 text-end"><span class="badge bg-warning text-dark">Тестовая имитация</span></div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info mx-auto mb-4" style="max-width: 500px;">
        <h5>ℹ️ Важная информация</h5>
        <p class="mb-2">Это была <strong>имитация оплаты в тестовом режиме</strong>.</p>
        <p class="mb-0"><strong>Настоящие деньги не списывались!</strong></p>
        <p class="mb-0 small mt-2">Заказ сохранен в системе. На указанную почту отправлено письмо с деталями заказа (имитация).</p>
    </div>
    
    <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
        <?= Html::a('Вернуться в магазин', ['/book/index'], [
            'class' => 'btn btn-primary btn-lg px-4'
        ]) ?>
        <?= Html::a('Мои заказы', ['/profile/orders'], [
            'class' => 'btn btn-outline-secondary btn-lg px-4'
        ]) ?>
        <?= Html::a('Продолжить покупки', ['/book/index'], [
            'class' => 'btn btn-success btn-lg px-4'
        ]) ?>
    </div>
    
    <div class="mt-4">
        <small class="text-muted">Спасибо за использование нашего магазина!</small>
    </div>
</div>