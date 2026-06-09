<?php
// views/book/search.php

use yii\helpers\Html;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string $searchQuery */
/** @var int $totalCount */

$this->title = 'Результаты поиска: ' . Html::encode($searchQuery);
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-search">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="text-muted">
            Найдено: <?= $totalCount ?> книг
        </div>
    </div>
    
    <?php if ($totalCount > 0): ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_book_item',
            'layout' => "<div class='row'>{items}</div>\n<div class='mt-4'>{pager}</div>",
            'itemOptions' => ['class' => 'col-xl-3 col-lg-4 col-md-6 mb-4'],
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center'],
                'linkOptions' => ['class' => 'page-link'],
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
            ],
        ]); ?>
    <?php else: ?>
        <div class="alert alert-info">
            <h4>📖 Книги не найдены</h4>
            <p>По запросу "<?= Html::encode($searchQuery) ?>" ничего не найдено.</p>
            <p>Попробуйте:</p>
            <ul>
                <li>Использовать другие ключевые слова</li>
                <li>Проверить правильность написания</li>
                <li>Искать по автору или названию</li>
            </ul>
            <div class="mt-3">
                <?= Html::a('← Вернуться в каталог', ['index'], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Показать все категории', ['categories'], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    <?php endif; ?>
</div>