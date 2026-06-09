<?php
/** @var yii\web\View $this */

$this->title = 'Главная';
?>
<?php
// views/site/index.php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Book[] $featuredBooks */
/** @var app\models\Book[] $newBooks */
/** @var app\models\Book[] $discountBooks */
/** @var array $popularCategories */

$this->title = 'Книжный магазин - читайте с удовольствием!';
$this->params['breadcrumbs'] = [];
?>


<!-- Герой-секция -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">Откройте мир книг</h1>
                <p class="hero-subtitle">Крупнейшая коллекция книг на любой вкус. От классики до современных бестселлеров.</p>
                <div class="hero-actions">
                    <?= Html::a('🛍️ Перейти в каталог', ['/book/index'], ['class' => 'btn btn-primary btn-lg me-3']) ?>
                    <?= Html::a('🔥 Смотреть акции', '#discounts', ['class' => 'btn btn-outline-light btn-lg']) ?>
                </div>
                <div class="hero-stats mt-5">
                    <div class="row">
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="stat-number">5000+</div>
                                <div class="stat-label">Книг в каталоге</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="stat-number">100+</div>
                                <div class="stat-label">Издательств</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">Доставка</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <img src="/images/hero-books.png" alt="Книги" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- О нас -->
<section class="about-section py-5">
    <div class="container">
        <div class="section-header mb-5">
            <h2 class="section-title">📖 О нашем магазине</h2>
            <p class="section-subtitle">История, миссия и ценности нашего книжного сообщества</p>
        </div>
        
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-image position-relative">
                    <img src="/images/about-store.jpg" alt="Наш магазин" class="img-fluid rounded shadow-lg">
                    <div class="about-badge bg-primary text-white p-3 rounded shadow position-absolute top-0 start-0 m-3">
                        <div class="fs-1">📚</div>
                        <div class="fw-bold">С 2010 года</div>
                        <div class="small">радуем читателей</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <h3 class="h3 mb-4">Мы создаем пространство для настоящих книголюбов</h3>
                    
                    <div class="about-story mb-4">
                        <p>Наш книжный магазин начал свою историю в 2010 году как небольшой семейный бизнес. 
                        Сегодня мы превратились в крупнейший онлайн-магазин книг в регионе, но сохранили 
                        душевную атмосферу и индивидуальный подход к каждому читателю.</p>
                    </div>
                    
                    <div class="about-mission mb-4">
                        <h4 class="h5 mb-3">Наша миссия</h4>
                        <p>Мы верим, что книги меняют жизнь к лучшему. Наша задача — сделать качественную 
                        литературу доступной каждому, помочь читателям находить именно те книги, 
                        которые станут для них открытием.</p>
                    </div>
                    
                    <div class="about-values row g-3">
                        <div class="col-md-6">
                            <div class="value-item p-3 rounded shadow-sm">
                                <div class="value-icon mb-2">✨</div>
                                <h5 class="h6 mb-2">Экспертный подбор</h5>
                                <p class="small mb-0">Наши консультанты — настоящие книжные эксперты</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="value-item p-3 rounded shadow-sm">
                                <div class="value-icon mb-2">🤝</div>
                                <h5 class="h6 mb-2">Честные цены</h5>
                                <p class="small mb-0">Прозрачная ценовая политика без скрытых наценок</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="value-item p-3 rounded shadow-sm">
                                <div class="value-icon mb-2">🌱</div>
                                <h5 class="h6 mb-2">Экологичность</h5>
                                <p class="small mb-0">Используем перерабатываемую упаковку</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="value-item p-3 rounded shadow-sm">
                                <div class="value-icon mb-2">❤️</div>
                                <h5 class="h6 mb-2">Поддержка авторов</h5>
                                <p class="small mb-0">Продвигаем молодых талантливых писателей</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="about-team mt-4">
                        <div class="d-flex align-items-center">
                            <div class="team-avatar me-3">
                                <img src="/images/team-avatar.jpg" alt="Наша команда" class="rounded-circle" width="60" height="60">
                            </div>
                            <div>
                                <h5 class="h6 mb-1">Команда Бумажного Феникса</h5>
                                <p class="small text-muted mb-0">12 опытных книголюбов всегда на связи</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Рекомендуемые книги -->
