<?php
// views/payment-test/index.php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Оплата заказа';
$this->params['breadcrumbs'][] = ['label' => 'Оформление заказа', 'url' => ['/cart/checkout']];
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
.payment-container {
    max-width: 500px;
    margin: 40px auto;
    padding: 20px;
}
.test-warning {
    background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
    color: white;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 25px;
}
.order-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    border-left: 4px solid #28a745;
}
.card-details {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.form-group {
    margin-bottom: 20px;
}
.form-control {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
}
.form-control:focus {
    border-color: #667eea;
    outline: none;
}
.submit-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 16px;
    width: 100%;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 20px;
    transition: transform 0.3s;
}
.submit-btn:hover {
    transform: translateY(-2px);
}
.test-info {
    background: #e7f3ff;
    padding: 15px;
    border-radius: 8px;
    margin-top: 25px;
    font-size: 14px;
}
CSS;

$this->registerCss($css);
?>

<div class="payment-container">
    <div class="test-warning">
        ⚠️ ТЕСТОВЫЙ РЕЖИМ ОПЛАТЫ ⚠️
        <div style="font-size: 14px; margin-top: 5px; opacity: 0.9;">Это имитация - деньги не списываются!</div>
    </div>
    
    <!-- <div class="order-info">
        <h4 class="mb-3">✅ Заказ успешно оформлен!</h4>
        <p><strong>Номер заказа:</strong> #<?= $orderId ?></p>
        <p><strong>Сумма к оплате:</strong> <span class="h4 text-success"><?= number_format($amount, 0, '', ' ') ?> ₽</span></p>
        <p class="mb-0"><strong>Статус:</strong> Ожидает оплаты</p>
    </div> -->
    <div class="order-info">
    <h4 class="mb-3">✅ Заказ успешно оформлен!</h4>
    <p><strong>Номер заказа:</strong> #<?= $orderId ?></p>
    <p><strong>Сумма к оплате:</strong> <span class="h4 text-success"><?= number_format($amount, 0, '', ' ') ?> ₽</span></p>
    <p><strong>Способ оплаты:</strong> 💳 <?= $payName ?? 'Банковской картой' ?></p>
    <p><strong>Доставка:</strong> 🚚 <?= $deliveryName ?? 'Не выбрана' ?></p>
    <p class="mb-0"><strong>Статус:</strong> Ожидает оплаты</p>
</div>
    
    <div class="card-details">
        <h4 class="mb-4 text-center">💳 Введите данные банковской карты</h4>
        
        <form id="payment-form">
            <div class="form-group">
                <label class="form-label">Номер карты</label>
                <input type="text" 
                       id="card-number" 
                       class="form-control" 
                       placeholder="0000 0000 0000 0000"
                       
                       required>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Срок действия (ММ/ГГ)</label>
                        <input type="text" 
                               class="form-control" 
                               placeholder="12/25"
                              
                               required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">CVC/CVV</label>
                        <input type="text" 
                               class="form-control" 
                               placeholder="123"
                               
                               required
                               maxlength="3">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Имя владельца карты</label>
                <input type="text" 
                       class="form-control" 
                       placeholder="IVAN IVANOV"
                      
                       required>
            </div>
            
            <!-- <div class="form-group">
                <label class="form-label">Email для чека (опционально)</label>
                <input type="email" 
                       class="form-control" 
                       placeholder="email@example.com"
                       >
            </div> -->
            
            <!-- <div class="test-info">
                <h6>📝 Тестовые данные для имитации:</h6>
                <ul class="mb-0">
                    <li><strong>Номер карты:</strong> 4242 4242 4242 4242</li>
                    <li><strong>Срок:</strong> любая будущая дата (например 12/30)</li>
                    <li><strong>CVC:</strong> любые 3 цифры (например 123)</li>
                    <li><strong>Имя:</strong> любое имя</li>
                </ul>
            </div> -->
            
            <button type="submit" class="submit-btn">
                💳 Оплатить <?= number_format($amount, 0, '', ' ') ?> ₽
            </button>
            
            <div class="text-center mt-3">
                <small class="text-muted">Нажимая кнопку, вы подтверждаете имитацию оплаты</small>
            </div>
        </form>
        
        <div class="text-center mt-4">
            <?= Html::a('← Вернуться к оформлению заказа', ['/cart/checkout'], ['class' => 'text-muted']) ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$(document).ready(function() {
    // Форматирование номера карты
    $('#card-number').on('input', function(e) {
        let value = $(this).val().replace(/\\s/g, '').replace(/[^0-9]/g, '');
        if (value.length > 0) {
            value = value.match(/.{1,4}/g).join(' ');
        }
        $(this).val(value);
    });
    
    // Форматирование срока карты
    $('input[placeholder="12/25"]').on('input', function(e) {
        let value = $(this).val().replace(/[^0-9]/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        $(this).val(value);
    });
    
    // Обработка формы
    $('#payment-form').on('submit', function(e) {
        e.preventDefault();
        
        var \$btn = $(this).find('button[type="submit"]');
        var originalText = \$btn.html();
        
        // Показываем загрузку
        \$btn.html('<span class="spinner-border spinner-border-sm"></span> Имитация обработки платежа...');
        \$btn.prop('disabled', true);
        
        // Имитация обработки платежа (3 секунды)
        setTimeout(function() {
            // Отправляем AJAX запрос
            $.ajax({
                url: '/payment-test/process',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect;
                    } else {
                        alert('Ошибка: ' + response.message);
                        \$btn.html(originalText);
                        \$btn.prop('disabled', false);
                    }
                },
                error: function() {
                    alert('Ошибка соединения с сервером');
                    \$btn.html(originalText);
                    \$btn.prop('disabled', false);
                }
            });
        }.bind(this), 1000);
        
        return false;
    });
});
JS;

$this->registerJs($js);
?>