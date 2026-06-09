// Функционал для страницы избранного
$(document).ready(function() {
    console.log('🚀 wishlist.js ЗАГРУЖЕН и готов к работе');
    console.log('📍 Текущий URL:', window.location.href);
    
    // ДОБАВЛЯЕМ ПРОВЕРКУ: какой счетчик корзины сейчас
    var cartCount = $('#cart-count').text();
    console.log('📊 Текущий счетчик корзины:', cartCount);

    // ============================================
    // ДОБАВЛЕНИЕ В КОРЗИНУ С ПРИНУДИТЕЛЬНЫМ ОБНОВЛЕНИЕМ
    // ============================================
    $(document).on('click', '.wishlist-add-to-cart', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('🛒 КЛИК по кнопке "В корзину" в ИЗБРАННОМ');
        
        var button = $(this);
        var bookId = button.data('book-id');
        var originalText = button.html();
        
        console.log('📚 ID книги:', bookId);
        
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (!csrfToken && typeof yii !== 'undefined') {
            csrfToken = yii.getCsrfToken();
        }
        console.log('🔑 CSRF токен:', csrfToken ? 'Есть' : 'НЕТ!');
        
        button.html('<span class="spinner-border spinner-border-sm"></span>');
        button.prop('disabled', true);
        
        // ПЕРВЫЙ ЗАПРОС: добавляем в корзину
        $.ajax({
            url: '/cart/add',
            type: 'POST',
            data: {
                id: bookId,
                quantity: 1,
                _csrf: csrfToken
            },
            dataType: 'json',
            success: function(response) {
                console.log('✅ ОТВЕТ СЕРВЕРА (/cart/add):', response);
                
                if (response.success) {
                    // Пытаемся обновить счетчик
                    var newCount = response.cartCount;
                    console.log('🔄 Новое количество в корзине (из ответа):', newCount);
                    
                    if (newCount !== undefined) {
                        $('#cart-count').text(newCount);
                        console.log('✅ Счетчик обновлен ДО:', cartCount, '-> ПОСЛЕ:', newCount);
                    } else {
                        console.log('⚠️ В ответе нет cartCount! Ответ сервера:', response);
                    }
                    
                    // ВТОРОЙ ЗАПРОС: принудительно получаем актуальное состояние корзины
                    console.log('🔄 Делаем дополнительный запрос для синхронизации счетчика...');
                    $.ajax({
                        url: '/cart/mini-cart',
                        type: 'GET',
                        success: function(miniResponse) {
                            console.log('📦 Дополнительный ответ /cart/mini-cart:', miniResponse);
                            if (miniResponse.success) {
                                $('#cart-count').text(miniResponse.count);
                                console.log('✅ Счетчик синхронизирован:', miniResponse.count);
                            } else {
                                console.log('❌ Не удалось синхронизировать счетчик');
                            }
                        },
                        error: function(err) {
                            console.log('❌ Ошибка при синхронизации:', err.status);
                        }
                    });
                    
                    // Меняем кнопку
                    button.html('✓ В корзине');
                    button.removeClass('btn-success').addClass('btn-outline-success');
                    
                    // Показываем уведомление
                    if (typeof showNotification === 'function') {
                        showNotification(response.message || 'Товар добавлен в корзину', 'success');
                    } else {
                        alert('✅ ' + (response.message || 'Товар добавлен в корзину'));
                    }
                    
                    button.prop('disabled', false);
                } else {
                    console.log('❌ Ошибка от сервера:', response.message);
                    button.html(originalText);
                    button.prop('disabled', false);
                    if (typeof showNotification === 'function') {
                        showNotification(response.message || 'Ошибка добавления', 'error');
                    } else {
                        alert('❌ ' + (response.message || 'Ошибка добавления'));
                    }
                }
            },
            error: function(xhr) {
                console.log('❌ AJAX ОШИБКА!');
                console.log('Статус:', xhr.status);
                console.log('Текст ошибки:', xhr.statusText);
                console.log('Ответ сервера:', xhr.responseText);
                
                button.html(originalText);
                button.prop('disabled', false);
                
                var errorMsg = 'Ошибка соединения: ' + xhr.status;
                if (typeof showNotification === 'function') {
                    showNotification(errorMsg, 'error');
                } else {
                    alert(errorMsg);
                }
            }
        });
    });

    // ============================================
    // УДАЛЕНИЕ ИЗ ИЗБРАННОГО С ПРИНУДИТЕЛЬНЫМ ОБНОВЛЕНИЕМ
    // ============================================
    $(document).on('click', '.wishlist-remove-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('🗑️ КЛИК по кнопке "Удалить из избранного"');
        
        var button = $(this);
        var bookId = button.data('book-id');
        var cardItem = button.closest('.card');
        var cardContainer = cardItem.parent();
        
        console.log('📚 ID книги для удаления:', bookId);
        
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (!csrfToken && typeof yii !== 'undefined') {
            csrfToken = yii.getCsrfToken();
        }
        
        $.ajax({
            url: '/wishlist/toggle',
            type: 'POST',
            data: {
                bookId: bookId,
                _csrf: csrfToken
            },
            dataType: 'json',
            success: function(response) {
                console.log('✅ ОТВЕТ СЕРВЕРА (/wishlist/toggle):', response);
                
                if (response.success) {
                    // Удаляем карточку
                    cardContainer.fadeOut(300, function() {
                        $(this).remove();
                        console.log('🗑️ Карточка удалена со страницы');
                        
                        // Проверяем, остались ли товары
                        setTimeout(function() {
                            var remainingCards = $('.wishlist-index .card, .wishlist-page .card').length;
                            console.log('📊 Осталось карточек:', remainingCards);
                            
                            if (remainingCards === 0) {
                                console.log('📭 Избранное пусто, перезагружаем страницу');
                                location.reload();
                            }
                        }, 500);
                    });
                    
                    // Обновляем счетчик избранного в шапке
                    if (response.wishlistCount !== undefined) {
                        console.log('🔄 Обновляем счетчик избранного:', response.wishlistCount);
                        $('.wishlist-count').text(response.wishlistCount);
                        $('#wishlist-count').text(response.wishlistCount);
                        $('.fa-heart').closest('a').find('.badge, .count').text(response.wishlistCount);
                    } else {
                        console.log('⚠️ В ответе нет wishlistCount! Делаем отдельный запрос...');
                        $.ajax({
                            url: '/wishlist/count',
                            type: 'GET',
                            success: function(countResponse) {
                                console.log('📊 Ответ /wishlist/count:', countResponse);
                                if (countResponse.success) {
                                    $('.wishlist-count').text(countResponse.count);
                                }
                            }
                        });
                    }
                    
                    if (typeof showNotification === 'function') {
                        showNotification('Удалено из избранного', 'warning');
                    }
                } else {
                    console.log('❌ Сервер вернул ошибку:', response.message);
                    if (typeof showNotification === 'function') {
                        showNotification(response.message || 'Ошибка удаления', 'error');
                    }
                }
            },
            error: function(xhr) {
                console.log('❌ ОШИБКА ПРИ УДАЛЕНИИ!');
                console.log('Статус:', xhr.status);
                console.log('Ответ:', xhr.responseText);
                
                if (typeof showNotification === 'function') {
                    showNotification('Ошибка при удалении, обновите страницу', 'error');
                }
            }
        });
    });
    
    // Дополнительно: логируем все AJAX запросы для отладки
    $(document).ajaxError(function(event, xhr, settings, error) {
        console.log('🌍 ГЛОБАЛЬНАЯ AJAX ОШИБКА:');
        console.log('URL:', settings.url);
        console.log('Ошибка:', error);
        console.log('Статус:', xhr.status);
    });
    
    $(document).ajaxSuccess(function(event, xhr, settings, data) {
        console.log('🌍 ГЛОБАЛЬНЫЙ AJAX УСПЕХ:', settings.url, data);
    });
});