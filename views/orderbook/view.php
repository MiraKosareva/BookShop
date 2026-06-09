<?php

use app\models\Delivery;
use app\models\Pay;
use app\models\Status;
use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Orderbook $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orderbooks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orderbook-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить этот объект?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            
        ],
    ]) ?>
    <h3>Товары в заказе</h3>

<?php if (!empty($model->items)): ?>
    <ul>
        <?php foreach ($model->items as $item): ?>
            <li>
                <?= Html::encode($item->book->name ?? 'Неизвестный товар') ?>
                — <?= $item->quantity ?> шт
                × <?= number_format((float)$item->price, 0, '', ' ') ?> ₽
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><i>Нет товаров</i></p>
<?php endif; ?>


</div>
