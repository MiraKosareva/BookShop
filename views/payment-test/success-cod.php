<?php
use yii\helpers\Html;

$this->title = 'Заказ оформлен';
?>

<div class="container text-center py-5">
    <div style="width: 100px; height: 100px; background: #28a745; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 50px; margin: 0 auto 30px;">
        ✓
    </div>
    
    <h1 class="mb-3">✅ Заказ успешно оформлен!</h1>
    <p class="lead text-muted mb-4">Заказ #<?= $orderId ?> принят в обработку</p>
    
    <div class="card mx-auto mb-4" style="max-width: 500px;">
        <div class="card-body text-start">
            <h5 class="card-title border-bottom pb-3 mb-3">Детали заказа</h5>
            <p><strong>Номер заказа:</strong> #<?= $orderId ?></p>
            <p><strong>Сумма к оплате:</strong> <span class="text-success fw-bold"><?= number_format($amount, 0, '', ' ') ?> ₽</span></p>
            <p><strong>Способ оплаты:</strong> 💵 Наличными при получении</p>
            <p><strong>Доставка:</strong> 🚚 <?= $deliveryName ?></p>
            <hr>
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle"></i> Оплата производится при получении заказа. 
                С вами свяжется менеджер для подтверждения.
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-center gap-3">
        <?= Html::a('Вернуться в магазин', ['/book/index'], ['class' => 'btn btn-primary btn-lg']) ?>
        <?= Html::a('Мои заказы', ['/profile/orders'], ['class' => 'btn btn-outline-secondary btn-lg']) ?>
    </div>
</div>