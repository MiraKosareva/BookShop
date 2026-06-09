<?php

/** @var yii\web\View $this */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Связаться с поддержкой';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4 p-lg-5">
                <div class="text-center mb-4">
                    <i class="fas fa-headset text-muted" style="font-size: 3rem;"></i>
                    <h2 class="mt-3"><?= Html::encode($this->title) ?></h2>
                    <p class="text-muted">
                        Если у вас есть вопросы, заполните форму. Мы ответим на указанную почту в ближайшее время.
                    </p>
                </div>

                <?php $form = ActiveForm::begin([
                    'id' => 'contact-form',
                ]); ?>

                <div class="mb-3">
                    <label class="form-label fw-medium">
                        <i class="fas fa-user me-2 text-muted"></i>Ваше имя
                    </label>
                    <?= $form->field($model, 'name')->textInput([
                        'placeholder' => 'Иванов Иван',
                        'class' => 'form-control rounded-3'
                    ])->label(false) ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">
                        <i class="fas fa-envelope me-2 text-muted"></i>Email
                    </label>
                    <?= $form->field($model, 'email')->textInput([
                        'type' => 'email',
                        'placeholder' => 'example@mail.ru',
                        'class' => 'form-control rounded-3'
                    ])->label(false) ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">
                        <i class="fas fa-question-circle me-2 text-muted"></i>Тема вопроса
                    </label>
                    <?= $form->field($model, 'subject')->textInput([
                        'placeholder' => 'Укажите тему',
                        'class' => 'form-control rounded-3'
                    ])->label(false) ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">
                        <i class="fas fa-comment me-2 text-muted"></i>Описание
                    </label>
                    <?= $form->field($model, 'body')->textarea([
                        'rows' => 5,
                        'placeholder' => 'Опишите проблему или вопрос...',
                        'class' => 'form-control rounded-3'
                    ])->label(false) ?>
                </div>

                <div class="mb-4">
                    <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::class, [
                        'options' => ['class' => 'form-control rounded-3', 'placeholder' => 'Введите код'],
                        'template' => '<div class="row"><div class="col-6">{image}</div><div class="col-6">{input}</div></div>',
                    ]) ?>
                </div>

                <?= Html::submitButton('<i class="fas fa-paper-plane me-2"></i>Отправить', [
                    'class' => 'btn btn-primary w-100 rounded-3 py-2 fw-medium',
                ]) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>