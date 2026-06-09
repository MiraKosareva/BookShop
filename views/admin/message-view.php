<?php

use yii\helpers\Html;

/** @var app\models\ContactMessage $model */

$this->title = 'Сообщение #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['messages']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-message-view">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">📩 Сообщение от пользователя</h5>
                    <div>
                        <?php
                        $statusColors = [0 => 'warning', 1 => 'info', 2 => 'success'];
                        ?>
                        <span class="badge bg-<?= $statusColors[$model->status] ?> fs-6">
                            <?= $model->getStatusText() ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>👤 Имя:</strong> <?= Html::encode($model->name) ?></p>
                            <p><strong>📧 Email:</strong> <a href="mailto:<?= $model->email ?>"><?= Html::encode($model->email) ?></a></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>📅 Дата:</strong> <?= Yii::$app->formatter->asDatetime($model->created_at) ?></p>
                            <p><strong>📋 Тема:</strong> <?= Html::encode($model->subject) ?></p>
                        </div>
                    </div>
                    
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">💬 Сообщение:</h6>
                            <p><?= nl2br(Html::encode($model->body)) ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <?= Html::a('<i class="fas fa-reply me-1"></i>Ответить на почту', 'mailto:' . $model->email . '?subject=Re: ' . urlencode($model->subject), [
                            'class' => 'btn btn-primary'
                        ]) ?>
                        <?= Html::a('<i class="fas fa-arrow-left me-1"></i>Назад к списку', ['messages'], [
                            'class' => 'btn btn-secondary'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>