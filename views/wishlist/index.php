<?php
// views/wishlist/index.php

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Избранное';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wishlist-index">
    <h1 class="mb-4">❤️ <?= Html::encode($this->title) ?></h1>

    <?php if ($dataProvider->totalCount > 0): ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_wishlist_item',
            'layout' => "<div class='row'>{items}</div>\n<div class='col-12 mt-3'>{pager}</div>",
            'itemOptions' => ['class' => 'col-lg-4 col-md-6 mb-4'],
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center'],
                'linkOptions' => ['class' => 'page-link'],
            ],
            'emptyText' => '<div class="col-12 text-center py-5"><h4>💔 У вас пока нет избранных книг</h4></div>',
        ]); ?>
    <?php else: ?>
        <div class="text-center py-5">
            <div style="font-size: 4rem;">💔</div>
            <h4>У вас пока нет избранных книг</h4>
            <p class="text-muted">Добавляйте книги в избранное, чтобы не потерять!</p>
            <?= Html::a('📚 Перейти в каталог', ['/book/index'], ['class' => 'btn btn-primary btn-lg']) ?>
        </div>
    <?php endif; ?>
</div>