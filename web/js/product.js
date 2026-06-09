// Функционал для страницы товара
$(document).ready(function() {
    initProductPage();
});

function initProductPage() {
    // Управление количеством
    $('.quantity-plus').off('click').on('click', function() {
        var input = $(this).closest('.input-group').find('.quantity-input');
        var max = parseInt(input.attr('max'));
        var value = parseInt(input.val());
        if (value < max) {
            input.val(value + 1);
        } else {
            showNotification('Максимальное количество: ' + max, 'warning');
        }
    });

    $('.quantity-minus').off('click').on('click', function() {
        var input = $(this).closest('.input-group').find('.quantity-input');
        var min = parseInt(input.attr('min'));
        var value = parseInt(input.val());
        if (value > min) {
            input.val(value - 1);
        }
    });

    // Валидация ввода количества
    $('.quantity-input').off('change').on('change', function() {
        var min = parseInt($(this).attr('min'));
        var max = parseInt($(this).attr('max'));
        var value = parseInt($(this).val());
        
        if (isNaN(value) || value < min) {
            $(this).val(min);
        } else if (value > max) {
            $(this).val(max);
            showNotification('Максимальное количество: ' + max, 'warning');
        }
    });

    // Галерея изображений
    initImageGallery();
}

// Галерея изображений товара
function initImageGallery() {
    $('.product-thumbnail').off('click').on('click', function() {
        var mainImage = $(this).closest('.product-gallery').find('.product-main-image');
        var thumbnail = $(this).find('img');
        
        mainImage.attr('src', thumbnail.attr('src'));
        mainImage.attr('alt', thumbnail.attr('alt'));
        
        $('.product-thumbnail').removeClass('active');
        $(this).addClass('active');
    });
}

// Подсветка миниатюр в карусели Bootstrap
$(document).off('slide.bs.carousel', '#productCarousel');
$(document).on('slide.bs.carousel', '#productCarousel', function(e) {
    var index = $(e.relatedTarget).index();
    $('.product-thumbnail').removeClass('active').css('border-color', '#ddd');
    $('.product-thumbnail').eq(index).addClass('active').css('border-color', '#a8c3d5');
});