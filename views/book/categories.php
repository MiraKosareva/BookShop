<?php
// views/book/categories.php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $categories */

$this->title = 'Категории книг';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-categories">
    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>
    
    <?php if (!empty($categories)): ?>
        <div class="row">
            <?php foreach ($categories as $category): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= Html::encode($category['name']) ?></h5>
                            <p class="card-text text-muted"><?= $category['count'] ?> книг</p>
                            <div class="mt-auto">
                                <?= Html::a('Просмотреть', ['category', 'name' => $category['name']], [
                                    'class' => 'btn btn-outline-primary w-100'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <p>Категории пока не добавлены.</p>
            <?php if (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin()): ?>
                <?= Html::a('Добавить книгу с категорией', ['create'], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>