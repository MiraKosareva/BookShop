<?php
// views/book/category.php

use yii\helpers\Html;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string $categoryName */
/** @var int $totalCount */

$this->title = 'Категория: ' . Html::encode($categoryName);
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['categories']];
$this->params['breadcrumbs'][] = Html::encode($categoryName);
?>

<div class="book-category">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="text-muted">
            Книг: <?= $totalCount ?>
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
        <div class="alert alert-warning">
            <p>В этой категории пока нет книг.</p>
            <?= Html::a('← Вернуться к категориям', ['categories'], ['class' => 'btn btn-primary']) ?>
        </div>
    <?php endif; ?>
</div>