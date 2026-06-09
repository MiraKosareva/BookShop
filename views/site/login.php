<?php

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4 p-lg-5">
                <div class="text-center mb-4">
                    <i class="fas fa-book-open text-muted" style="font-size: 3rem;"></i>
                    <h2 class="mt-3"><?= Html::encode($this->title) ?></h2>
                    <p class="text-muted">Добро пожаловать в книжный магазин</p>
                </div>

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['class' => 'needs-validation'],
                ]); ?>

                <div class="mb-3">
                    <label class="form-label fw-medium">
                        <i class="fas fa-user me-2 text-muted"></i>Логин
                    </label>
                    <?= $form->field($model, 'username', [
                        'options' => ['class' => 'form-floating']
                    ])->textInput([
                        'autofocus' => true,
                        'placeholder' => 'Введите логин',
                        'class' => 'form-control rounded-3'
                    ])->label(false) ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">
                        <i class="fas fa-lock me-2 text-muted"></i>Пароль
                    </label>
                    <?= $form->field($model, 'password', [
                        'options' => ['class' => 'form-floating']
                    ])->passwordInput([
                        'placeholder' => 'Введите пароль',
                        'class' => 'form-control rounded-3'
                    ])->label(false) ?>
                </div>

                <div class="mb-4 form-check">
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'class' => 'form-check-input',
                        'template' => '<div class="form-check">{input} <label class="form-check-label">{label}</label></div>',
                    ]) ?>
                </div>

                <?= Html::submitButton('<i class="fas fa-sign-in-alt me-2"></i>Войти', [
                    'class' => 'btn btn-primary w-100 rounded-3 py-2 fw-medium',
                    'name' => 'login-button'
                ]) ?>

                <?php ActiveForm::end(); ?>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        Нет аккаунта? 
                        <?= Html::a('Зарегистрироваться', ['/user/create'], ['class' => 'fw-medium text-decoration-none']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>