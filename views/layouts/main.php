<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\web\YiiAsset;

YiiAsset::register($this);


$cart = new \app\models\Cart();
$cartCount = $cart->getTotalCount();


AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
     <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/style.css"> -->
    
    <?php if (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index'): ?>
    <link href="/css/home.css" rel="stylesheet">
<?php endif; ?>
<?php if (Yii::$app->controller->id === 'profile'): ?>
    <link href="/css/profile.css" rel="stylesheet">
<?php endif; ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?php
// layouts/main.php (фрагмент header)

$cart = new \app\models\Cart();
$cartCount = $cart->getTotalCount();

// Избранное считаем только для авторизованных
$wishlistCount = 0;
if (!Yii::$app->user->isGuest) {
    $wishlistCount = \app\models\Wishlist::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->count();
}
?>

<header id="header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <!-- ЛОГО -->
            <a class="navbar-brand" href="<?Yii::$app->homeUrl?>">
                <img src="/images/logo5.png" alt="Бумажный Феникс" style="height: 60px;">
            </a>
            
            <!-- Бургер -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNav">
                <!-- ОСНОВНЫЕ ПУНКТЫ СЛЕВА -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/site/index">
                            <i class="fas fa-home"></i> Главная
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/book/index">
                            <i class="fas fa-book"></i> Каталог
                        </a>
                    </li>
                </ul>
                
                <!-- ПОИСК -->
                <form class="d-flex me-3" action="/book/search" method="get">
                    <input class="form-control me-2" type="search" name="q" 
                           placeholder="Поиск книг..." value="<?= Yii::$app->request->get('q', '') ?>">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <!-- ИКОНКИ СПРАВА -->
                <ul class="navbar-nav">
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/index" title="Панель администратора">
                                <i class="fas fa-cogs"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    <!-- Вход/Выход -->
                    <?php if (Yii::$app->user->isGuest): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/site/login">
                                <i class="fas fa-sign-in-alt"></i> Войти
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <?= Html::beginForm(['/site/logout']) ?>
                            <?= Html::submitButton(
                                '<i class="fas fa-sign-out-alt"></i>',
                                ['class' => 'nav-link btn btn-link']
                            ) ?>
                            <?= Html::endForm() ?>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Избранное -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/wishlist/index" title="Избранное">
                            <i class="fas fa-heart"></i>
                            <?php if ($wishlistCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                      style="font-size: 0.6rem;">
                                    <?= $wishlistCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <!-- Корзина -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/cart/index" title="Корзина">
                            <i class="fas fa-shopping-cart"></i>
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" 
                                  style="font-size: 0.6rem;">
                                <?= $cartCount ?>
                            </span>
                        </a>
                    </li>
                    
                    <!-- Профиль -->
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile/index" title="Профиль">
                                <i class="fas fa-user"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; My Company <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/app.js"></script>

<?php if ($this->context->id === 'cart'): ?>
    <script src="/js/cart.js"></script>
<?php endif; ?>

<?php if ($this->context->id === 'book' && Yii::$app->controller->action->id === 'view'): ?>
    <script src="/js/product.js"></script>
<?php endif; ?>
<?php if ($this->context->id === 'wishlist'): ?>
    <script src="/js/wishlist.js"></script>
<?php endif; ?>

<script>
$(document).ready(function() {
    if (typeof initQuickView === 'function') {
        initQuickView();
    }
    
    if (typeof initSearch === 'function') {
        initSearch();
    }
});
</script>
</body>
</html>
<?php $this->endPage() ?>