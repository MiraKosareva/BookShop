<?php
// views/book/view.php

use app\models\Wishlist;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-view">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body text-center">
    <?php if ($model->getDiscountPercent() > 0): ?>
        <div class="discount-badge-large">
            -<?= $model->getDiscountPercent() ?>%
        </div>
    <?php endif; ?>
    
    <?php 
    $allImages = $model->images;
    $hasExtraImages = !empty($allImages);
    ?>
    
    <?php if ($hasExtraImages): ?>
        <!-- Карусель -->
        <div id="productCarousel" class="carousel slide" data-bs-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <?= Html::img($model->getImageUrl(), [
                        'class' => 'd-block w-100',
                        'style' => 'max-height: 500px; object-fit: contain;',
                        'alt' => $model->name
                    ]) ?>
                </div>
                <?php foreach ($allImages as $img): ?>
                    <div class="carousel-item">
                        <img src="<?= $img->image_path ?>" 
                             class="d-block w-100" 
                             style="max-height: 500px; object-fit: contain;"
                             alt="<?= $model->name ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle"></span>
            </button>
            <!-- Миниатюры -->
            <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                <img src="<?= $model->getImageUrl() ?>" 
                     class="img-thumbnail product-thumbnail active" 
                     style="width: 60px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid #a8c3d5;"
                     onclick="$('#productCarousel').carousel(0)">
                <?php $idx = 1; foreach ($allImages as $img): ?>
                    <img src="<?= $img->image_path ?>" 
                         class="img-thumbnail product-thumbnail" 
                         style="width: 60px; height: 80px; object-fit: cover; cursor: pointer;"
                         onclick="$('#productCarousel').carousel(<?= $idx ?>)">
                <?php $idx++; endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <?= Html::img($model->getImageUrl(), [
            'class' => 'img-fluid rounded',
            'alt' => $model->name,
            'style' => 'max-height: 500px; width: auto;'
        ]) ?>
    <?php endif; ?>
</div>
            </div>
        </div>
        
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h1 class="h2 mb-3"><?= Html::encode($model->name) ?></h1>
                    
                    <div class="author-section mb-3">
                        <h4 class="text-muted"><?= Html::encode($model->author) ?></h4>
                    </div>
                    
                    <?php if (!empty($model->category)): ?>
                        <span class="badge bg-primary mb-3"><?= Html::encode($model->category) ?></span>
                    <?php endif; ?>
                    
                    <div class="price-section mb-4">
                        <?php if ($model->getDiscountPercent() > 0): ?>
                            <div class="d-flex align-items-center">
                                <span class="h2 text-success me-3"><?= number_format($model->price, 0, '', ' ') ?> ₽</span>
                                <span class="h4 text-muted text-decoration-line-through"><?= number_format($model->old_price, 0, '', ' ') ?> ₽</span>
                            </div>
                        <?php else: ?>
                            <span class="h2 text-success"><?= number_format($model->price, 0, '', ' ') ?> ₽</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="stock-section mb-4">
                        <?php $stockStatus = $model->getStockStatus(); ?>
                        <div class="d-flex align-items-center">
                            <span class="badge <?= $stockStatus['class'] ?> me-2 fs-6"><?= $stockStatus['text'] ?></span>
                            <?php if ($model->stock > 0): ?>
                                <span class="text-muted">Осталось: <?= $model->stock ?> шт.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin() && $model->isInStock()): ?>
    <div class="purchase-section mb-4">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="input-group input-group-lg" style="width: 140px;">
                    <button class="btn btn-outline-secondary quantity-minus" type="button">-</button>
                    <input type="number" class="form-control text-center quantity-input" 
                           value="1" min="1" max="<?= $model->stock ?>" 
                           data-book-id="<?= $model->id ?>">
                    <button class="btn btn-outline-secondary quantity-plus" type="button">+</button>
                </div>
            </div>
            <div class="col">
                <button 
                    class="btn btn-success btn-lg w-100 add-to-cart-single" 
                    data-book-id="<?= $model->id ?>">
                    🛒 Добавить в корзину
                </button>
            </div>
            <!-- КНОПКА ИЗБРАННОГО -->
            <?php 
            $inWishlist = Wishlist::isInWishlist(Yii::$app->user->id, $model->id);
            ?>
            <div class="col-auto">
                <button class="btn btn-lg wishlist-toggle-btn <?= $inWishlist ? 'btn-danger' : 'btn-outline-danger' ?>" 
                        data-book-id="<?= $model->id ?>"
                        title="<?= $inWishlist ? 'Удалить из избранного' : 'Добавить в избранное' ?>">
                    <i class="<?= $inWishlist ? 'fas' : 'far' ?> fa-heart"></i>
                </button>
            </div>
        </div>
    </div>
