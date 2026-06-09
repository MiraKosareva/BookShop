<?php

use yii\helpers\Html;
use yii\helpers\Json;

/** @var yii\web\View $this */
/** @var array $statusStats */
/** @var array $ratingStats */
/** @var array $topBooks */

$this->title = 'Статистика отзывов';
$this->params['breadcrumbs'][] = ['label' => 'Модерация отзывов', 'url' => ['reviews']];
$this->params['breadcrumbs'][] = $this->title;

// Подготовка данных для графика
$statusLabels = ['На модерации', 'Одобрено', 'Отклонено'];
$statusData = [0, 0, 0];
foreach ($statusStats as $stat) {
    $index = $stat['status'];
    $statusData[$index] = (int)$stat['count'];
}

$ratingLabels = ['5 звезд', '4 звезды', '3 звезды', '2 звезды', '1 звезда'];
$ratingData = [0, 0, 0, 0, 0];
foreach ($ratingStats as $stat) {
    $index = 5 - $stat['rating']; // Инвертируем для правильного порядка
    $ratingData[$index] = (int)$stat['count'];
}

// Конвертируем массивы в JSON для JavaScript
$statusLabelsJson = Json::encode($statusLabels);
$statusDataJson = Json::encode($statusData);
$ratingLabelsJson = Json::encode($ratingLabels);
$ratingDataJson = Json::encode($ratingData);
?>

<div class="review-stats">
    <div class="container-fluid">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Распределение по статусам</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Распределение по рейтингам (одобренные)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="ratingChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Топ-10 книг по количеству отзывов</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($topBooks)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Книга</th>
                                            <th>Автор</th>
                                            <th>Кол-во отзывов</th>
                                            <th>Средний рейтинг</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1; ?>
                                        <?php foreach ($topBooks as $book): ?>
                                            <?php
                                            $bookModel = $book['book'];

                                            // поддержка обеих структур: массива и объекта
                                            $bookName = is_array($bookModel) ? $bookModel['name'] : $bookModel->name;
                                            $bookAuthor = is_array($bookModel) ? $bookModel['author'] : $bookModel->author;
                                            ?>
                                            
                                            <?php if ($bookModel): ?>
                                                <tr>
                                                    <td><?= $counter++ ?></td>
                                                    <td><?= Html::encode($bookName) ?></td>
                                                    <td><?= Html::encode($bookAuthor) ?></td>
                                                    <td><span class="badge bg-primary"><?= $book['review_count'] ?></span></td>
                                                    <td>
                                                        <span class="text-warning">
                                                            <?= str_repeat('★', round($book['avg_rating'])) ?>
                                                            <?= str_repeat('☆', 5 - round($book['avg_rating'])) ?>
                                                        </span>
                                                        <span class="badge bg-info ms-2"><?= number_format($book['avg_rating'], 1) ?></span>
                                                    </td>
                                                    <td>
                                                        <?= Html::a('<i class="fas fa-eye"></i>', ['/book/view', 'id' => $book['book_id']], [
                                                            'class' => 'btn btn-sm btn-info',
                                                            'target' => '_blank',
                                                            'title' => 'Просмотреть книгу'
                                                        ]) ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>

                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Нет данных для отображения</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <?= Html::a('<i class="fas fa-arrow-left"></i> Вернуться к списку отзывов', ['reviews'], [
                            'class' => 'btn btn-primary btn-lg'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Подключаем Chart.js
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js');

// JavaScript для графиков
$this->registerJs(<<<JS
// График статусов
var statusCtx = document.getElementById('statusChart').getContext('2d');
var statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: $statusLabelsJson,
        datasets: [{
            data: $statusDataJson,
            backgroundColor: [
                'rgba(255, 193, 7, 0.8)', // На модерации
                'rgba(40, 167, 69, 0.8)',  // Одобрено
                'rgba(220, 53, 69, 0.8)'   // Отклонено
            ],
            borderColor: [
                'rgb(255, 193, 7)',
                'rgb(40, 167, 69)',
                'rgb(220, 53, 69)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// График рейтингов
var ratingCtx = document.getElementById('ratingChart').getContext('2d');
var ratingChart = new Chart(ratingCtx, {
    type: 'bar',
    data: {
        labels: $ratingLabelsJson,
        datasets: [{
            label: 'Количество отзывов',
            data: $ratingDataJson,
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
JS);
?>