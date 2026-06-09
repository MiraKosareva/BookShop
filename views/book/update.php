<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = 'Редактировать: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="book-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>
    
    <!-- Дополнительные фото -->
    <?php if (!$model->isNewRecord): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">📸 Дополнительные фото</h5>
        </div>
        <div class="card-body">
            <!-- Существующие фото -->
            <?php $images = $model->images; ?>
            <?php if (!empty($images)): ?>
                <div class="row mb-3">
                    <?php foreach ($images as $img): ?>
                        <div class="col-auto text-center mb-2">
                            <img src="<?= $img->image_path ?>" style="height: 100px; width: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                            <br>
                            <?= Html::a('🗑️', ['delete-image', 'id' => $img->id], [
                                'class' => 'btn btn-sm btn-danger mt-1',
                                'data' => [
                                    'confirm' => 'Удалить фото?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Загрузка новых фото -->
            <?= Html::beginForm(['upload-images', 'id' => $model->id], 'post', [
                'enctype' => 'multipart/form-data'
            ]) ?>
            
            <div class="mb-3">
                <label class="form-label">Выберите фото (можно несколько)</label>
                <?= Html::fileInput('images[]', null, [
                    'multiple' => true,
                    'accept' => 'image/*',
                    'class' => 'form-control'
                ]) ?>
            </div>
            
            <?= Html::submitButton('📤 Загрузить фото', ['class' => 'btn btn-primary']) ?>
            
            <?= Html::endForm() ?>
        </div>
    </div>
    <?php endif; ?>
</div>
