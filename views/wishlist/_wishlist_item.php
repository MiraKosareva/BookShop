<?php
// views/wishlist/_wishlist_item.php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wishlist;

/** @var app\models\Wishlist $model */
$book = $model->book;
?>

<div class="card h-100 shadow-sm position-relative">
    <a href="<?= Url::to(['/book/view', 'id' => $book->id]) ?>" class="stretched-link"></a>

    <!-- Кнопка удаления -->
    <button type="button"
        class="btn btn-sm btn-danger wishlist-remove-btn position-absolute top-0 end-0 mt-2 me-2 z-3 rounded-circle"
        data-book-id="<?= $book->id ?>"
        style="width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;"
        title="Удалить из избранного">
        <i class="fas fa-times"></i>
    </button>

    <div style="height: 200px; overflow: hidden; background: linear-gradient(135deg, #f9f9f9, #f0f0f0);">
        <?= Html::img($book->getImageUrl(), [
            'style' => 'width:100%; height:100%; object-fit:contain; padding:16px;',
            'alt' => $book->name
        ]) ?>
    </div>

    <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?= Html::encode($book->name) ?></h5>
        <p class="text-muted small mb-2"><?= Html::encode($book->author) ?></p>
        <span class="h5 text-success mb-3"><?= number_format($book->price, 0, '', ' ') ?> ₽</span>

        <div class="mt-auto" style="z-index: 5;" onclick="event.stopPropagation();">
            <?php if ($book->isInStock()): ?>
                <button type="button"
                    class="btn btn-success w-100"
                    data-book-id="<?= $book->id ?>"
                    onclick="var btn=this; btn.disabled=true; btn.innerHTML='<span class=\'spinner-border spinner-border-sm\'></span>'; fetch('/cart/add',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded','X-CSRF-Token':'<?= Yii::$app->request->csrfToken ?>'},body:'id=<?= $book->id ?>&quantity=1'}).then(r=>r.json()).then(d=>{if(d.success){$('#cart-count').text(d.cartCount);btn.innerHTML='✓ В корзине';btn.classList.remove('btn-success');btn.classList.add('btn-outline-success');}else{alert(d.message);btn.disabled=false;btn.innerHTML='🛒 В корзину';}}).catch(()=>{btn.disabled=false;btn.innerHTML='🛒 В корзину';}); return false;">
                    🛒 В корзину
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-outline-secondary w-100" disabled>Нет в наличии</button>
            <?php endif; ?>
        </div>
    </div>
</div>