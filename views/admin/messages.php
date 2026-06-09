<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Сообщения от пользователей';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-messages">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'email:email',
            'subject',
            [
                'attribute' => 'body',
                'value' => function($model) {
                    return mb_strimwidth($model->body, 0, 50, '...');
                },
            ],
            [
                'attribute' => 'status',
                'value' => function($model) {
                    $colors = [0 => 'warning', 1 => 'info', 2 => 'success'];
                    return '<span class="badge bg-' . $colors[$model->status] . '">' . $model->getStatusText() . '</span>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return Yii::$app->formatter->asDatetime($model->created_at);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a('👁️ Просмотр', ['message-view', 'id' => $model->id], ['class' => 'btn btn-sm btn-info']);
                    },
                ],
            ],
        ],
    ]); ?>
</div>