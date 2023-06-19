window.addEventListener('beforeunload', (event) => {
    delete event['returnValue'];

    setTimeout(function () {
        preload = document.getElementById('preload');
        preload.style.display = 'flex';

        lottie.loadAnimation({
            container: preload,
            renderer: 'svg',
            loop: !0,
            autoplay: !0,
            path: document.location.origin + '/wp-content/themes/tehnokrat/js/loading.json'
        })
    }, 1000)
});

jQuery(document).ready(function($){
    var footer_height = $('.footer_js').height();
    $('.wrapper-js').css('padding-bottom', footer_height);

    $('.all-product .filter .filter-open').on('click', function () {
        $('.all-product .filter .filter-cont').addClass('active');
    });

    $('.all-product .filter .close').on('click', function () {
        $('.all-product .filter .filter-cont').removeClass('active');
    });

    var proditem_height = $(".product-item .product-cont").height();
    $('.product-item').css('min-height', proditem_height+40);

    $(".product-item").on({
        touchstart: function () {
            $(this).addClass('active');
        },
        touchend: function () {
            $(this).removeClass('active');
        }
    });

    $(".product-item").on({
        mouseenter: function () {
            $(this).addClass('active');
        },
        mouseleave: function () {
            $(this).removeClass('active');
        }
    });

    jcf.replace($('.filter-item .checkbox-item input[type=checkbox]'));
    jcf.replace($('.filter-item input[type=range]'));



    // $('.color').find('li').on('click', function () {
    //     $('.color').find('li').removeClass('active');
    //     $(this).addClass('active');
    // });

    // $('.memory').find('li').on('click', function () {
    //     $('.memory').find('li').removeClass('active');
    //     $(this).addClass('active');
    // });

    // $('.buy').on('click', function () {
    //     $('.wrapper').addClass('popup');
    //     $('.up_order').addClass('active');
    //     $('body').addClass('popup');
    // });

    $('.up_order .close').on('click', function () {
        $('.wrapper').removeClass('popup');
        $('.up_order').removeClass('active');
        $('body').removeClass('popup');
    });

   /* $('.buy_pred').on('click', function () {
        $('.wrapper').addClass('popup');
        $('.up_order_pred').addClass('active');
        $('body').addClass('popup');
    });*/

    $('.up_order_pred .close').on('click', function () {
        $('.wrapper').removeClass('popup');
        $('.up_order_pred').removeClass('active');
        $('body').removeClass('popup');
    });

    // $('.product-img img').on('click', function () {
    //     $('.wrapper').addClass('popup');
    //     $('.slider_popup').addClass('active');
    //     $('body').addClass('popup');
    // });

    $('.slider_popup .close').on('click', function () {
        $('.wrapper').removeClass('popup');
        $('.slider_popup').removeClass('active');
        $('body').removeClass('popup');
    });

    $('.thank_popup .close').on('click', function () {
        $('.wrapper').removeClass('popup');
        $('.thank_popup').removeClass('active');
        $('body').removeClass('popup');
    });


    var wind_height = $(window).height();
    var wind_width = $(window).width();

    $('.up_order').css('height', wind_height);
    $('.up_order').css('width', wind_width);



    /*$('.menu-item-has-children > a').on('click', function(){
        if(!$(this).hasClass('active')){
            console.log('if');
            $('.menu-item-has-children > a').removeClass('active');
            $('.menu-item-has-children').find('.sub-menu').animate({
                opacity: '0',
                zIndex: '-1'
            }, 100);
            $('.header-categories-menu > .menu-item-has-children').removeClass('active');
            $(this).addClass('active');
            $(this).next('.sub-menu').animate({
                opacity: '1',
                zIndex: '2'
            }, 100);
            $('.header-categories-menu').parent().animate({
                paddingBottom: '50'
            }, 500)
        }else {
            console.log('else');
            $('.menu-item-has-children > a').removeClass('active');
            $('.header-categories-menu .sub-menu > li a').removeClass('active');
            $(this).next('.sub-menu').animate({
                opacity: '0',
                zIndex: '-1'
            }, 100);
            $('.header-categories-menu').parent().animate({
                paddingBottom: '0'
            }, 500)
        }
    });*/

    $('.menu-item-has-children > a').on('click', function() {
        if (!$(this).hasClass('active')) {
            $('.menu-item-has-children > a').removeClass('active');
            $('.header-categories-menu > .menu-item-has-children').removeClass('active');
            $(this).addClass('active');
        } else {
            console.log('else');
            $('.menu-item-has-children > a').removeClass('active');
            $('.header-categories-menu .sub-menu > li a').removeClass('active');
            $(this).next('.sub-menu').animate({
                opacity: '0',
                zIndex: '-1'
            }, 100);
        }
    });

    $('.header-categories-menu .sub-menu > li a').on('click', function () {
        if(!$(this).hasClass('active')){
            $('.header-categories-menu .sub-menu > li a').removeClass('active');
            $(this).addClass('active');
        }else{
            $('.header-categories-menu .sub-menu > li a').removeClass('active');
        }
    });

    /*$('article').readmore({
        speed: 300,
        maxHeight: 100,
        moreLink: '<a href="#" class="readmore">Читать под робнее</a>',
        lessLink: '<a href="#" class="readmore">Скрыть под робнее</a>'
    });*/

	/*$('.text .text-content article').readmore({
        collapsedHeight: 200,
        moreLink: '<a class="readmore" href="javascript:void(0)">Читать подробнее</a>',
        lessLink: '<a class="readmore" href="javascript:void(0)">Скрыть подробнее</a>',
        speed: 300,
        afterToggle: function(trigger, element, expanded) {
            if(! expanded) {
                $('html, body').animate({scrollTop: element.offset().top}, {duration: 100});
            }
        }
    });*/

    $('.mobile-menu').on('click', function () {
        $('.menuMibi').addClass('active');
        $('body').addClass('popup');
    });

    $('.emp-pr').on('click', function () {
        $('.menuMibi').removeClass('active');
        $('body').removeClass('popup');
    });

    $('.number').on('click',function () {
        $('.number').mask('+38(000)00-00-000').val('+38')
    });
/*
    $('.number').mask('+38(000)00-00-000').val('+38')
*/

    $('.installments').on('click', function () {
        $('.product_popup').addClass('active');
        $('body').addClass('popup');

    });

    $('.product_popup .close').on('click', function () {
        $('.product_popup').removeClass('active');
        $('body').removeClass('popup');

    });

    $('.product_popup .back').on('click', function () {
        $('.product_popup').removeClass('active');
        $('body').removeClass('popup');

    });


    $('.buy_pred').on('click', function () {
        $('.product_pred_popup').addClass('active');
    });

    $('.product_pred_popup .close').on('click', function () {
        $('.product_pred_popup').removeClass('active');
        $('body').removeClass('popup');
    });
 
    $('.product_pred_popup .back').on('click', function () {
        $('.product_pred_popup').removeClass('active');
        $('body').removeClass('popup');
    });

    var header_height = $('header').height();
    if($(window).width() >= 768){
        $('.wrapper-js').css('padding-top', header_height);
    } else {
        $('.wrapper-js').css('padding-top', header_height+20);
    }
	
	var acc = document.getElementsByClassName("more-but");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight){
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    }
	
	$( ".accordion" ).accordion({
		active: false,
        collapsible: true,
        heightStyle: "content"
    });

    var pi = document.getElementsByClassName("product-item");
    var j;
    for (j = 0; j < pi.length; j++) {
        pi[j].addEventListener("mouseover", function() {
            this.classList.toggle("active");
            var pichild = pi.querySelector('.features');
            if (pichild.style.maxHeight){
                pichild.style.maxHeight = null;
            } else { 
                pichild.style.maxHeight = pichild.scrollHeight + "px";
            }
        });
    }




});
   
jQuery(window).resize(function(){

    var $ = jQuery;

    var footer_height = $('.footer_js').height();
    $('.wrapper-js').css('padding-bottom', footer_height);

    var wind_height = $(window).height();
    var wind_width = $(window).width();

    $('.up_order').css('height', wind_height);
    $('.up_order').css('width', wind_width);

    var proditem_height = $(".all-product .product-cont").height();
    $('.all-product .product-item').css('min-height', proditem_height+40);

});
