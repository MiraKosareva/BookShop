<?php

use app\models\Catalog;
use app\models\Delivery;
use app\models\Orderbook;
use app\models\Pay;
use app\models\Status;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'id_user',
                'value' => function($model)
                {
                    $status = User::findOne($model->id_user)->fio;
                    return $status;
                },
            ],
            'fio',
            'contact',
            // 
            // 'email:email',
            // 'phone',
            // 'username',
            // 'password',
           'adres',
        //    [
        //         'attribute' => 'id_catalog',
        //         'value' => function($model)
        //         {
        //             $status = Catalog::findOne($model->id_catalog)->name;
        //             return $status;
        //         },
        //     ],
            [
                'attribute' => 'id_delivery',
                'value' => function($model)
                {
                    $status = Delivery::findOne($model->id_delivery)->name;
                    return $status;
                },
            ],
            [
                'attribute' => 'id_pay',
                'value' => function($model)
                {
                    $status = Pay::findOne($model->id_pay)->name;
                    return $status;
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
             [
                'label' => 'Товары',
                'format' => 'raw',
                'value' => function ($model) {
                    if (empty($model->items)) {
                        return '<i>Нет товаров</i>';
                    }

                    $html = '<ul style="padding-left:15px;">';
                    foreach ($model->items as $item) {
                        $name = $item->book->name ?? 'Неизвестный товар';
                        $html .= "<li>{$name} — {$item->quantity} шт × {$item->price} ₽</li>";
                    }
                    $html .= '</ul>';

                    return $html;
                }
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Orderbook $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
