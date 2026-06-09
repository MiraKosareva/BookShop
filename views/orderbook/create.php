<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Orderbook $model */

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = ['label' => 'Корзина', 'url' => ['cart/index']];
$this->params['breadcrumbs'][] = $this->title;

// JavaScript для заполнения скрытых полей
$js = <<<JS
$(document).ready(function() {
    // Заполняем скрытые поля данными корзины
    $('#orderbook-total_amount').val($totalAmount);
    $('#orderbook-cart_items').val(JSON.stringify($cartItems));
});
JS;

$this->registerJs($js, \yii\web\View::POS_READY);
?>
<div class="orderbook-create">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <!-- Отображение суммы заказа -->
    <div class="alert alert-info">
        <h4>Сумма заказа: <?= Yii::$app->formatter->asCurrency($totalAmount) ?></h4>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'pay' => $pay,
        'delivery' => $delivery,
    ]) ?>

</div>