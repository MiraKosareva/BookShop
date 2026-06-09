<?php
// views/profile/edit.php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = ['label' => 'Мой профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-edit">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">✏️ Редактирование профиля</h5>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data'], // ← важно!
                    ]); ?>

                    <!-- Фото профиля -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <?php if ($model->getAvatarUrl()): ?>
                                <img src="<?= $model->getAvatarUrl() ?>" 
                                     id="avatar-preview"
                                     class="rounded-circle border shadow-sm"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            <?php else: ?>
                                <div id="avatar-preview" 
                                     class="rounded-circle bg-light d-flex align-items-center justify-content-center border shadow-sm"
                                     style="width: 120px; height: 120px;">
                                    <span class="display-5 text-muted">
                                        <?= mb_substr($model->fio ?? '?', 0, 1) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <label for="user-avatarfile" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2" 
                                   style="cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-camera" style="font-size: 14px;"></i>
                            </label>
                        </div>
                        <?= $form->field($model, 'avatarFile', [
                            'options' => ['class' => 'd-none']
                        ])->fileInput([
                            'id' => 'user-avatarfile',
                            'accept' => 'image/*',
                            'onchange' => 'previewAvatar(this)'
                        ])->label(false) ?>
                        <p class="text-muted small mt-2 mb-0">Нажмите на иконку камеры для загрузки фото</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'fio')->textInput([
                                'placeholder' => 'Иванов Иван Иванович',
                                'class' => 'form-control rounded-3'
                            ]) ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'email')->textInput([
                                'type' => 'email',
                                'placeholder' => 'example@mail.ru',
                                'class' => 'form-control rounded-3'
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'phone')->textInput([
                                'placeholder' => '+7 (999) 123-45-67',
                                'class' => 'form-control rounded-3'
                            ]) ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'username')->textInput([
                                'placeholder' => 'Логин для входа',
                                'class' => 'form-control rounded-3'
                            ])->label('Логин') ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <?= Html::a('<i class="fas fa-arrow-left me-1"></i>Назад', ['index'], ['class' => 'btn btn-secondary rounded-3']) ?>
                        <?= Html::submitButton('<i class="fas fa-save me-1"></i>Сохранить', ['class' => 'btn btn-primary rounded-3']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            
            <div class="alert alert-info mt-3 rounded-3">
                <div class="d-flex">
                    <i class="fas fa-info-circle me-2 mt-1"></i>
                    <div>
                        <strong>Внимание:</strong> После изменения данных необходимо будет повторно авторизоваться.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('avatar-preview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Заменяем div на img
                var img = document.createElement('img');
                img.id = 'avatar-preview';
                img.src = e.target.result;
                img.className = 'rounded-circle border shadow-sm';
                img.style.width = '120px';
                img.style.height = '120px';
                img.style.objectFit = 'cover';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>