<?php
// views/book/index.php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use app\models\Book;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Каталог книг';
$this->params['breadcrumbs'][] = $this->title;

// Получаем все категории для фильтра
$categories = Book::getAllCategories();
?>

<div class="book-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin()): ?>
            <?= Html::a('➕ Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

    <div class="row">
        <!-- Кнопка фильтров для мобильных -->
        <button class="btn btn-outline-primary w-100 d-md-none mb-3" type="button" onclick="openFilter()">
            <i class="fas fa-filter"></i> Фильтры
        </button>

        <!-- Оверлей -->
        <div class="filter-overlay d-md-none" id="filterOverlay" onclick="closeFilter()"></div>

        <!-- Боковая панель с фильтрами -->
        <div class="col-lg-3 col-md-4 mb-4" id="filterSidebar">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Фильтры</h5>
                    <button type="button" class="btn-close d-md-none" onclick="closeFilter()"></button>
                </div>
                <div class="card-body">

                    <!-- Фильтр по категориям -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3"><i class="fas fa-folder me-2"></i>Категории</h6>
                        <div class="list-group list-group-flush">
                            <a href="<?= Url::to(['index']) ?>"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= !Yii::$app->request->get('category') ? 'active' : '' ?>">
                                Все категории
                                <span class="badge bg-primary rounded-pill">
                                    <?= Book::find()->count() ?>
                                </span>
                            </a>

                            <?php foreach ($categories as $category): ?>
                                <?php
                                $count = Book::find()->where(['category' => $category])->count();
                                if ($count > 0):
                                ?>
                                    <a href="<?= Url::to(['index', 'category' => $category]) ?>"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= Yii::$app->request->get('category') == $category ? 'active' : '' ?>">
                                        <?= Html::encode($category) ?>
                                        <span class="badge bg-primary rounded-pill">
                                            <?= $count ?>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Фильтр по наличию -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3"><i class="fas fa-box me-2"></i>Наличие</h6>
                        <div class="list-group list-group-flush">
                            <a href="<?= Url::to(['index', 'stock' => 'available']) ?>"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= Yii::$app->request->get('stock') == 'available' ? 'active' : '' ?>">
                                В наличии
                                <span class="badge bg-success rounded-pill">
                                    <?= Book::find()->where(['>', 'stock', 0])->count() ?>
                                </span>
                            </a>
                            <a href="<?= Url::to(['index', 'stock' => 'outofstock']) ?>"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= Yii::$app->request->get('stock') == 'outofstock' ? 'active' : '' ?>">
                                Нет в наличии
                                <span class="badge bg-secondary rounded-pill">
                                    <?= Book::find()->where(['stock' => 0])->count() ?>
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Фильтр по акциям -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3"><i class="fas fa-percent me-2"></i>Акции</h6>
                        <div class="list-group list-group-flush">
                            <a href="<?= Url::to(['index', 'discount' => 'yes']) ?>"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= Yii::$app->request->get('discount') == 'yes' ? 'active' : '' ?>">
                                Со скидкой
                                <span class="badge bg-danger rounded-pill">
                                    <?= Book::find()->where(['>', 'old_price', 0])->count() ?>
                                </span>
                            </a>
                            <a href="<?= Url::to(['index', 'featured' => 'yes']) ?>"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= Yii::$app->request->get('featured') == 'yes' ? 'active' : '' ?>">
                                Рекомендуемые
                                <span class="badge bg-warning rounded-pill">
                                    <?= Book::find()->where(['is_featured' => 1])->count() ?>
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Кнопка сброса фильтров -->
                    <?php if (Yii::$app->request->get()): ?>
                        <div class="d-grid">
                            <a href="<?= Url::to(['index']) ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Сбросить фильтры
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Информация о фильтрах -->
            <?php if (Yii::$app->request->get()): ?>
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">Активные фильтры:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php if ($category = Yii::$app->request->get('category')): ?>
                                <span class="badge bg-primary">
                                    Категория: <?= Html::encode($category) ?>
                                    <a href="<?= Url::current(['category' => null]) ?>" class="text-white ms-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            <?php endif; ?>

                            <?php if ($stock = Yii::$app->request->get('stock')): ?>
                                <span class="badge bg-success">
                                    <?= $stock == 'available' ? 'В наличии' : 'Нет в наличии' ?>
                                    <a href="<?= Url::current(['stock' => null]) ?>" class="text-white ms-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            <?php endif; ?>

                            <?php if ($discount = Yii::$app->request->get('discount')): ?>
                                <span class="badge bg-danger">
                                    Со скидкой
                                    <a href="<?= Url::current(['discount' => null]) ?>" class="text-white ms-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            <?php endif; ?>

                            <?php if ($featured = Yii::$app->request->get('featured')): ?>
                                <span class="badge bg-warning">
                                    Рекомендуемые
                                    <a href="<?= Url::current(['featured' => null]) ?>" class="text-white ms-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Основной контент -->
        <div class="col-lg-9 col-md-8">
            <?php
            // Получаем активные фильтры
            $query = Book::find();

            // Применяем фильтры
            if ($category = Yii::$app->request->get('category')) {
                $query->andWhere(['category' => $category]);
            }

            if ($stock = Yii::$app->request->get('stock')) {
                if ($stock == 'available') {
                    $query->andWhere(['>', 'stock', 0]);
                } elseif ($stock == 'outofstock') {
                    $query->andWhere(['stock' => 0]);
                }
            }

            if ($discount = Yii::$app->request->get('discount')) {
                $query->andWhere(['>', 'old_price', 0]);
            }

            if ($featured = Yii::$app->request->get('featured')) {
                $query->andWhere(['is_featured' => 1]);
            }

            // Создаем DataProvider с отфильтрованным запросом
            $sort = Yii::$app->request->get('sort', 'name');
            $sortDirection = SORT_ASC;

            // Если начинается с минуса - сортировка по убыванию
            if (strpos($sort, '-') === 0) {
                $sort = ltrim($sort, '-');
                $sortDirection = SORT_DESC;
            }

            $filteredDataProvider = new \yii\data\ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 12,
                ],
                'sort' => [
                    'defaultOrder' => [
                        $sort => $sortDirection,
                    ],
                    'attributes' => [
                        'name',
                        'price',
                        'created_at',
                    ],
                ],
            ]);
            ?>

            <!-- Информация о результатах -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="text-muted">
                    Найдено книг: <strong><?= $filteredDataProvider->totalCount ?></strong>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <?php
                        $currentSort = Yii::$app->request->get('sort', 'name');
                        $sortLabels = [
                            'name' => 'По названию (А-Я)',
                            '-name' => 'По названию (Я-А)',
                            'price' => 'По цене (сначала дешевые)',
                            '-price' => 'По цене (сначала дорогие)',
                            '-created_at' => 'Сначала новинки',
                        ];
                        echo $sortLabels[$currentSort] ?? 'Сортировка';
                        ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><?= Html::a('По названию (А-Я)', Url::current(['sort' => 'name']), ['class' => 'dropdown-item']) ?></li>
                        <li><?= Html::a('По названию (Я-А)', Url::current(['sort' => '-name']), ['class' => 'dropdown-item']) ?></li>
                        <li><?= Html::a('По цене (сначала дешевые)', Url::current(['sort' => 'price']), ['class' => 'dropdown-item']) ?></li>
                        <li><?= Html::a('По цене (сначала дорогие)', Url::current(['sort' => '-price']), ['class' => 'dropdown-item']) ?></li>
                        <li><?= Html::a('Сначала новинки', Url::current(['sort' => '-created_at']), ['class' => 'dropdown-item']) ?></li>
                    </ul>
                </div>
            </div>

            <!-- Список книг -->
            <?= ListView::widget([
                'dataProvider' => $filteredDataProvider,
                'itemView' => '_book_item',
                'layout' => "<div class='row'>{items}</div>\n<div class='mt-4'>{pager}</div>",
                'itemOptions' => ['class' => 'col-xl-4 col-lg-6 col-md-6 mb-4'],
                'pager' => [
                    'options' => ['class' => 'pagination justify-content-center'],
                    'linkOptions' => ['class' => 'page-link'],
                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                ],
                'emptyText' => '
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h4>📖 Книги не найдены</h4>
                            <p class="mb-3">По вашему запросу ничего не найдено</p>
                            ' . Html::a('Сбросить фильтры', ['index'], ['class' => 'btn btn-primary']) . '
                        </div>
                    </div>
                ',
                'emptyTextOptions' => ['class' => 'col-12'],
            ]); ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
