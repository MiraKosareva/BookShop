<?php
// views/profile/orders.php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Orderbook[] $orders */

$this->title = 'Мои заказы';
$this->params['breadcrumbs'][] = ['label' => 'Мой профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-orders">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📦 <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('🛒 Новый заказ', ['/book/index'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php if (!empty($orders)): ?>
        <div class="row">
            <?php foreach ($orders as $order): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card order-card h-100 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Заказ #<?= $order->id ?></strong>
                                <small class="text-muted ms-2">
                                    <?= date('d.m.Y H:i', strtotime($order->created_at)) ?>
                                </small>
                            </div>

                            <?php
                            $statusColors = [
                                1 => 'warning',
                                2 => 'info',
                                3 => 'primary',
                                4 => 'success',
                                5 => 'danger',
                            ];
                            $statusText = $order->status->name ?? 'В обработке';
                            $color = $statusColors[$order->id_status] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $color ?>"><?= $statusText ?></span>
                        </div>

                        <div class="card-body">
                            <div class="order-summary mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Сумма заказа:</span>
                                    <strong class="text-success">
                                        <?= number_format((float)$order->getOrderItems()->sum('price * quantity'), 0, '', ' ') ?> ₽
                                    </strong>
                                </div>

                                <?php if ($order->delivery): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Доставка:</span>
                                    <span><?= Html::encode($order->delivery->name) ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if ($order->pay): ?>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Оплата:</span>
                                    <span><?= Html::encode($order->pay->name) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="order-address mb-3">
                                <small class="text-muted d-block mb-1">Адрес доставки:</small>
                                <div><?= nl2br(Html::encode($order->adres)) ?></div>
                            </div>

                            <div class="order-items">
                                <small class="text-muted d-block mb-1">Товары в заказе:</small>
                                <?php if (!empty($order->orderItems)): ?>
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach ($order->orderItems as $item): ?>
                                            <?php
                                            $q = (int)($item->quantity ?? 0);
                                            $p = (float)($item->price ?? 0);
                                            $sum = $p * $q;
                                            ?>
                                            <li class="mb-1">
                                                <small>
                                                    <?= Html::encode($item->book->name ?? 'Товар') ?>
                                                    × <?= $q ?> шт.
                                                    - <?= number_format($sum, 0, '', ' ') ?> ₽
                                                </small>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <small class="text-muted">Информация о товарах отсутствует</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between">
                                <?= Html::a('👁️ Подробнее', ['order-view', 'id' => $order->id], [
                                    'class' => 'btn btn-sm btn-outline-primary'
                                ]) ?>

                                <!-- <?php if ($order->id_status == 1): ?>
                                    <?= Html::a('❌ Отменить', ['#'], [
                                        'class' => 'btn btn-sm btn-outline-danger',
                                        'data' => [
                                            'confirm' => 'Вы уверены, что хотите отменить заказ?',
                                            'method' => 'post',
                                        ]
                                    ]) ?>
                                <?php endif; ?> -->
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <div class="empty-orders-icon mb-3" style="font-size: 4rem;">🛒</div>
                <h4>У вас пока нет заказов</h4>
                <p class="text-muted mb-4">Сделайте свой первый заказ в нашем магазине!</p>
                <?= Html::a('Перейти в каталог', ['/book/index'], ['class' => 'btn btn-primary btn-lg']) ?>
            </div>
        </div>
    <?php endif; ?>
</div>