<section class="featured-section py-5">
    <div class="container">
        <div class="section-header mb-5">
            <h2 class="section-title">🌟 Рекомендуем к прочтению</h2>
            <p class="section-subtitle">Лучшие книги по версии наших читателей</p>
        </div>
        
        <?php if (!empty($featuredBooks)): ?>
            <div class="row g-4">
                <?php foreach ($featuredBooks as $book): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="book-card-featured position-relative shadow-sm h-100">
                            <!-- Кликабельная ссылка на всю карточку -->
                            <a href="<?= Url::to(['/book/view', 'id' => $book->id]) ?>" class="stretched-link book-card-link"></a>
                            
                            <div class="book-image" style="height: 250px; background: linear-gradient(135deg, #f9f9f9 0%, #f0f0f0 100%);">
                                <?= Html::img($book->getImageUrl(), [
                                    'class' => 'w-100 h-100',
                                    'style' => 'object-fit: contain; object-position: center; padding: 16px;',
                                    'alt' => $book->name,
                                    'loading' => 'lazy'
                                ]) ?>
                                <?php if ($book->getDiscountPercent() > 0): ?>
                                    <span class="discount-badge position-absolute top-0 start-0 m-2 z-2">-<?= $book->getDiscountPercent() ?>%</span>
                                <?php endif; ?>
                            </div>
                            <div class="book-info p-3">
                                <h3 class="book-title fs-5 mb-1"><?= Html::encode($book->name) ?></h3>
                                <p class="book-author text-muted small mb-2"><?= Html::encode($book->author) ?></p>
                                <div class="book-price mb-2">
                                    <span class="current-price text-success fw-bold"><?= number_format($book->price, 0, '', ' ') ?> ₽</span>
                                    <?php if ($book->old_price): ?>
                                        <span class="old-price text-muted text-decoration-line-through small ms-2"><?= number_format($book->old_price, 0, '', ' ') ?> ₽</span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Кнопка "В корзину" - отдельный элемент -->
                                <div class="position-absolute bottom-0 start-0 end-0 p-3" style="z-index: 10;">
    <?php if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()): ?>
        <div class="d-flex gap-2">
            <?php if ($book->isInStock()): ?>
                <button class="btn btn-primary btn-sm flex-grow-1 add-to-cart-home" data-book-id="<?= $book->id ?>">
                    🛒 В корзину
                </button>
            <?php else: ?>
                <button class="btn btn-outline-secondary btn-sm flex-grow-1" disabled>
                    Нет в наличии
                </button>
            <?php endif; ?>
            
            <?php $isInWishlist = \app\models\Wishlist::isInWishlist(Yii::$app->user->id, $book->id); ?>
            <button class="btn btn-sm wishlist-toggle-btn <?= $isInWishlist ? 'btn-danger' : 'btn-outline-danger' ?>"
                    data-book-id="<?= $book->id ?>"
                    title="<?= $isInWishlist ? 'Удалить из избранного' : 'Добавить в избранное' ?>">
                <i class="<?= $isInWishlist ? 'fas fa-heart' : 'far fa-heart' ?>"></i>
            </button>
        </div>
    <?php elseif (Yii::$app->user->isGuest): ?>
        <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-outline-primary btn-sm w-100">
            🛒 В корзину
        </a>
    <?php endif; ?>
