<?php
// views/book/_book_item.php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wishlist;

/** @var app\models\Book $model */
?>

<div class="card book-card h-100 shadow-sm position-relative">
    <a href="<?= Url::to(['view', 'id' => $model->id]) ?>" class="stretched-link card-link"></a>

    <!-- Скидка слева сверху -->
    <?php if ($model->getDiscountPercent() > 0): ?>
        <div class="position-absolute top-0 start-0 mt-2 ms-2 z-2"
            style="background: #ff4444; color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">
            -<?= $model->getDiscountPercent() ?>%
        </div>
    <?php endif; ?>

    <!-- Рекомендуем справа сверху -->
    <?php if ($model->is_featured): ?>
        <div class="position-absolute top-0 end-0 mt-2 me-2 z-2"
            style="background: #ffd700; color: #333; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">
            ★ Рекомендуем
        </div>
    <?php endif; ?>

    <!-- Изображение -->
    <div class="card-img-container" style="height: 250px; overflow: hidden; background: linear-gradient(135deg, #f9f9f9 0%, #f0f0f0 100%);">
        <?= Html::img($model->getImageUrl(), [
            'class' => 'card-img-top w-100 h-100',
            'style' => 'object-fit: contain; object-position: center; padding: 16px;',
            'alt' => $model->name,
            'loading' => 'lazy'
        ]) ?>
        <?php if (!$model->isInStock()): ?>
            <div class="position-absolute top-50 start-50 translate-middle z-1">
                <span class="badge bg-danger px-3 py-2 fs-6">Нет в наличии</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-body d-flex flex-column">
        <h5 class="card-title text-dark"><?= Html::encode($model->name) ?></h5>
        <p class="card-text text-muted mb-2 small"><?= Html::encode($model->author) ?></p>

        <?php if (!empty($model->category)): ?>
            <span class="badge bg-light text-dark mb-2 align-self-start border"><?= Html::encode($model->category) ?></span>
        <?php endif; ?>

        <div class="price-section mb-3">
            <?php if ($model->getDiscountPercent() > 0): ?>
                <div class="d-flex align-items-center">
                    <span class="h5 text-success mb-0 me-2"><?= number_format($model->price, 0, '', ' ') ?> ₽</span>
                    <span class="text-muted text-decoration-line-through small"><?= number_format($model->old_price, 0, '', ' ') ?> ₽</span>
                </div>
            <?php else: ?>
                <span class="h5 text-success mb-0"><?= number_format($model->price, 0, '', ' ') ?> ₽</span>
            <?php endif; ?>
        </div>

        <div class="stock-status mb-3">
            <?php $stockStatus = $model->getStockStatus(); ?>
            <span class="badge <?= $stockStatus['class'] ?>"><?= $stockStatus['text'] ?></span>
            <?php if ($model->stock > 0): ?>
                <small class="text-muted">(осталось: <?= $model->stock ?> шт.)</small>
            <?php endif; ?>
        </div>

        <!-- Кнопки: Корзина + Избранное -->
        <div class="mt-auto d-flex flex-column gap-2">
            <?php if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()): ?>
                <?php if ($model->isInStock()): ?>
                    <div class="d-flex gap-2" style="position: relative; z-index: 3;">
                        <?= Html::a('🛒 В корзину', ['/cart/add', 'id' => $model->id], [
                            'class' => 'btn btn-success flex-grow-1 add-to-cart-btn',
                            'data-book-id' => $model->id,
                        ]) ?>

                        <?php $isInWishlist = \app\models\Wishlist::isInWishlist(Yii::$app->user->id, $model->id); ?>
                        <button type="button"
                            class="btn wishlist-toggle-btn <?= $isInWishlist ? 'btn-danger' : 'btn-outline-danger' ?>"
                            data-book-id="<?= $model->id ?>"
                            title="<?= $isInWishlist ? 'Удалить из избранного' : 'Добавить в избранное' ?>"
                            style="width: 42px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                            <i class="<?= $isInWishlist ? 'fas fa-heart' : 'far fa-heart' ?>"></i>
                        </button>
                    </div>
                <?php else: ?>
                    <button class="btn btn-outline-secondary w-100" disabled>Нет в наличии</button>
                <?php endif; ?>
            <?php elseif (Yii::$app->user->isGuest): ?>
                <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-outline-primary w-100" style="position: relative; z-index: 3;">
                    🛒 В корзину
                </a>
            <?php endif; ?>

            <?php if (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin()): ?>
                <div class="btn-group w-100" style="position: relative; z-index: 3;">
                    <?= Html::a('✏️', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-secondary btn-sm w-50']) ?>
                    <?= Html::a('🗑️', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-outline-danger btn-sm w-50',
                        'data' => ['confirm' => 'Удалить книгу?', 'method' => 'post'],
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>