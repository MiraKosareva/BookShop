<?php
// views/profile/order-view.php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Orderbook $order */

$this->title = 'Заказ #' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Мой профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Мои заказы', 'url' => ['orders']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📦 <?= Html::encode($this->title) ?></h1>
        <?= Html::a('← К списку заказов', ['orders'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Информация о заказе -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Информация о заказе</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Номер заказа</small>
                            <strong>#<?= (int)$order->id ?></strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Дата оформления</small>
                            <strong><?= date('d.m.Y H:i', strtotime($order->created_at)) ?></strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Статус</small>
                            <?php
                            $statusColors = [1 => 'warning', 2 => 'info', 3 => 'primary', 4 => 'success', 5 => 'danger'];
                            $statusText = $order->status->name ?? 'В обработке';
                            $color = $statusColors[$order->id_status] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $color ?>"><?= Html::encode($statusText) ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Сумма заказа</small>
                            <strong class="text-success h5">
                                <?= number_format((float)$order->getOrderItems()->sum('price * quantity'), 0, '', ' ') ?> ₽

                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Товары в заказе -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Товары в заказе</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($order->orderItems)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Товар</th>
                                        <th class="text-center">Количество</th>
                                        <th class="text-end">Цена</th>
                                        <th class="text-end">Сумма</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order->orderItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($item->book): ?>
                                                        <div class="me-3">
                                                            <?= Html::img($item->book->getImageUrl(), [
                                                                'style' => 'width:50px;height:70px;object-fit:cover;',
                                                                'class' => 'rounded',
                                                                'alt' => $item->book->name
                                                            ]) ?>
                                                        </div>
                                                        <div>
                                                            <strong><?= Html::encode($item->book->name) ?></strong><br>
                                                            <small class="text-muted"><?= Html::encode($item->book->author) ?></small>
                                                        </div>
                                                    <?php else: ?>
                                                        <em>Товар недоступен</em>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="text-center"><?= (int)$item->quantity ?> шт.</td>
                                            <td class="text-end"><?= number_format((float)$item->price, 0, '', ' ') ?> ₽</td>
                                            <td class="text-end"><?= number_format((float)$item->price * (int)$item->quantity, 0, '', ' ') ?> ₽</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                                        <td class="text-end">
                                            <strong class="text-success">
                                                <?= number_format((float)$order->getOrderItems()->sum('price * quantity'), 0, '', ' ') ?> ₽

                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Информация о товарах отсутствует</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Информация о доставке -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">🚚 Доставка</h5>
                </div>
                <div class="card-body">
                    <?php if ($order->delivery): ?>
                        <div class="mb-3">
                            <small class="text-muted d-block">Способ доставки</small>
                            <strong><?= Html::encode($order->delivery->name) ?></strong>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <small class="text-muted d-block">Адрес доставки</small>
                        <div><?= nl2br(Html::encode($order->adres)) ?></div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Получатель</small>
                        <strong><?= Html::encode($order->fio) ?></strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Контактный телефон</small>
                        <strong><?= Html::encode($order->contact) ?></strong>
                    </div>
                </div>
            </div>

            <!-- Информация об оплате -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">💳 Оплата</h5>
                </div>
                <div class="card-body">
                    <?php if ($order->pay): ?>
                        <div class="mb-3">
                            <small class="text-muted d-block">Способ оплаты</small>
                            <strong><?= Html::encode($order->pay->name) ?></strong>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <small class="text-muted d-block">Статус оплаты</small>
                        <?php if ($order->id_status >= 3): ?>
                            <span class="badge bg-success">Оплачено</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Оплачено</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Действия с заказом -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">⚡ Действия</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= Html::a('🖨️ Распечатать', '#', [
                            'class' => 'btn btn-outline-secondary',
                            'onclick' => 'window.print(); return false;'
                        ]) ?>

                        <!-- <?php if ($order->id_status == 1): ?>
                            <?= Html::beginForm('', 'post') ?>
                                <?= Html::hiddenInput('action', 'cancel') ?>
                                <?= Html::submitButton('❌ Отменить заказ', [
                                    'class' => 'btn btn-outline-danger',
                                    'data-confirm' => 'Вы уверены, что хотите отменить заказ?'
                                ]) ?>
                            <?= Html::endForm() ?>
                        <?php endif; ?> -->

                        <?= Html::a('📞 Связаться с поддержкой', ['/site/contact'], [
                            'class' => 'btn btn-outline-primary'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .card-header, .btn, .breadcrumb, .d-print-none { display: none !important; }
    .card { border: 1px solid #000 !important; box-shadow: none !important; }
}
</style>
