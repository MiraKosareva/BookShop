<?php
// views/cart/checkout.php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Orderbook $model */
/** @var app\models\Delivery[] $deliveryMethods */
/** @var app\models\Pay[] $payMethods */
/** @var array $cartBooks */
/** @var float $total */

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = ['label' => 'Корзина', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cart-checkout">
    <h1>📦 <?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Данные для доставки</h5>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'adres')->textarea(['rows' => 3]) ?>

                    <?= $form->field($model, 'id_delivery')->dropDownList(
                        \yii\helpers\ArrayHelper::map($deliveryMethods, 'id', 'name'),
                        ['prompt' => 'Выберите способ доставки']
                    ) ?>

                    <?= $form->field($model, 'id_pay')->dropDownList(
                        \yii\helpers\ArrayHelper::map($payMethods, 'id', 'name'),
                        ['prompt' => 'Выберите способ оплаты']
                    ) ?>

                    <div class="form-group">
    <?= Html::submitButton('Подтвердить заказ и перейти к оплате', ['class' => 'btn btn-success btn-lg']) ?>
</div>

<!-- ДОБАВЬТЕ ПОСЛЕ КНОПКИ: -->
<div class="alert alert-warning mt-3">
    <h5>⚠️ Тестовый режим оплаты</h5>
    <p class="mb-0">После подтверждения заказа вы перейдете на страницу <strong>имитации оплаты</strong>.</p>
    <p class="mb-0 small">Это тестовый режим - реальные деньги не списываются.</p>
</div>

<?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ваш заказ</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($cartBooks as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small"><?= Html::encode($item['book']->name) ?> × <?= $item['quantity'] ?></span>
                            <span class="small"><?= number_format($item['book']->price * $item['quantity'], 0, '', ' ') ?> ₽</span>
                        </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Итого:</strong>
                        <strong class="h5 text-success"><?= number_format($total, 0, '', ' ') ?> ₽</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>