</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <p class="text-muted">Скоро появятся рекомендуемые книги</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Скидки и акции -->
<section id="discounts" class="discount-section py-5">
    <div class="container">
        <div class="section-header mb-5">
            <h2 class="section-title">🔥 Горячие скидки</h2>
            <p class="section-subtitle">Успейте купить по выгодной цене</p>
        </div>
        
        <?php if (!empty($discountBooks)): ?>
            <div class="row g-4">
                <?php foreach ($discountBooks as $book): ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="discount-card position-relative shadow-sm h-100">
                            <!-- Кликабельная ссылка на всю карточку -->
                            <a href="<?= Url::to(['/book/view', 'id' => $book->id]) ?>" class="stretched-link book-card-link"></a>
                            
                            <div class="discount-percent position-absolute top-0 end-0 m-2 z-2 bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <span class="fw-bold">-<?= $book->getDiscountPercent() ?>%</span>
                            </div>
                            
                            <div class="discount-image" style="height: 220px; background: linear-gradient(135deg, #f9f9f9 0%, #f0f0f0 100%);">
                                <?= Html::img($book->getImageUrl(), [
                                    'class' => 'w-100 h-100',
                                    'style' => 'object-fit: contain; object-position: center; padding: 16px;',
                                    'alt' => $book->name,
                                    'loading' => 'lazy'
                                ]) ?>
                            </div>
                            
                            <div class="discount-info p-3">
                                <h3 class="discount-title fs-6 mb-1"><?= Html::encode(mb_strimwidth($book->name, 0, 40, '...')) ?></h3>
                                <p class="discount-author text-muted small mb-2"><?= Html::encode($book->author) ?></p>
                                <div class="discount-price mb-2">
                                    <span class="new-price text-success fw-bold fs-5"><?= number_format($book->price, 0, '', ' ') ?> ₽</span>
                                    <span class="old-price text-muted text-decoration-line-through small ms-2"><?= number_format($book->old_price, 0, '', ' ') ?> ₽</span>
                                </div>
                                
                                <!-- Кнопка "Купить сейчас" - отдельный элемент -->
                                <div style="position: relative; z-index: 3;">
    <?php if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()): ?>
        <div class="d-flex gap-2">
            <?php if ($book->isInStock()): ?>
                <button type="button" class="btn btn-danger flex-grow-1 add-to-cart-home" data-book-id="<?= $book->id ?>">
                    Купить сейчас
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-outline-secondary flex-grow-1" disabled>
                    Нет в наличии
                </button>
            <?php endif; ?>
            
            <?php $isInWishlist = \app\models\Wishlist::isInWishlist(Yii::$app->user->id, $book->id); ?>
            <button type="button" 
                    class="btn btn-sm wishlist-toggle-btn <?= $isInWishlist ? 'btn-danger' : 'btn-outline-danger' ?>"
                    data-book-id="<?= $book->id ?>"
                    title="<?= $isInWishlist ? 'Удалить из избранного' : 'Добавить в избранное' ?>"
                    style="width: 38px; display: flex; align-items: center; justify-content: center;">
                <i class="<?= $isInWishlist ? 'fas fa-heart' : 'far fa-heart' ?>"></i>
            </button>
        </div>
    <?php elseif (Yii::$app->user->isGuest): ?>
        <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-outline-light w-100">
            Купить сейчас
        </a>
    <?php endif; ?>
