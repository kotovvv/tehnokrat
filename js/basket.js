jQuery(document).ready(function($){

    $('.slider-bask').slick({
        dots: false,
        arrows:true,
        infinite: true,
        prevArrow: tehnokrat_script.slickPrevArrow,
        nextArrow: tehnokrat_script.slickNextArrow,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1020,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    arrows:true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    arrows:true
                }
            },
            {
                breakpoint: 500,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows:true
                }
            }
        ]
    });

    $('.slider-bask .slick-slide .name').matchHeight({
        byRow: false
    });

    $('.slider-bask .slick-slide .for-img').matchHeight({
        byRow: false
    });

    jcf.replaceAll();
});