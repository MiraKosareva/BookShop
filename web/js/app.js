// Основные функции для интернет-магазина
$(document).ready(function() {
    updateCartCounter();
    initCartHandlers();
    initQuantityHandlers();
    initNotifications();
});

// Обновление счетчика корзины в шапке
function updateCartCounter() {
    $.ajax({
        url: '/cart/mini-cart',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#cart-count').text(response.count);
            }
        }
    });
}

// Инициализация обработчиков корзины
function initCartHandlers() {
    // Удаляем старые обработчики перед добавлением новых
    $(document).off('click', '.add-to-cart-btn');
    $(document).off('click', '.add-to-cart-single');
    
    // Добавление в корзину из каталога/избранного
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var bookId = $(this).data('book-id');
        var quantity = $(this).data('quantity') || 1;
        addToCart(bookId, quantity, $(this));
    });

    // Добавление в корзину со страницы товара
    $(document).on('click', '.add-to-cart-single', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var bookId = $(this).data('book-id');
        var quantity = $('.quantity-input').val() || 1;
        addToCart(bookId, quantity, $(this));
    });
}

// Инициализация обработчиков количества
function initQuantityHandlers() {
    $(document).off('click', '.quantity-plus');
    $(document).off('click', '.quantity-minus');
    $(document).off('change', '.quantity-input');
    
    $(document).on('click', '.quantity-plus', function() {
        var input = $(this).closest('.input-group').find('.quantity-input');
        var max = parseInt(input.attr('max'));
        var value = parseInt(input.val());
        if (value < max) input.val(value + 1);
    });

    $(document).on('click', '.quantity-minus', function() {
        var input = $(this).closest('.input-group').find('.quantity-input');
        var min = parseInt(input.attr('min'));
        var value = parseInt(input.val());
        if (value > min) input.val(value - 1);
    });

    $(document).on('change', '.quantity-input', function() {
        var min = parseInt($(this).attr('min'));
        var max = parseInt($(this).attr('max'));
        var value = parseInt($(this).val());
        if (isNaN(value) || value < min) $(this).val(min);
        if (value > max) $(this).val(max);
    });
}

// Функция добавления в корзину
function addToCart(bookId, quantity, button) {
    var originalHtml = button.html();
    button.html('<span class="spinner-border spinner-border-sm"></span>');
    button.prop('disabled', true);

    $.ajax({
        url: '/cart/add',
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
                showNotification(response.message, 'success');
                button.html('✓ В корзине');
                button.removeClass('btn-success').addClass('btn-outline-success');
            } else {
                showNotification(response.message, 'error');
                button.html(originalHtml);
            }
        },
        error: function(xhr) {
            console.error('Cart error:', xhr);
            showNotification('Ошибка соединения', 'error');
            button.html(originalHtml);
        },
        complete: function() {
            button.prop('disabled', false);
        }
    });
}

// Система уведомлений
function initNotifications() {
    setTimeout(function() {
        $('.alert:not(.alert-permanent)').fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);
}

function showNotification(message, type) {
    var alertClass = type === 'success' ? 'alert-success' :
                    type === 'error' ? 'alert-danger' :
                    type === 'warning' ? 'alert-warning' : 'alert-info';

    var icon = type === 'success' ? '✅' :
               type === 'error' ? '❌' :
               type === 'warning' ? '⚠️' : 'ℹ️';

    var notification = $('<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed notification-toast">' +
        '<div class="d-flex align-items-center">' +
            '<span class="me-2">' + icon + '</span>' +
            '<span>' + message + '</span>' +
        '</div>' +
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
        '</div>');

    notification.css({
        'top': '20px',
        'right': '20px',
        'z-index': '99999',
        'min-width': '300px',
        'box-shadow': '0 4px 12px rgba(0,0,0,0.15)'
    });

    $('body').append(notification);

    setTimeout(function() {
        notification.fadeOut(300, function() { $(this).remove(); });
    }, 4000);
}


// ========== ИЗБРАННОЕ ==========

// Переключение избранного (сердечко)
$(document).off('click', '.wishlist-toggle-btn');
$(document).on('click', '.wishlist-toggle-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var button = $(this);
    var bookId = button.data('book-id');
    var icon = button.find('i');

    button.prop('disabled', true);

    var csrfToken = $('meta[name="csrf-token"]').attr('content') || (typeof yii !== 'undefined' ? yii.getCsrfToken() : '');

    console.log('Toggle wishlist:', bookId);

    $.ajax({
        url: '/wishlist/toggle',
        type: 'POST',
        data: { bookId: bookId, _csrf: csrfToken },
        dataType: 'json',
        success: function(response) {
            console.log('Wishlist response:', response);
            if (response.success) {
                // Обновляем счетчик в навбаре
                if (response.wishlistCount !== undefined) {
                    $('.wishlist-count').text(response.wishlistCount);
                    // Альтернативный селектор на всякий случай
                    $('.fa-heart').closest('a').find('.badge').text(response.wishlistCount);
                }
                
                if (response.status === 'added') {
                    icon.removeClass('far').addClass('fas');
                    button.removeClass('btn-outline-danger').addClass('btn-danger');
                    showNotification(response.message, 'success');
                } else {
                    icon.removeClass('fas').addClass('far');
                    button.removeClass('btn-danger').addClass('btn-outline-danger');
                    showNotification(response.message || 'Удалено из избранного', 'warning');
                }
            } else {
                showNotification(response.message, 'error');
            }
        },
        error: function(xhr) {
            console.error('Wishlist error:', xhr);
            showNotification('Ошибка при обновлении избранного', 'error');
            // При ошибке на странице избранного - перезагружаем
            if (window.location.pathname.includes('/wishlist')) {
                setTimeout(function() { location.reload(); }, 1500);
            }
        },
        complete: function() {
            button.prop('disabled', false);
        }
    });
});

// Удаление из избранного (для страницы избранного)
$(document).off('click', '.wishlist-remove-btn').on('click', '.wishlist-remove-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var button = $(this);
    var bookId = button.data('book-id');
    var card = button.closest('.card');
    var csrfToken = $('meta[name="csrf-token"]').attr('content') || (typeof yii !== 'undefined' ? yii.getCsrfToken() : '');
    
    $.ajax({
        url: '/wishlist/toggle',
        type: 'POST',
        data: { bookId: bookId, _csrf: csrfToken },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Обновляем счетчик
                if (response.wishlistCount !== undefined) {
                    $('.wishlist-count').text(response.wishlistCount);
                    $('#wishlist-count').text(response.wishlistCount);
                }
                
                card.fadeOut(300, function() {
                    $(this).remove();
                    // Если не осталось карточек — показываем сообщение вместо перезагрузки
                    if ($('.wishlist-index .card, .wishlist-page .card').length === 0) {
                        $('.wishlist-index .row, .wishlist-page .row').html(
                            '<div class="col-12 text-center py-5">' +
                            '<i class="far fa-heart fa-3x mb-3 text-muted"></i>' +
                            '<h4>Избранное пусто</h4>' +
                            '<a href="/catalog" class="btn btn-primary mt-3">Перейти в каталог</a>' +
                            '</div>'
                        );
                    }
                });
                showNotification('Удалено из избранного', 'warning');
            } else {
                showNotification(response.message, 'error');
            }
        },
        error: function(xhr) {
            console.error('Remove error:', xhr);
            showNotification('Ошибка при удалении', 'error');
            // При ошибке перезагружаем страницу
            setTimeout(function() { location.reload(); }, 1500);
        }
    });
});