</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Новинки -->
<section class="new-books-section py-5">
    <div class="container">
        <div class="section-header mb-5">
            <h2 class="section-title">📚 Новинки</h2>
            <p class="section-subtitle">Самые свежие поступления в нашем магазине</p>
        </div>
        
        <?php if (!empty($newBooks)): ?>
            <div class="row g-4">
                <?php foreach ($newBooks as $book): ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="new-book-card position-relative shadow-sm h-100">
                            <!-- Кликабельная ссылка на всю карточку -->
                            <a href="<?= Url::to(['/book/view', 'id' => $book->id]) ?>" class="stretched-link book-card-link"></a>
                            
                            <div class="new-book-badge position-absolute top-0 start-0 m-2 z-2 bg-primary text-white px-2 py-1 rounded">
                                NEW
                            </div>
                            
                            <div class="new-book-image" style="height: 220px; background: linear-gradient(135deg, #f9f9f9 0%, #f0f0f0 100%);">
                                <?= Html::img($book->getImageUrl(), [
                                    'class' => 'w-100 h-100',
                                    'style' => 'object-fit: contain; object-position: center; padding: 16px;',
                                    'alt' => $book->name,
                                    'loading' => 'lazy'
                                ]) ?>
                            </div>
                            
                            <div class="new-book-info p-3">
                                <h3 class="new-book-title fs-6 mb-1"><?= Html::encode(mb_strimwidth($book->name, 0, 35, '...')) ?></h3>
                                <p class="new-book-author text-muted small mb-2"><?= Html::encode($book->author) ?></p>
                                <div class="new-book-price text-success fw-bold mb-2">
                                    <?= number_format($book->price, 0, '', ' ') ?> ₽
                                </div>
                                
                                <!-- Кнопки действий - отдельные элементы -->
                                <div class="position-absolute bottom-0 start-0 end-0 p-3" style="z-index: 10;">
    <?php if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()): ?>
        <div class="d-flex gap-2">
            <?php if ($book->isInStock()): ?>
                <button class="btn btn-primary btn-sm flex-grow-1 add-to-cart-home" data-book-id="<?= $book->id ?>">
                    🛒 В корзину
                </button>
            <?php else: ?>
                <button class="btn btn-outline-secondary btn-sm flex-grow-1" disabled>
                    Нет в наличии
                </button>
            <?php endif; ?>
            
            <?php $isInWishlist = \app\models\Wishlist::isInWishlist(Yii::$app->user->id, $book->id); ?>
            <button class="btn btn-sm wishlist-toggle-btn <?= $isInWishlist ? 'btn-danger' : 'btn-outline-danger' ?>"
                    data-book-id="<?= $book->id ?>"
                    title="<?= $isInWishlist ? 'Удалить из избранного' : 'Добавить в избранное' ?>">
                <i class="<?= $isInWishlist ? 'fas fa-heart' : 'far fa-heart' ?>"></i>
            </button>
        </div>
    <?php elseif (Yii::$app->user->isGuest): ?>
        <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-outline-primary btn-sm w-100">
            🛒 В корзину
        </a>
    <?php endif; ?>
</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-5">
                <?= Html::a('Смотреть все новинки →', ['/book/index'], ['class' => 'btn btn-outline-secondary btn-lg']) ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Отзывы покупателей -->
