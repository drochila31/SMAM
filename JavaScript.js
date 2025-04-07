$(document).ready(function(){
    $('.slick-slider').slick({
        infinite: true,    // Бесконечный прокрут
        slidesToShow: 4,   // Показываем 4 изображения одновременно
        slidesToScroll: 1, // Прокручиваем по одному изображению
        dots: true,        // Отображение точек для навигации
        arrows: true,      // Отображение стрелок для переключения
        responsive: [
            {
                breakpoint: 768, // Для мобильных устройств
                settings: {
                    slidesToShow: 1,  // Показываем одно изображение
                    slidesToScroll: 1,
                }
            }
        ]
    });
});
