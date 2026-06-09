<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Review $model */
/** @var yii\web\View $this */

$statusColors = [
    \app\models\Review::STATUS_PENDING => 'warning',
    \app\models\Review::STATUS_APPROVED => 'success',
    \app\models\Review::STATUS_REJECTED => 'danger',
];
?>

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <?= Html::img($model->book->getImageUrl(), [
                    'class' => 'img-fluid rounded',
                    'alt' => $model->book->name,
                    'style' => 'max-height: 120px;'
                ]) ?>
            </div>
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="card-title mb-1">
                            <?= Html::a(Html::encode($model->book->name), 
                                ['/book/view', 'id' => $model->book_id], 
                                ['class' => 'text-decoration-none']
                            ) ?>
                        </h5>
                        <p class="text-muted small mb-2"><?= Html::encode($model->book->author) ?></p>
                    </div>
                    <div>
                        <span class="badge bg-<?= $statusColors[$model->status] ?? 'secondary' ?>">
                            <?= $model->getStatusText() ?>
                        </span>
                    </div>
                </div>
                
                <div class="rating mb-2 text-warning">
                    <?= $model->getRatingStars() ?>
                </div>
                
                <p class="card-text"><?= nl2br(Html::encode($model->text)) ?></p>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
                    </small>
                    <div>
                        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-outline-danger',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить этот отзыв?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>