<section class="reviews-section py-5">
    <div class="container">
        <div class="section-header mb-5">
            <h2 class="section-title">💬 Отзывы наших читателей</h2>
            <p class="section-subtitle">Что говорят клиенты о нашем магазине</p>
        </div>
        
        <div class="row g-4">
            <?php 
            // Получаем последние 3 одобренных отзыва
            $reviews = \app\models\Review::find()
                ->where(['status' => \app\models\Review::STATUS_APPROVED])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(3)
                ->all();
            
            if (!empty($reviews)): 
                foreach ($reviews as $review): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="review-card shadow-sm p-4 rounded h-100">
                            <div class="review-header d-flex align-items-center mb-3">
                                <div class="review-avatar <?= $review->getAvatarColor() ?> rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px;">
                                    <span class="text-white fw-bold"><?= $review->getUserInitials() ?></span>
                                </div>
                                <div>
                                    <h5 class="h6 mb-1"><?= Html::encode($review->user->username ?? 'Гость') ?></h5>
                                    <div class="review-rating text-warning">
                                        <?= $review->getRatingStars() ?>
                                    </div>
                                </div>
                            </div>
                            <div class="review-content">
                                <p class="mb-2"><?= nl2br(Html::encode(mb_strimwidth($review->text, 0, 150, '...'))) ?></p>
                                <small class="text-muted">
                                    <?= Html::a(
                                        Html::encode(mb_strimwidth($review->book->name, 0, 30, '...')), 
                                        ['/book/view', 'id' => $review->book_id],
                                        ['class' => 'text-decoration-none']
                                    ) ?>
                                </small>
                            </div>
                            <div class="review-footer mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    <?= Yii::$app->formatter->asRelativeTime($review->created_at) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; 
            else: ?>
                <!-- Запасные отзывы, если нет реальных -->
                <div class="col-lg-4 col-md-6">
                    <div class="review-card shadow-sm p-4 rounded h-100">
                        <div class="review-header d-flex align-items-center mb-3">
                            <div class="review-avatar bg-soft-blue rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <span class="fs-4">А</span>
                            </div>
                            <div>
                                <h5 class="h6 mb-1">Анна Смирнова</h5>
                                <div class="review-rating text-warning">
                                    ★★★★★
                                </div>
                            </div>
                        </div>
                        <div class="review-content">
                            <p class="mb-0">Заказываю книги здесь уже 3 года. Всегда нахожу именно то, что нужно. 
                            Отличный выбор, быстрая доставка и внимательные консультанты.</p>
                        </div>
                        <div class="review-footer mt-3 pt-3 border-top">
                            <small class="text-muted">Прочитано книг: 47</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="review-card shadow-sm p-4 rounded h-100">
                        <div class="review-header d-flex align-items-center mb-3">
                            <div class="review-avatar bg-dusty-rose rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <span class="fs-4">М</span>
                            </div>
                            <div>
                                <h5 class="h6 mb-1">Михаил Ковалев</h5>
                                <div class="review-rating text-warning">
                                    ★★★★★
                                </div>
                            </div>
                        </div>
                        <div class="review-content">
                            <p class="mb-0">Искал редкое издание по истории архитектуры. В других магазинах не было, 
                            здесь нашли и привезли под заказ. Большое спасибо за оперативность!</p>
                        </div>
                        <div class="review-footer mt-3 pt-3 border-top">
                            <small class="text-muted">Клиент с 2018 года</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="review-card shadow-sm p-4 rounded h-100">
                        <div class="review-header d-flex align-items-center mb-3">
                            <div class="review-avatar bg-sage rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <span class="fs-4">Е</span>
                            </div>
                            <div>
                                <h5 class="h6 mb-1">Екатерина Волкова</h5>
                                <div class="review-rating text-warning">
                                    ★★★★☆
                                </div>
                            </div>
                        </div>
                        <div class="review-content">
                            <p class="mb-0">Отличный магазин для покупки подарков. Всегда свежие новинки, 
                            красивая упаковка. Особенно нравится раздел с автографами авторов.</p>
                        </div>
                        <div class="review-footer mt-3 pt-3 border-top">
                            <small class="text-muted">Сделано заказов: 23</small>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <?php if (!empty($reviews)): ?>
                <?= Html::a('Показать больше отзывов', ['/review/index'], ['class' => 'btn btn-outline-primary']) ?>
            <?php endif; ?>
            <?php if (!Yii::$app->user->isGuest): ?>
                <!-- <?= Html::a('Оставить отзыв', ['/review/create'], ['class' => 'btn btn-primary ms-3']) ?> -->
                <?= Html::a('Мои отзывы', ['/review/my'], ['class' => 'btn btn-outline-secondary ms-3']) ?>
            <?php else: ?>
                <?= Html::a('Оставить отзыв', ['/site/login'], ['class' => 'btn btn-primary ms-3']) ?>
            <?php endif; ?>
        </div>
    </div>
</section>
    

<!-- Преимущества -->
<section class="benefits-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="benefit-card shadow-sm p-4 rounded h-100 text-center">
                    <div class="benefit-icon fs-1 mb-3">🚚</div>
                    <h3 class="h5">Бесплатная доставка</h3>
                    <p class="text-muted small">При заказе от 2000 рублей по всему городу</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="benefit-card shadow-sm p-4 rounded h-100 text-center">
                    <div class="benefit-icon fs-1 mb-3">🔄</div>
                    <h3 class="h5">Легкий возврат</h3>
                    <p class="text-muted small">Возврат в течение 14 дней без вопросов</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="benefit-card shadow-sm p-4 rounded h-100 text-center">
                    <div class="benefit-icon fs-1 mb-3">🎁</div>
                    <h3 class="h5">Подарки к покупкам</h3>
                    <p class="text-muted small">Заказы от 5000 рублей — получайте подарки</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="benefit-card shadow-sm p-4 rounded h-100 text-center">
                    <div class="benefit-icon fs-1 mb-3">📞</div>
                    <h3 class="h5">Поддержка 24/7</h3>
                    <p class="text-muted small">Наши консультанты всегда на связи</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Рассылка -->