// Функции для мобильных фильтров
window.openFilter = function() {
    var sidebar = document.getElementById('filterSidebar');
    var overlay = document.getElementById('filterOverlay');
    sidebar.classList.add('filter-sidebar-mobile', 'open');
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
};

window.closeFilter = function() {
    var sidebar = document.getElementById('filterSidebar');
    var overlay = document.getElementById('filterOverlay');
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
};

// Сохранение состояния фильтров в localStorage
$(document).on('click', '.list-group-item-action', function(e) {
    var filters = {};
    $('.list-group-item-action.active').each(function() {
        var href = $(this).attr('href');
        if (href) {
            var urlParams = new URLSearchParams(href.split('?')[1]);
            urlParams.forEach(function(value, key) {
                filters[key] = value;
            });
        }
    });
    localStorage.setItem('bookFilters', JSON.stringify(filters));
});

// Восстановление фильтров и подсветка активных
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    
    $('.list-group-item-action').each(function() {
        var href = $(this).attr('href');
        if (href) {
            var itemParams = new URLSearchParams(href.split('?')[1]);
            var isActive = true;
            
            itemParams.forEach(function(value, key) {
                if (urlParams.get(key) !== value) {
                    isActive = false;
                }
            });
            
            if (isActive && itemParams.toString() !== '') {
                $(this).addClass('active');
            }
        }
    });
    
    // Сортировка
    $('.dropdown-item').click(function(e) {
        var url = $(this).attr('href');
        if (url) {
            var currentUrl = new URL(window.location);
            var currentParams = new URLSearchParams(currentUrl.search);
            var targetUrl = new URL(url, window.location.origin);
            var targetParams = new URLSearchParams(targetUrl.search);
            
            currentParams.forEach(function(value, key) {
                if (!['sort', 'page'].includes(key)) {
                    targetParams.set(key, value);
                }
            });
            
            window.location.search = targetParams.toString();
            e.preventDefault();
        }
    });
});
JS;

$this->registerJs($js);
?>