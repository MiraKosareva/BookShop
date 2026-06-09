<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\Orderbook $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="orderbook-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'id_user')->textInput() ?> -->

    <?= $form->field($model, 'fio')->textInput() ?>

    <?= $form->field($model, 'contact')->widget(MaskedInput::class, [
        'mask' => '+7 (999) 999-99-99',
        'options' => ['maxlength' => true,
        'placeholder' => '+7 (___) ___-__-__',
        ]
        ]) ?>

    <?= $form->field($model, 'adres')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_catalog')->textInput() ?>

    <?= $form->field($model, 'id_delivery')->dropDownList(ArrayHelper::map($delivery, 'id', 'name')) ?>

     <?= $form->field($model, 'id_pay')->dropDownList(ArrayHelper::map($pay, 'id', 'name')) ?>

    <!-- <?= $form->field($model, 'id_status')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Заказать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php

    $js = <<<JS
// Автоматически заполняем скрытые поля
$(document).ready(function() {
    // Получаем сумму корзины через AJAX или передаем из PHP
    var totalAmount = $cartTotal; // Это нужно передать из PHP
    var cartItems = $cartItems; // Это нужно передать из PHP
    
    $('#orderbook-total_amount').val(totalAmount);
    $('#orderbook-cart_items').val(JSON.stringify(cartItems));
});
JS;

// Передайте данные в JavaScript
$cart = new \app\models\Cart();
$this->registerJs($js);
?>

</div>