<section class="newsletter-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2>📧 Подпишитесь на рассылку</h2>
                <p class="lead mb-4">Узнавайте первыми о новинках, акциях и специальных предложениях</p>
                
                <div class="newsletter-form-wrapper">
                    <form class="newsletter-form" id="subscribe-form">
                        <div class="newsletter-form-group">
                            <input type="email" 
                                   class="newsletter-input" 
                                   id="newsletter-email"
                                   placeholder="Ваш email" 
                                   required>
                            <button type="submit" class="newsletter-button" id="subscribe-btn">
                                Подписаться
                            </button>
                        </div>
                        
                        <div id="subscribe-message" class="mt-3"></div>
                        
                        <p class="newsletter-agreement">
    Подписываясь, вы соглашаетесь с 
    <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">политикой конфиденциальности</a>
</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Модальное окно политики конфиденциальности -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-light rounded-top-4">
                <h5 class="modal-title">📜 Политика конфиденциальности</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body p-4">
                <h6 class="fw-bold">1. Общие положения</h6>
                <p>Настоящая политика конфиденциальности описывает, как интернет-магазин «Бумажный Феникс» собирает, 
                использует и защищает персональные данные пользователей.</p>
                
                <h6 class="fw-bold mt-3">2. Какие данные мы собираем</h6>
                <ul>
                    <li>Имя и фамилия</li>
                    <li>Адрес электронной почты</li>
                    <li>Номер телефона</li>
                    <li>Адрес доставки</li>
                    <li>История заказов</li>
                </ul>
                
                <h6 class="fw-bold mt-3">3. Как мы используем данные</h6>
                <p>Мы используем ваши данные исключительно для:</p>
                <ul>
                    <li>Обработки и доставки заказов</li>
                    <li>Отправки уведомлений о статусе заказа</li>
                    <li>Рассылки новостей и акций (только с вашего согласия)</li>
                    <li>Улучшения качества обслуживания</li>
                </ul>
                
                <h6 class="fw-bold mt-3">4. Защита данных</h6>
                <p>Мы принимаем все необходимые меры для защиты ваших персональных данных от 
                несанкционированного доступа, изменения, раскрытия или уничтожения.</p>
                
                <h6 class="fw-bold mt-3">5. Файлы cookie</h6>
                <p>Наш сайт использует файлы cookie для улучшения работы и персонализации контента. 
                Продолжая использовать сайт, вы соглашаетесь на использование cookie.</p>
                
                <h6 class="fw-bold mt-3">6. Передача данных третьим лицам</h6>
                <p>Мы не продаем, не обмениваем и не передаем ваши персональные данные третьим лицам 
                без вашего согласия, за исключением случаев, предусмотренных законодательством.</p>
                
                <h6 class="fw-bold mt-3">7. Контакты</h6>
                <p>По всем вопросам, связанным с обработкой персональных данных, обращайтесь:<br>
                📧 Email: privacy@phoenix-books.ru<br>
                📞 Телефон: +7 (XXX) XXX-XX-XX</p>
                
                <p class="text-muted mt-4 mb-0"><small>Последнее обновление: <?= date('d.m.Y') ?></small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary rounded-3" data-bs-dismiss="modal">Понятно</button>
            </div>
        </div>
    </div>
