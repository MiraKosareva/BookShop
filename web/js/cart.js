// Функционал для страницы корзины
$(document).ready(function() {
    initCartPage();
});

function initCartPage() {
    // Увеличение количества
    $('.cart-quantity-plus').off('click').on('click', function() {
        var bookId = $(this).data('book-id');
        var input = $('.cart-quantity-input[data-book-id="' + bookId + '"]');
        var max = parseInt(input.attr('max'));
        var value = parseInt(input.val());
        
        if (value < max) {
            updateCartQuantity(bookId, value + 1);
        } else {
            showNotification('Недостаточно товара. Доступно: ' + max, 'error');
        }
    });

    // Уменьшение количества
    $('.cart-quantity-minus').off('click').on('click', function() {
        var bookId = $(this).data('book-id');
        var input = $('.cart-quantity-input[data-book-id="' + bookId + '"]');
        var min = parseInt(input.attr('min'));
        var value = parseInt(input.val());
        
        if (value > min) {
            updateCartQuantity(bookId, value - 1);
        }
    });

    // Ручное изменение
    $('.cart-quantity-input').off('change').on('change', function() {
        var bookId = $(this).data('book-id');
        var value = parseInt($(this).val());
        var max = parseInt($(this).attr('max'));
        var min = parseInt($(this).attr('min'));
        
        if (isNaN(value) || value < min) value = min;
        if (value > max) value = max;
        
        $(this).val(value);
        updateCartQuantity(bookId, value);
    });
}

function updateCartQuantity(bookId, quantity) {
    $.ajax({
        url: '/cart/update',
        type: 'POST',
        data: {
            id: bookId,
            quantity: quantity,
            _csrf: $('meta[name="csrf-token"]').attr('content') || yii.getCsrfToken()
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#cart-count').text(response.cartCount);
                $('.item-total[data-book-id="' + bookId + '"]').text(formatPrice(response.itemTotal) + ' ₽');
                $('#cart-subtotal').text(formatPrice(response.total) + ' ₽');
                $('#cart-total').text(formatPrice(response.total) + ' ₽');
                
                if (quantity === 0) {
                    $('.cart-item[data-book-id="' + bookId + '"]').fadeOut(300, function() {
                        $(this).remove();
                        checkEmptyCart();
                    });
                }
                
                showNotification(response.message, 'success');
            } else {
                showNotification(response.message, 'error');
            }
        },
        error: function(xhr) {
            console.error('Cart update error:', xhr);
            showNotification('Ошибка соединения', 'error');
        }
    });
}

function checkEmptyCart() {
    if ($('.cart-item').length === 0) {
        setTimeout(function() {
            location.reload();
        }, 500);
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('ru-RU').format(price);
}