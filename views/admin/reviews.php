<?php

use app\models\Review;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\ReviewSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Модерация отзывов';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-reviews">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <div class="btn-group">
                        <?= Html::a('<i class="fas fa-check-double"></i> Одобрить все', ['mass-approve'], [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите одобрить ВСЕ отзывы на модерации?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::a('<i class="fas fa-chart-bar"></i> Статистика', ['review-stats'], [
                            'class' => 'btn btn-info'
                        ]) ?>
                        <?= Html::a('<i class="fas fa-home"></i> Дашборд', ['index'], [
                            'class' => 'btn btn-secondary'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Фильтры</h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-group mb-3">
                            <?= Html::a('Все', ['reviews', 'status' => ''], [
                                'class' => 'btn btn-sm btn-outline-secondary' . (empty($searchModel->status) ? ' active' : '')
                            ]) ?>
                            <?= Html::a('На модерации', ['reviews', 'status' => 0], [
                                'class' => 'btn btn-sm btn-warning' . ($searchModel->status === '0' ? ' active' : '')
                            ]) ?>
                            <?= Html::a('Одобренные', ['reviews', 'status' => 1], [
                                'class' => 'btn btn-sm btn-success' . ($searchModel->status == 1 ? ' active' : '')
                            ]) ?>
                            <?= Html::a('Отклоненные', ['reviews', 'status' => 2], [
                                'class' => 'btn btn-sm btn-danger' . ($searchModel->status == 2 ? ' active' : '')
                            ]) ?>
                        </div>
                        
                        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <?php Pjax::begin(['id' => 'review-grid-pjax']); ?>
                        
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'tableOptions' => ['class' => 'table table-hover'],
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    
                                    [
                                        'attribute' => 'id',
                                        'headerOptions' => ['style' => 'width: 80px'],
                                    ],
                                    
                                    [
                                        'attribute' => 'user.username',
                                        'label' => 'Пользователь',
                                        'value' => function($model) {
                                            return $model->user ? $model->user->username : 'Гость';
                                        },
                                        'format' => 'raw',
                                    ],
                                    
                                    [
                                        'attribute' => 'book.name',
                                        'label' => 'Книга',
                                        'value' => function($model) {
                                            return Html::a(
                                                $model->book ? $model->book->name : 'Неизвестно',
                                                ['/book/view', 'id' => $model->book_id],
                                                ['target' => '_blank']
                                            );
                                        },
                                        'format' => 'raw',
                                    ],
                                    
                                    [
                                        'attribute' => 'rating',
                                        'value' => function($model) {
                                            $stars = str_repeat('★', $model->rating) . str_repeat('☆', 5 - $model->rating);
                                            return '<span class="text-warning">' . $stars . '</span> (' . $model->rating . ')';
                                        },
                                        'format' => 'raw',
                                        'filter' => [1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'],
                                        'headerOptions' => ['style' => 'width: 150px'],
                                    ],
                                    
                                    [
                                        'attribute' => 'text',
                                        'value' => function($model) {
                                            return mb_strimwidth($model->text, 0, 100, '...');
                                        },
                                    ],
                                    
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
                                        'filter' => [
                                            0 => 'На модерации',
                                            1 => 'Одобрен',
                                            2 => 'Отклонен',
                                        ],
                                        'headerOptions' => ['style' => 'width: 150px'],
                                    ],
                                    
                                    [
                                        'attribute' => 'created_at',
                                        'value' => function($model) {
                                            return Yii::$app->formatter->asDatetime($model->created_at);
                                        },
                                        'headerOptions' => ['style' => 'width: 180px'],
                                    ],
                                    
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view} {approve} {reject} {update} {delete}',
                                        'buttons' => [
                                            'view' => function($url, $model) {
                                                return Html::a('<i class="fas fa-eye"></i>', 
                                                    ['review-view', 'id' => $model->id], [
                                                        'class' => 'btn btn-sm btn-info',
                                                        'title' => 'Просмотр',
                                                    ]);
                                            },
                                            'approve' => function($url, $model) {
                                                if ($model->status == Review::STATUS_APPROVED) return '';
                                                return Html::a('<i class="fas fa-check"></i>', 
                                                    ['review-approve', 'id' => $model->id], [
                                                        'class' => 'btn btn-sm btn-success',
                                                        'title' => 'Одобрить',
                                                        'data' => [
                                                            'confirm' => 'Одобрить этот отзыв?',
                                                            'method' => 'post',
                                                        ],
                                                    ]);
                                            },
                                            'reject' => function($url, $model) {
                                                if ($model->status == Review::STATUS_REJECTED) return '';
                                                return Html::a('<i class="fas fa-times"></i>', 
                                                    ['review-reject', 'id' => $model->id], [
                                                        'class' => 'btn btn-sm btn-danger',
                                                        'title' => 'Отклонить',
                                                        'data' => [
                                                            'confirm' => 'Отклонить этот отзыв?',
                                                            'method' => 'post',
                                                        ],
                                                    ]);
                                            },
                                            'update' => function($url, $model) {
                                                return Html::a('<i class="fas fa-edit"></i>', 
                                                    ['review-update', 'id' => $model->id], [
                                                        'class' => 'btn btn-sm btn-primary',
                                                        'title' => 'Редактировать',
                                                    ]);
                                            },
                                            'delete' => function($url, $model) {
                                                return Html::a('<i class="fas fa-trash"></i>', 
                                                    ['review-delete', 'id' => $model->id], [
                                                        'class' => 'btn btn-sm btn-danger',
                                                        'title' => 'Удалить',
                                                        'data' => [
                                                            'confirm' => 'Удалить этот отзыв?',
                                                            'method' => 'post',
                                                        ],
                                                    ]);
                                            },
                                        ],
                                        'headerOptions' => ['style' => 'width: 200px', 'class' => 'text-center'],
                                        'contentOptions' => ['class' => 'text-center'],
                                    ],
                                ],
                            ]); ?>
                        </div>
                        
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// JavaScript для быстрых действий
$this->registerJs(<<<JS
$(document).on('click', '.quick-action', function(e) {
    e.preventDefault();
    
    var button = $(this);
    var url = button.attr('href');
    var action = button.data('action');
    
    if (confirm('Вы уверены, что хотите выполнить это действие?')) {
        $.post(url, {_csrf: yii.getCsrfToken()}, function(response) {
            $.pjax.reload({container: '#review-grid-pjax'});
        });
    }
});
JS);
?>