<?php elseif (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> Товар временно отсутствует на складе
    </div>
<?php endif; ?>
<?php if (Yii::$app->user->isGuest): ?>
    <div class="purchase-section mb-4">
        <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-primary btn-lg w-100">
            🛒 В корзину
        </a>
    </div>
<?php endif; ?>
                    
                    <?php if (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin()): ?>
                        <div class="admin-actions mb-4">
                            <div class="btn-group">
                                <?= Html::a('✏️ Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                                <?= Html::a('🗑️ Удалить', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить этот товар?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Описание товара</h5>
                </div>
                <div class="card-body">
                    <p class="card-text"><?= nl2br(Html::encode($model->description)) ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!empty($model->publisher) || !empty($model->publication_year)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Характеристики</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if (!empty($model->publisher)): ?>
                            <div class="col-md-6">
                                <strong>Издательство:</strong> <?= Html::encode($model->publisher) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($model->publication_year)): ?>
                            <div class="col-md-6">
                                <strong>Год издания:</strong> <?= Html::encode($model->publication_year) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Отзывы -->
<section id="reviews" class="reviews-section mt-5">
    <div class="card">
        <div class="card-header bg-light">
            <h3 class="h5 mb-0">Отзывы читателей</h3>
        </div>
        <div class="card-body">
            <?php if ($model->reviewsCount > 0): ?>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="display-4 text-primary fw-bold mb-2">
                                <?= $model->averageRating ?>
                            </div>
                            <div class="rating text-warning mb-2 fs-5">
                                <?php 
                                $avg = round($model->averageRating);
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $avg ? '★' : '☆';
                                }
                                ?>
                            </div>
                            <div class="text-muted small">
                                <?= $model->reviewsCount ?> отзывов
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <?php foreach ($model->approvedReviews as $review): ?>
                            <div class="review-item mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="review-avatar <?= $review->getAvatarColor() ?> rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold"><?= $review->getUserInitials() ?></span>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?= Html::encode($review->user->username ?? 'Гость') ?></h6>
                                            <div class="rating text-warning small">
                                                <?= $review->getRatingStars() ?>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <?= Yii::$app->formatter->asRelativeTime($review->created_at) ?>
                                    </small>
                                </div>
                                <p class="mb-0"><?= nl2br(Html::encode($review->text)) ?></p>
                                
                                <?php if ($review->canEdit()): ?>
                                    <div class="mt-2">
                                        <?= Html::a('Редактировать', ['/review/update', 'id' => $review->id], [
                                            'class' => 'btn btn-sm btn-outline-primary'
                                        ]) ?>
                                        <?= Html::a('Удалить', ['/review/delete', 'id' => $review->id], [
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'data' => [
                                                'confirm' => 'Вы уверены, что хотите удалить этот отзыв?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <p class="text-muted mb-4">Пока нет отзывов. Будьте первым, кто поделится мнением об этой книге!</p>
                </div>
            <?php endif; ?>
            
            <?php if (!Yii::$app->user->isGuest): ?>
                <?php if ($model->hasUserReview()): ?>
                    <div class="alert alert-info">
                        Вы уже оставляли отзыв на эту книгу. 
                        <?= Html::a('Посмотреть мои отзывы', ['/review/my'], ['class' => 'alert-link']) ?>
                    </div>
                <?php else: ?>
                    <?= Html::a('Написать отзыв', ['/review/create', 'book_id' => $model->id], [
                        'class' => 'btn btn-primary'
                    ]) ?>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-light">
                    <?= Html::a('Войдите', ['/site/login'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                    , чтобы оставить отзыв
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
// JavaScript для управления количеством и AJAX добавления в корзину
$js = <<<JS
// Управление количеством
$('.quantity-plus').click(function() {
    var input = $(this).closest('.input-group').find('.quantity-input');
    var max = parseInt(input.attr('max'));
    var value = parseInt(input.val());
    if (value < max) {
        input.val(value + 1);
    }
});

$('.quantity-minus').click(function() {
    var input = $(this).closest('.input-group').find('.quantity-input');
    var min = parseInt(input.attr('min'));
    var value = parseInt(input.val());
    if (value > min) {
        input.val(value - 1);
    }
});


JS;

$this->registerJs($js);
?>

