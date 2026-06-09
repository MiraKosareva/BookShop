<?php
// views/profile/index.php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var app\models\Orderbook[] $orders */

$this->title = 'Мой профиль';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-index">
    <div class="row">
        <!-- Левая колонка - информация профиля -->
        <div class="col-lg-4">
            <div class="card profile-card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="profile-avatar mb-3">
    <?php if ($user->getAvatarUrl()): ?>
        <img src="<?= $user->getAvatarUrl() ?>" 
             class="avatar-img rounded-circle"
             alt="<?= Html::encode($user->fio ?? '') ?>">
    <?php else: ?>
        <div class="avatar-circle">
            <span class="avatar-initials"><?= mb_substr(Html::encode($user->fio ?? ''), 0, 2) ?></span>
        </div>
    <?php endif; ?>
</div>
                    
                    <h3 class="profile-name"><?= Html::encode($user->fio ?? '') ?></h3>
                    
                   
                    
                    <div class="profile-stats mb-4">
                        <div class="row">
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number"><?= (int) $user->getOrdersCount() ?></div>
                                    <div class="stat-label">Заказов</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number"><?= number_format((float) $user->getTotalSpent(), 0, '', ' ') ?> ₽</div>
                                    <div class="stat-label">Потрачено</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-actions">
                        <?= Html::a('✏️ Редактировать профиль', ['edit'], ['class' => 'btn btn-outline-primary w-100 mb-2']) ?>
                        
                        <?= Html::a('🚪 Выйти', ['/site/logout'], [
                            'class' => 'btn btn-outline-danger w-100',
                            'data' => ['method' => 'post']
                        ]) ?>
                    </div>
                </div>
            </div>

            <!-- Контактная информация -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">📱 Контакты</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled contact-list">
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <span class="contact-icon me-3">📧</span>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <strong><?= Html::encode($user->email ?? '') ?></strong>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <span class="contact-icon me-3">📞</span>
                                <div>
                                    <small class="text-muted d-block">Телефон</small>
                                    <strong><?= Html::encode($user->phone ?? '') ?></strong>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <span class="contact-icon me-3">📅</span>
                                <div>
                                    <small class="text-muted d-block">Дата регистрации</small>
                                    <strong><?= date('d.m.Y', strtotime($user->created_at ?? 'now')) ?></strong>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Статистика -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">📊 Статистика</h5>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-icon">🛒</div>
                            <div class="stat-text">Заказов за все время</div>
                            <div class="stat-number"><?= (int) $user->getOrdersCount() ?></div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon">💰</div>
                            <div class="stat-text">Общая сумма</div>
                            <div class="stat-number"><?= number_format((float) $user->getTotalSpent(), 0, '', ' ') ?> ₽</div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>

        <!-- Правая колонка - заказы и активность -->
        <div class="col-lg-8">
            <!-- Последние заказы -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">📦 Последние заказы</h5>
                    <?= Html::a('Все заказы →', ['orders'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                </div>
                <div class="card-body">
                    <?php if (!empty($orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>№ Заказа</th>
                                        <th>Дата</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                                        <tr>
                                            <td><strong>#<?= $order->id ?></strong></td>
                                            <td><?= date('d.m.Y H:i', strtotime($order->created_at)) ?></td>
                                            <td>
                                                <span class="text-success fw-bold">
                                                    <?= number_format((float)$order->getOrderItems()->sum('price * quantity'), 0, '', ' ') ?> ₽
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $statusColors = [
                                                    1 => 'warning', // В обработке
                                                    2 => 'info',    // Принят
                                                    3 => 'primary', // В доставке
                                                    4 => 'success', // Доставлен
                                                    5 => 'danger',  // Отменен
                                                ];
                                                $statusText = $order->status->name ?? 'В обработке';
                                                $color = $statusColors[$order->id_status] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $color ?>"><?= $statusText ?></span>
                                            </td>
                                            <td>
                                                <?= Html::a('👁️ Просмотр', ['order-view', 'id' => $order->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <div class="empty-state-icon mb-3">🛒</div>
                            <h5>Заказов пока нет</h5>
                            <p class="text-muted">Сделайте свой первый заказ!</p>
                            <?= Html::a('Перейти в каталог', ['/book/index'], ['class' => 'btn btn-primary']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Избранное -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">❤️ Избранное</h5>
        <?= Html::a('Все избранное →', ['/wishlist/index'], ['class' => 'btn btn-sm btn-outline-danger']) ?>
    </div>
    <div class="card-body">
        <?php 
        $wishlistItems = \app\models\Wishlist::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->with('book')
            ->limit(3)
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        ?>
        
        <?php if (!empty($wishlistItems)): ?>
            <div class="row">
                <?php foreach ($wishlistItems as $item): ?>
                    <?php $book = $item->book; ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center p-2">
                                <?= Html::img($book->getImageUrl(), [
                                    'style' => 'height: 100px; object-fit: contain;',
                                    'class' => 'mb-2',
                                    'alt' => $book->name
                                ]) ?>
                                <h6 class="card-title small mb-1">
                                    <?= Html::a(Html::encode($book->name), ['/book/view', 'id' => $book->id]) ?>
                                </h6>
                                <p class="text-success small mb-0"><?= number_format($book->price, 0, '', ' ') ?> ₽</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted text-center mb-0">У вас пока нет избранных книг</p>
        <?php endif; ?>
    </div>
</div>

            

            <!-- Активность -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">📈 Ваша активность</h5>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        <?php if (!empty($orders)): ?>
                            <?php foreach (array_slice($orders, 0, 3) as $order): ?>
                                <div class="activity-item">
                                    <div class="activity-icon"><span>🛒</span></div>
                                    <div class="activity-content">
                                        <div class="activity-title">
                                            Заказ #<?= $order->id ?> на сумму <?= number_format((float)$order->getOrderItems()->sum('price * quantity'), 0, '', ' ') ?> ₽
                                        </div>
                                        <div class="activity-time"><?= date('d.m.Y H:i', strtotime($order->created_at)) ?></div>
                                        <div class="activity-status">Статус: <span class="text-primary"><?= Html::encode($order->status->name ?? 'В обработке') ?></span></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <p class="text-muted">У вас пока нет активности</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="activity-item">
                            <div class="activity-icon"><span>👋</span></div>
                            <div class="activity-content">
                                <div class="activity-title">Регистрация в магазине</div>
                                <div class="activity-time"><?= date('d.m.Y', strtotime($user->created_at ?? 'now')) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$js = <<<JS
$('#save-password-btn').click(function() {
    var formData = $('#change-password-form').serialize();
    var btn = $(this);
    var originalText = btn.html();
    
    btn.html('<span class="spinner-border spinner-border-sm"></span>');
    btn.prop('disabled', true);
    
    $('#password-error').addClass('d-none');
    $('#password-success').addClass('d-none');
    
    setTimeout(function() {
        var newPass = $('input[name="new_password"]').val();
        var confirmPass = $('input[name="confirm_password"]').val();
        
        if (newPass !== confirmPass) {
            $('#password-error').text('Пароли не совпадают').removeClass('d-none');
        } else if (newPass.length < 6) {
            $('#password-error').text('Пароль должен быть не менее 6 символов').removeClass('d-none');
        } else {
            $('#password-success').text('Пароль успешно изменен!').removeClass('d-none');
            $('#change-password-form')[0].reset();
            setTimeout(function() { $('#changePasswordModal').modal('hide'); }, 2000);
        }
        
        btn.html(originalText);
        btn.prop('disabled', false);
    }, 1000);
});

$('#changePasswordModal').on('shown.bs.modal', function() {
    $('input[name="current_password"]').focus();
});

$('#changePasswordModal').on('hidden.bs.modal', function() {
    $('#change-password-form')[0].reset();
    $('#password-error').addClass('d-none');
    $('#password-success').addClass('d-none');
});
JS;

$this->registerJs($js);
?>
