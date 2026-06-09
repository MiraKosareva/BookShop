<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4 p-lg-5">
                <div class="text-center mb-4">
                    <i class="fas fa-user-plus text-muted" style="font-size: 3rem;"></i>
                    <h2 class="mt-3"><?= Html::encode($this->title) ?></h2>
                    <p class="text-muted">Создайте аккаунт для покупок</p>
                </div>

                <?php $form = ActiveForm::begin([
                    'id' => 'register-form',
                ]); ?>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-id-card me-2 text-muted"></i>ФИО
                        </label>
                        <?= $form->field($model, 'fio')->textInput([
                            'placeholder' => 'Иванов Иван Иванович',
                            'class' => 'form-control rounded-3'
                        ])->label(false) ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-envelope me-2 text-muted"></i>Email
                        </label>
                        <?= $form->field($model, 'email')->textInput([
                            'type' => 'email',
                            'placeholder' => 'example@mail.ru',
                            'class' => 'form-control rounded-3'
                        ])->label(false) ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-phone me-2 text-muted"></i>Телефон
                        </label>
                        <?= $form->field($model, 'phone')->textInput([
                            'placeholder' => '+7 (999) 123-45-67',
                            'class' => 'form-control rounded-3'
                        ])->label(false) ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-user me-2 text-muted"></i>Логин
                        </label>
                        <?= $form->field($model, 'username')->textInput([
                            'placeholder' => 'Придумайте логин',
                            'class' => 'form-control rounded-3'
                        ])->label(false) ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-lock me-2 text-muted"></i>Пароль
                        </label>
                        <?= $form->field($model, 'password')->passwordInput([
                            'placeholder' => 'Минимум 6 символов',
                            'class' => 'form-control rounded-3'
                        ])->label(false) ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-check-circle me-2 text-muted"></i>Подтверждение пароля
                        </label>
                        <?= $form->field($model, 'passwordValidate')->passwordInput([
                            'placeholder' => 'Повторите пароль',
                            'class' => 'form-control rounded-3'
                        ])->label(false) ?>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <?= $form->field($model, 'check')->checkbox([
                        'class' => 'form-check-input',
                        'template' => '<div class="form-check">{input} <label class="form-check-label">{label}</label></div>',
                    ]) ?>
                </div>

                <?= Html::submitButton('<i class="fas fa-user-plus me-2"></i>Зарегистрироваться', [
                    'class' => 'btn btn-success w-100 rounded-3 py-2 fw-medium',
                ]) ?>

                <?php ActiveForm::end(); ?>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        Уже есть аккаунт? 
                        <?= Html::a('Войти', ['/site/login'], ['class' => 'fw-medium text-decoration-none']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>