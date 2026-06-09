<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ReviewSearch $model */
/** @var ActiveForm $form */
?>

<div class="review-search">
    <?php $form = ActiveForm::begin([
        'action' => ['reviews'],
        'method' => 'get',
        'options' => [
            'class' => 'row g-3'
        ],
    ]); ?>

    <div class="col-md-3">
        <?= $form->field($model, 'username', [
            'inputOptions' => ['placeholder' => 'Имя пользователя']
        ])->label(false) ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'bookName', [
            'inputOptions' => ['placeholder' => 'Название книги']
        ])->label(false) ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'rating')->dropDownList([
            '' => 'Все рейтинги',
            1 => '1 звезда',
            2 => '2 звезды',
            3 => '3 звезды',
            4 => '4 звезды',
            5 => '5 звезд'
        ], ['class' => 'form-select'])->label(false) ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'status')->dropDownList([
            '' => 'Все статусы',
            0 => 'На модерации',
            1 => 'Одобренные',
            2 => 'Отклоненные'
        ], ['class' => 'form-select'])->label(false) ?>
    </div>

    <div class="col-md-2">
        <div class="d-grid">
            <?= Html::submitButton('<i class="fas fa-search"></i> Поиск', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-times"></i> Сброс', ['reviews'], ['class' => 'btn btn-secondary mt-1']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>