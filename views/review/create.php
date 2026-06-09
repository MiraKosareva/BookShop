<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Review $model */
/** @var app\models\Book $book */

$this->title = 'Оставить отзыв';
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['/book/index']];
if ($book) {
    $this->params['breadcrumbs'][] = ['label' => $book->name, 'url' => ['/book/view', 'id' => $book->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="review-create">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="card-body">
                    
                    <?php if ($book): ?>
                        <div class="book-info mb-4 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-2">
                                    <?= Html::img($book->getImageUrl(), [
                                        'class' => 'img-fluid rounded',
                                        'alt' => $book->name,
                                        'style' => 'max-height: 120px;'
                                    ]) ?>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="h5"><?= Html::encode($book->name) ?></h4>
                                    <p class="text-muted mb-1"><?= Html::encode($book->author) ?></p>
                                    <p class="mb-0">
                                        Ваш отзыв поможет другим читателям сделать правильный выбор.
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php $form = ActiveForm::begin([
                        'id' => 'review-form',
                        'enableClientValidation' => true,
                    ]); ?>
                    
                    <div class="rating-section mb-4 field-rating">
                        <label class="form-label fw-bold">Ваша оценка *</label>
                        <div class="rating-stars mb-2" id="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star" data-value="<?= $i ?>" style="font-size: 2rem; cursor: pointer; color: #ccc; margin-right: 5px;">
                                    ☆
                                </span>
                            <?php endfor; ?>
                        </div>
                        <div class="text-danger" id="rating-error" style="display: none;">
                            Пожалуйста, поставьте оценку
                        </div>
                        <?= $form->field($model, 'rating')->hiddenInput([
                            'id' => 'rating-input',
                            'value' => $model->rating ?: 0
                        ])->label(false) ?>
                        <div class="rating-labels">
                            <small class="text-muted">
                                1 - Ужасно, 2 - Плохо, 3 - Нормально, 4 - Хорошо, 5 - Отлично
                            </small>
                        </div>
                    </div>
                    
                    <?= $form->field($model, 'text')->textarea([
                        'id' => 'review-text', 
                        'rows' => 6,
                        'placeholder' => 'Напишите ваш отзыв о книге... Расскажите, что вам понравилось или не понравилось, стоит ли читать эту книгу другим.',
                        'class' => 'form-control'
                    ])->label('Текст отзыва *') ?>
                    
                    <?= $form->field($model, 'book_id')->hiddenInput()->label(false) ?>
                    
                    
                    <div class="form-group">
                        <?= Html::submitButton('Отправить отзыв', [
                            'class' => 'btn btn-primary btn-lg',
                            'id' => 'submit-btn'
                        ]) ?>
                        <?= Html::a('Отмена', $book ? ['book/view', 'id' => $book->id] : ['/book/index'], [
                            'class' => 'btn btn-outline-secondary btn-lg'
                        ]) ?>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                    
                    <div class="mt-4 p-3 bg-light rounded">
                        <h5 class="h6 mb-2">Правила написания отзывов:</h5>
                        <ul class="small mb-0">
                            <li>Будьте вежливы и уважительны к другим читателям</li>
                            <li>Избегайте ненормативной лексики</li>
                            <li>Опишите ваш личный опыт чтения</li>
                            <li>Избегайте спойлеров или используйте предупреждение</li>
                            <li>Отзывы проходят модерацию перед публикацией</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$(document).ready(function() {
    var ratingInput = $('#rating-input');
    var ratingError = $('#rating-error');
    var stars = $('#rating-stars .star');
    var currentRating = parseInt(ratingInput.val()) || 0;
    
    // Инициализируем звезды с текущим рейтингом
    updateStars(currentRating);
    
    // Клик по звезде
    stars.on('click', function() {
        var value = $(this).data('value');
        ratingInput.val(value);
        currentRating = value;
        updateStars(value);
        ratingError.hide();
        $(this).closest('.field-rating').removeClass('has-error');
    });
    
    // Наведение на звезды
    stars.hover(
        function() {
            var value = $(this).data('value');
            updateStars(value, true);
        },
        function() {
            updateStars(currentRating, false);
        }
    );
    
    // Обновление отображения звезд
    function updateStars(value, isHovering) {
        stars.each(function(index) {
            var starValue = $(this).data('value');
            if (starValue <= value) {
                $(this).html('★').css('color', '#ffc107');
            } else {
                $(this).html('☆').css('color', isHovering ? '#ffc107' : '#ccc');
            }
        });
    }
    
    // Валидация формы перед отправкой
    $('#review-form').on('beforeSubmit', function(e) {
        var rating = parseInt(ratingInput.val());
        var text = $('#review-text').val().trim();
        
        if (!rating || rating < 1 || rating > 5) {
            ratingError.show().text('Пожалуйста, поставьте оценку от 1 до 5 звезд');
            $('html, body').animate({
                scrollTop: $('#rating-stars').offset().top - 100
            }, 500);
            return false;
        }
        
        if (!text) {
            ratingError.hide();
            $('html, body').animate({
                scrollTop: $('#review-text').offset().top - 100
            }, 500);
            return true; // Позволяем Yii показать свою ошибку
        }
        
        return true;
    });
    
    // Показываем ошибку рейтинга при фокусе
    $('#rating-stars').on('focus', function() {
        if (!currentRating || currentRating < 1) {
            ratingError.show();
        }
    });
});
JS;

$this->registerJs($js);

// CSS для ошибок
$css = <<<CSS
.field-rating.has-error .rating-stars {
    border: 2px solid #dc3545;
    border-radius: 5px;
    padding: 5px;
}
.help-block {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 5px;
}
.star {
    transition: transform 0.2s ease, color 0.2s ease;
}
.star:hover {
    transform: scale(1.3);
}
CSS;

$this->registerCss($css);
?>
