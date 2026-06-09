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


    <?= $form->field($model, 'id_status')->dropDownList(ArrayHelper::map($status, 'id', 'name')) ?>

    

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
