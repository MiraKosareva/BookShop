<?php

use yii\widgets\ListView;
use yii\helpers\Html;

/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Отзывы читателей';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="review-index">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_review_item',
        'summary' => false,
        'layout' => "{items}\n<div class='text-center mt-4'>{pager}</div>",
        'emptyText' => '<p class="text-muted text-center">Пока отзывов нет. Будьте первым!</p>',
    ]) ?>

</div>
