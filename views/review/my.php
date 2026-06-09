<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Мои отзывы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="review-my">
    <div class="container">
        <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>
        
        <?php Pjax::begin(); ?>
        
        <?php if ($dataProvider->getTotalCount() > 0): ?>
            <div class="row">
                <div class="col-lg-3">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Статистика</h5>
                            <p class="card-text">
                                Всего отзывов: <strong><?= $dataProvider->getTotalCount() ?></strong><br>
                                Одобрено: <strong><?= \app\models\Review::find()
                                    ->where(['user_id' => Yii::$app->user->id, 'status' => \app\models\Review::STATUS_APPROVED])
                                    ->count() ?></strong><br>
                                На модерации: <strong><?= \app\models\Review::find()
                                    ->where(['user_id' => Yii::$app->user->id, 'status' => \app\models\Review::STATUS_PENDING])
                                    ->count() ?></strong>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-9">
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_my_review_item',
                        'layout' => "{items}\n{pager}",
                        'options' => ['class' => 'review-list'],
                        'itemOptions' => ['class' => 'review-item'],
                        'emptyText' => 'У вас пока нет отзывов.',
                        'emptyTextOptions' => ['class' => 'text-center py-5'],
                        'pager' => [
                            'options' => ['class' => 'pagination justify-content-center'],
                            'linkOptions' => ['class' => 'page-link'],
                            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                        ],
                    ]) ?>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="display-1 mb-4">📝</div>
                <h2 class="h3 mb-3">У вас пока нет отзывов</h2>
                <p class="text-muted mb-4">Оставляйте отзывы на прочитанные книги и помогайте другим читателям!</p>
                <?= Html::a('Найти книги для отзыва', ['/book/index'], ['class' => 'btn btn-primary btn-lg']) ?>
            </div>
        <?php endif; ?>
        
        <?php Pjax::end(); ?>
    </div>
</div>