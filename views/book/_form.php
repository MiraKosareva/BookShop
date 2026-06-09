<?php
// views/book/_form.php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><?= $model->isNewRecord ? 'Добавить книгу' : 'Редактировать книгу' ?></h5>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'publisher')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'old_price')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'stock')->textInput(['type' => 'number', 'min' => 0]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'publication_year')->textInput(['type' => 'number', 'min' => 1900, 'max' => date('Y')]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'file')->fileInput([
                        'accept' => 'image/*'
                    ])->label('Основное изображение') ?>

                    <?php if (!$model->isNewRecord && $model->image): ?>
                        <div class="mt-2">
                            <p class="text-muted small mb-1">Текущее:</p>
                            <img src="<?= $model->image ?>" alt="" style="max-height: 100px; border-radius: 8px;">
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($model->isNewRecord): ?>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label">📸 Дополнительные фото (можно несколько)</label>
                            <?= Html::fileInput('extra_images[]', null, [
                                'multiple' => true,
                                'accept' => 'image/*',
                                'class' => 'form-control',
                                'id' => 'extra-images-input'
                            ]) ?>
                            <div id="extra-images-names" class="mt-2"></div>
                        </div>
                    </div>

                    <script>
                        document.getElementById('extra-images-input').addEventListener('change', function() {
                            var names = '';
                            for (var i = 0; i < this.files.length; i++) {
                                names += '📎 ' + this.files[i].name + '<br>';
                            }
                            if (this.files.length > 0) {
                                names += '<small class="text-success">✅ Выбрано: ' + this.files.length + ' файлов</small>';
                            }
                            document.getElementById('extra-images-names').innerHTML = names;
                        });
                    </script>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'is_featured')->checkbox() ?>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>