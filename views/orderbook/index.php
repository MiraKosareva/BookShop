<?php

use app\models\Delivery;
use app\models\Orderbook;
use app\models\Pay;
use app\models\Status;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orderbook-index">

    <h1><?= Html::encode($this->title) ?></h1>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'id_user',
            'fio',
            'contact',
            'adres',
            // 'id_catalog',
            [
                'attribute' => 'id_delivery',
                'value' => function($model)
                {
                    $delivery = Delivery::findOne($model->id_delivery)->name;
                    return $delivery;
                },
            ],
            [
                'attribute' => 'id_pay',
                'value' => function($model)
                {
                    $pay = Pay::findOne($model->id_pay)->name;
                    return $pay;
                },
            ],
             [
                'attribute' => 'id_status',
                'value' => function($model)
                {
                    $status = Status::findOne($model->id_status)->name;
                    return $status;
                },
            ],
            //  'total_amount',
            //  'created_at',
            // [
            //     'class' => ActionColumn::className(),
            //     'urlCreator' => function ($action, Orderbook $model, $key, $index, $column) {
            //         return Url::toRoute([$action, 'id' => $model->id]);
            //      }
            // ],
        ],
    ]); ?>


</div>
