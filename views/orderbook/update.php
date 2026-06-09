<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Orderbook $model */

$this->title = 'Редактировать заказ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['admin']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="orderbook-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('formUpdate', [
        'model' => $model,
        'status' => $status,
    ]) ?>

</div>
