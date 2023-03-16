/**
 * Number.prototype.format(n, x, s, c)
 *
 * @param n: integer length of decimal
 * @param x: integer length of whole part
 * @param s: mixed sections delimiter
 * @param c: mixed decimal delimiter
 */
Number.prototype.format = function (n, x, s, c) {
	var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
		num = this.toFixed(Math.max(0, ~~n));

	return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};

;(function ($, window, document, undefined) {
	jQuery(document).ready(function ($) {

		$('.bask').on('click', function () {
			$('.for-hov').toggleClass('active');
			$('.backdrop').toggleClass('active');
		});

		$('.backdrop').on('click', function () {
			$('.for-hov').removeClass('active');
			$('.backdrop').removeClass('active');
		});

		$('.slider').slick({
			dots: true,
			prevArrow: tehnokrat_script.slickPrevArrow,
			nextArrow: tehnokrat_script.slickNextArrow
		});

		$(document.body).on('updated_wc_div updated_checkout', function () {
			jcf.replaceAll();
		});

		$('.exit').on('click', function () {
			$('.menuMibi').removeClass('active');
			$('body').removeClass('popup');
		});

		$('.home-slider').slick({
			dots: true,
			arrows: false,
			infinite: true,
			autoplay: true,
			autoplaySpeed: 2000
		});

		$('.dies p').matchHeight({
			byRow: false
		});

		$(".slick-dots").append("<li class='afters'></li>");


		// $(".product_popup .add").on("click", function() {
		// $("#vue-app > section.product_popup.active > div.product_popup_content > div > div.buttons > div.bb > a.add").on("click", function() {
		// 	$(".qu").addClass("active");
		// 	$(".for-ba").removeClass("emp");
		// 	$(window).width() >= 768 && $(".for-hov").toggleClass("active");
		// });


		// $(".bask").on("click", function() {
		// 	$(".for-hov").toggleClass("active");
		// });

		jQuery(document.body).trigger('wc_fragment_refresh');
	});
})(jQuery, window, document);
