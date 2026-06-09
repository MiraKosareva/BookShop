<?php
// views/cart/index.php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $books */
/** @var float $total */
/** @var int $totalItems */

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cart-index">
    <h1>🛒 <?= Html::encode($this->title) ?></h1>

    <?php if (empty($books)): ?>
        <div class="alert alert-info text-center">
            <h4>Корзина пуста</h4>
            <p>Добавьте товары из каталога</p>
            <?= Html::a('Перейти в каталог', ['/book/index'], ['class' => 'btn btn-primary']) ?>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <?php foreach ($books as $item): ?>
                    <?php $book = $item['book']; ?>
                    <?php $quantity = $item['quantity']; ?>
                    <?php $itemTotal = $item['item_total']; ?>
                    
                    <div class="card mb-3 shadow-sm cart-item" data-book-id="<?= $book->id ?>">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <?= Html::img($book->getImageUrl(), [
                                        'class' => 'img-fluid rounded',
                                        'alt' => $book->name,
                                        'style' => 'height: 80px; object-fit: cover;'
                                    ]) ?>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="card-title mb-1">
                                        <?= Html::a(Html::encode($book->name), ['/book/view', 'id' => $book->id]) ?>
                                    </h6>
                                    <p class="card-text text-muted small mb-1"><?= Html::encode($book->author) ?></p>
                                    <p class="card-text text-success mb-0"><?= number_format($book->price, 0, '', ' ') ?> ₽</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-sm">
                                        <!-- <button class="btn btn-outline-secondary cart-quantity-minus" 
                                                type="button" data-book-id="<?= $book->id ?>">-</button> -->
                                        <input type="number" class="form-control text-center cart-quantity-input" 
                                               value="<?= $quantity ?>" 
                                               min="1" max="<?= $book->stock ?>"
                                               data-book-id="<?= $book->id ?>">
                                        <!-- <button class="btn btn-outline-secondary cart-quantity-plus" 
                                                type="button" data-book-id="<?= $book->id ?>">+</button> -->
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <p class="h6 item-total" data-book-id="<?= $book->id ?>">
                                        <?= number_format($itemTotal, 0, '', ' ') ?> ₽
                                    </p>
                                </div>
                                <div class="col-md-1 text-end">
                                    <?= Html::a('❌', ['remove', 'id' => $book->id], [
                                        'class' => 'btn btn-outline-danger btn-sm',
                                        'title' => 'Удалить',
                                        'data' => [
                                            'confirm' => 'Удалить товар из корзины?',
                                            'method' => 'post',
                                        ]
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="text-start mt-3">
                    <?= Html::a('🗑️ Очистить корзину', ['clear'], [
                        'class' => 'btn btn-outline-danger',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите очистить корзину?',
                            'method' => 'post',
                        ]
                    ]) ?>
                    <?= Html::a('← Продолжить покупки', ['/book/index'], [
                        'class' => 'btn btn-outline-primary ms-2'
                    ]) ?>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow sticky-top" style="top: 20px;">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Ваш заказ</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Товары (<?= $totalItems ?> шт.):</span>
                            <span id="cart-subtotal"><?= number_format($total, 0, '', ' ') ?> ₽</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Доставка:</span>
                            <span>0 ₽</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Итого:</strong>
                            <strong class="h5 text-success" id="cart-total"><?= number_format($total, 0, '', ' ') ?> ₽</strong>
                        </div>
                        
                        <?= Html::a('🛍️ Оформить заказ', ['checkout'], [
                            'class' => 'btn btn-success btn-lg w-100 mb-2'
                        ]) ?>
                        
                        <div class="text-center">
                            <small class="text-muted">Бесплатная доставка от 2000 ₽</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
