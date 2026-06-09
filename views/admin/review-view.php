<?php

use app\models\Review;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Review $model */

$this->title = 'Отзыв #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Модерация отзывов', 'url' => ['reviews']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="review-view">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
                        <div class="card-tools">
                            <?= Html::a('<i class="fas fa-arrow-left"></i> Назад', ['reviews'], [
                                'class' => 'btn btn-secondary'
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h3 class="card-title mb-0">Содержание отзыва</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>Рейтинг:</strong><br>
                                            <span class="text-warning fs-4">
                                                <?= str_repeat('★', $model->rating) . str_repeat('☆', 5 - $model->rating) ?>
                                            </span>
                                            <span class="badge bg-info"><?= $model->rating ?>/5</span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <strong>Текст отзыва:</strong>
                                            <div class="p-3 bg-light rounded mt-2">
                                                <?= nl2br(Html::encode($model->text)) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h3 class="card-title mb-0">Информация</h3>
                                    </div>
                                    <div class="card-body">
                                        <?= DetailView::widget([
                                            'model' => $model,
                                            'attributes' => [
                                                [
                                                    'attribute' => 'status',
                                                    'value' => function($model) {
                                                        $badgeClass = [
                                                            0 => 'badge-warning',
                                                            1 => 'badge-success',
                                                            2 => 'badge-danger',
                                                        ][$model->status] ?? 'badge-secondary';
                                                        return '<span class="badge ' . $badgeClass . '">' . $model->getStatusText() . '</span>';
                                                    },
                                                    'format' => 'raw',
                                                ],
                                                [
                                                    'label' => 'Пользователь',
                                                    'value' => function($model) {
                                                        return $model->user ? $model->user->username : 'Гость';
                                                    },
                                                ],
                                                [
                                                    'label' => 'Книга',
                                                    'value' => function($model) {
                                                        if (!$model->book) return 'Неизвестно';
                                                        return Html::a($model->book->name, 
                                                            ['/book/view', 'id' => $model->book_id],
                                                            ['target' => '_blank']);
                                                    },
                                                    'format' => 'raw',
                                                ],
                                                [
                                                    'label' => 'Автор книги',
                                                    'value' => function($model) {
                                                        return $model->book ? $model->book->author : 'Неизвестно';
                                                    },
                                                ],
                                                [
                                                    'attribute' => 'created_at',
                                                    'value' => function($model) {
                                                        return Yii::$app->formatter->asDatetime($model->created_at);
                                                    },
                                                ],
                                                [
                                                    'attribute' => 'updated_at',
                                                    'value' => function($model) {
                                                        return Yii::$app->formatter->asDatetime($model->updated_at);
                                                    },
                                                ],
                                            ],
                                        ]) ?>
                                    </div>
                                </div>
                                
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h3 class="card-title mb-0">Действия</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="btn-group-vertical w-100">
                                            <?php if ($model->status != Review::STATUS_APPROVED): ?>
                                                <?= Html::a('<i class="fas fa-check"></i> Одобрить', 
                                                    ['review-approve', 'id' => $model->id], [
                                                        'class' => 'btn btn-success mb-2',
                                                        'data' => ['method' => 'post']
                                                    ]
                                                ) ?>
                                            <?php endif; ?>
                                            
                                            <?php if ($model->status != Review::STATUS_REJECTED): ?>
                                                <?= Html::a('<i class="fas fa-times"></i> Отклонить', 
                                                    ['review-reject', 'id' => $model->id], [
                                                        'class' => 'btn btn-danger mb-2',
                                                        'data' => ['method' => 'post']
                                                    ]
                                                ) ?>
                                            <?php endif; ?>
                                            
                                            <?= Html::a('<i class="fas fa-edit"></i> Редактировать', 
                                                ['review-update', 'id' => $model->id], [
                                                    'class' => 'btn btn-primary mb-2'
                                                ]
                                            ) ?>
                                            
                                            <?= Html::a('<i class="fas fa-trash"></i> Удалить', 
                                                ['review-delete', 'id' => $model->id], [
                                                    'class' => 'btn btn-danger mb-2',
                                                    'data' => [
                                                        'confirm' => 'Удалить этот отзыв?',
                                                        'method' => 'post',
                                                    ]
                                                ]
                                            ) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>