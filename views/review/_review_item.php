<?php
use yii\helpers\Html;

/** @var app\models\Review $model */

$userName = $model->user ? $model->user->fio : 'Аноним';
$bookName = $model->book ? $model->book->name : 'Без книги';

$stars = str_repeat('★', $model->rating) . str_repeat('☆', 5 - $model->rating);
?>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title"><?= Html::encode($bookName) ?></h5>

        <div class="text-warning mb-2" style="font-size: 1.3rem;">
            <?= $stars ?>
        </div>

        <p class="card-text"><?= nl2br(Html::encode($model->text)) ?></p>

        <div class="text-muted small">
            Автор: <?= Html::encode($userName) ?> — <?= date('d.m.Y H:i', strtotime($model->created_at)) ?>
        </div>
    </div>
</div>