</div>
</section>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("subscribe-form");
    const emailInput = document.getElementById("newsletter-email");
    const messageDiv = document.getElementById("subscribe-message");
    const button = document.getElementById("subscribe-btn");

    if (!form) {
        console.error("Subscribe form not found!");
        return;
    }

    // Функция для определения URL
    function getSubscribeUrl() {
        // Попробуем разные варианты
        const baseUrl = window.location.origin;
        const paths = [
            '/site/subscribe',
            '/index.php/site/subscribe', 
            '/index.php?r=site/subscribe',
            '/?r=site/subscribe'
        ];
        
        // Проверим какой путь доступен
        return baseUrl + '/site/subscribe'; // Начнем с этого
    }

    // Функция для получения CSRF токена
    function getCsrfToken() {
        // Ищем в мета-тегах
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) return metaToken.content;
        
        // Ищем в форме
        const csrfInput = document.querySelector('input[name="_csrf"]');
        if (csrfInput) return csrfInput.value;
        
        // Глобальный Yii объект
        if (typeof yii !== 'undefined' && yii.getCsrfToken) {
            return yii.getCsrfToken();
        }
        
        // Ищем все скрытые поля
        const allInputs = document.querySelectorAll('input[type="hidden"]');
        for (let input of allInputs) {
            if (input.name.includes('csrf')) {
                return input.value;
            }
        }
        
        console.warn('CSRF токен не найден');
        return '';
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Очищаем сообщения
        messageDiv.innerHTML = '';
        
        // Валидация email
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email || !emailRegex.test(email)) {
            showMessage('Введите корректный email адрес', 'error');
            emailInput.focus();
            return;
        }

        // Показываем загрузку
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Отправка...';
        button.disabled = true;

        // Подготавливаем данные
        const formData = new FormData();
        formData.append('email', email);
        formData.append('_csrf', getCsrfToken());

        // Отправляем запрос
        fetch(getSubscribeUrl(), {
            method: "POST",
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status, response.statusText);
            
            if (response.status === 404) {
                throw new Error('Страница не найдена (404). Проверьте URL правила.');
            }
            
            if (!response.ok) {
                throw new Error('HTTP ошибка: ' + response.status);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data && data.success) {
                showMessage(data.message || 'Спасибо за подписку!', 'success');
                form.reset();
            } else {
                showMessage(data?.message || 'Произошла ошибка', 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            
            // Показываем разные сообщения для разных ошибок
            if (error.message.includes('404')) {
                showMessage('Ошибка: Страница не найдена. Сообщите администратору.', 'error');
            } else if (error.message.includes('NetworkError')) {
                showMessage('Ошибка сети. Проверьте подключение к интернету.', 'error');
            } else {
                showMessage('Ошибка соединения с сервером. Попробуйте позже.', 'error');
            }
        })
        .finally(() => {
            // Восстанавливаем кнопку
            button.innerHTML = originalText;
            button.disabled = false;
        });
    });

    function showMessage(text, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? '✅' : '❌';
        
        messageDiv.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <span class="me-2">${icon}</span>
                    <span>${text}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        `
    }
    
    
});
</script>
<?php
$csrfToken = Yii::$app->request->csrfToken;
$addToCartUrl = Url::to(['/cart/add']);
?>

<script>
document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.add-to-cart-home').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const bookId = this.dataset.bookId;
            const cartCounter = document.querySelector('#cart-count');
            const btn = this;

            fetch('<?= $addToCartUrl ?>?id=' + bookId, {
    method: 'POST',
    headers: {
        'X-CSRF-Token': '<?= $csrfToken ?>',
        'X-Requested-With': 'XMLHttpRequest', // ← ВАЖНО
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({})


            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {

                    // 🔥 Моментальное обновление счётчика
                    if (cartCounter) {
                        cartCounter.textContent = data.cartCount;
                    }

                    // 🔄 Меняем кнопку
                    btn.textContent = '🛒 В корзине';
                    btn.classList.add('btn-outline-success');
                    btn.classList.remove('btn-success');

                    showNotification('📚 Товар добавлен в корзину!', 'success');

                } else {
                    showNotification('Ошибка: ' + data.message, 'error');
                }

            })
            .catch(err => {
                showNotification('Сервер недоступен', 'error');
                console.error(err);
            });
        });
    });
});
</script>
