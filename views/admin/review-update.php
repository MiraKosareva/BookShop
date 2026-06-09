<?php

use app\models\Review;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Review $model */

$this->title = 'Редактировать отзыв #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Модерация отзывов', 'url' => ['reviews']];
$this->params['breadcrumbs'][] = ['label' => 'Отзыв #' . $model->id, 'url' => ['review-view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

<div class="review-update">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
                    </div>
                    
                    <div class="card-body">
                        <?php $form = ActiveForm::begin(); ?>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <?= $form->field($model, 'text')->textarea(['rows' => 8]) ?>
                            </div>
                            
                            <div class="col-md-4">
                                <?= $form->field($model, 'rating')->dropDownList([
                                    1 => '1 звезда',
                                    2 => '2 звезды', 
                                    3 => '3 звезды',
                                    4 => '4 звезды',
                                    5 => '5 звезд'
                                ]) ?>
                                
                                <?= $form->field($model, 'status')->dropDownList([
                                    Review::STATUS_PENDING => 'На модерации',
                                    Review::STATUS_APPROVED => 'Одобрен',
                                    Review::STATUS_REJECTED => 'Отклонен'
                                ]) ?>
                                
                                <div class="form-group">
                                    <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить', [
                                        'class' => 'btn btn-success btn-lg'
                                    ]) ?>
                                    <?= Html::a('Отмена', ['review-view', 'id' => $model->id], [
                                        'class' => 'btn btn-secondary btn-lg'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>