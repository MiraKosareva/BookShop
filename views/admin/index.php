<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Панель администратора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artide-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="knopki">

    <p class="knopki-p">
        <?= Html::a('Заказы', ['/orderbook/admin'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="knopki-p">
        <?= Html::a('Каталог', ['/book/index'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="knopki-p">
        <?= Html::a('Модерация отзывов', ['/admin/reviews'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="knopki-p">
        <?= Html::a('Сообщения', ['/admin/messages'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="knopki-p">
        <?= Html::a('Статистика отзывов', ['/admin/review-stats'], ['class' => 'btn btn-success']) ?>
    </p>
    </div>
    
</div>
<div class="admin-index">
    <div class="container-fluid">
        
        
        <!-- Статистика -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="display-6"><?= $totalBooks ?></h2>
                                <p class="mb-0">Книг</p>
                            </div>
                            <i class="fas fa-book fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="display-6"><?= $totalUsers ?></h2>
                                <p class="mb-0">Пользователей</p>
                            </div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-warning text-white shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="display-6"><?= $pendingCount ?></h2>
                                <p class="mb-0">Отзывов на модерации</p>
                            </div>
                            <i class="fas fa-comments fa-3x opacity-50"></i>
                        </div>
                        <?php if ($pendingCount > 0): ?>
                            <?= Html::a('Модерировать', ['reviews', 'status' => 0], [
                                'class' => 'btn btn-light btn-sm mt-2 w-100'
                            ]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-info text-white shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2 class="display-6"><?= $approvedCount + $rejectedCount ?></h2>
                                <p class="mb-0">Всего отзывов</p>
                            </div>
                            <i class="fas fa-star fa-3x opacity-50"></i>
                        </div>
                        <?= Html::a('Все отзывы', ['reviews'], [
                            'class' => 'btn btn-light btn-sm mt-2 w-100'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Последние отзывы на модерации -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-warning text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-clock"></i> Последние отзывы на модерации
                            <?php if ($pendingCount > 0): ?>
                                <span class="badge bg-danger float-end"><?= $pendingCount ?></span>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentReviews)): ?>
                            <div class="list-group">
                                <?php foreach ($recentReviews as $review): ?>
                                    <a href="<?= Url::to(['review-view', 'id' => $review->id]) ?>" 
                                       class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">
                                                <?= Html::encode($review->user->username ?? 'Гость') ?>
                                            </h6>
                                            <small>
                                                <?= Yii::$app->formatter->asRelativeTime($review->created_at) ?>
                                            </small>
                                        </div>
                                        <p class="mb-1 small text-truncate">
                                            <?= Html::encode($review->text) ?>
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-warning">
                                                <?= str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating) ?>
                                            </small>
                                            <small>
                                                <?= Html::encode(mb_strimwidth($review->book->name ?? 'Неизвестно', 0, 30, '...')) ?>
                                            </small>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <?= Html::a('<i class="fas fa-list"></i> Все отзывы на модерации', 
                                    ['reviews', 'status' => 0], ['class' => 'btn btn-warning']) ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">Нет отзывов на модерации</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Последние добавленные книги -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-book"></i> Последние добавленные книги
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentBooks)): ?>
                            <div class="list-group">
                                <?php foreach ($recentBooks as $book): ?>
                                    <a href="<?= Url::to(['/book/view', 'id' => $book->id]) ?>" 
                                       class="list-group-item list-group-item-action" target="_blank">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= Html::encode($book->name) ?></h6>
                                            <small><?= number_format($book->price, 0, '', ' ') ?> ₽</small>
                                        </div>
                                        <p class="mb-1 text-muted small"><?= Html::encode($book->author) ?></p>
                                        <small>
                                            <?= Yii::$app->formatter->asRelativeTime($book->created_at) ?>
                                        </small>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <?= Html::a('<i class="fas fa-plus"></i> Добавить книгу', 
                                    ['/book/create'], ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Нет добавленных книг</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Быстрые действия -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Быстрые действия</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <?= Html::a('<i class="fas fa-comments"></i> Модерация отзывов', 
                                    ['reviews'], ['class' => 'btn btn-warning w-100 btn-lg']) ?>
                            </div>
                            <div class="col-md-3">
                                <?= Html::a('<i class="fas fa-chart-bar"></i> Статистика', 
                                    ['review-stats'], ['class' => 'btn btn-info w-100 btn-lg']) ?>
                            </div>
                            <div class="col-md-3">
                                <?= Html::a('<i class="fas fa-plus"></i> Добавить книгу', 
                                    ['/book/create'], ['class' => 'btn btn-success w-100 btn-lg']) ?>
                            </div>
                            <!-- <div class="col-md-3">
                                <?= Html::a('<i class="fas fa-users"></i> Управление пользователями', 
                                    ['#'], ['class' => 'btn btn-primary w-100 btn-lg']) ?>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>