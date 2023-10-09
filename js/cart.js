jQuery(document).ready(function($){
	$(document).on(
		'change keyup',
		'input.qty',
		_.debounce(
			() => {
				// Provide the submit button value because wc-form-handler expects it.
				$( '<input />' ).attr( 'type', 'hidden' )
					.attr( 'name', 'update_cart' )
					.attr( 'value', 'Update Cart' )
					.appendTo( $( '.woocommerce-cart-form' ) );

				$( document ).trigger('wc_update_cart');
			},
			1000
		)
	)

	let slick = $('.slider-bask').slick({
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

	//window.addEventListener('scroll', maybePushImpressions);

	slick.on('afterChange', maybePushImpressions);

	function maybePushImpressions() {
		if (!isSliderVisible()) {
			return;
		}

		let slidesToShow = slick[0].slick.slickGetOption('slidesToShow');
		$('.slick-active', slick[0]).each(function () {
			let index = $(this).data('slick-index') % (slidesToShow + 1);

			if ('pushed' in upsells[index]) {
				// данные о показе уже переданы
			} else {
				upsells[index].pushed = true;

				window.dataLayer.push({
					"ecommerce": {
						"currencyCode": "UAH",
						"impressions": [{
							"id": upsells[index]['id'],
							"name": upsells[index]['title'],
							"price": upsells[index]['price_uah'],
							"list": 'upsells',
							"position": index + 1,
						}]
					}
				});
			}
		});
	}

	function isSliderVisible() {
		const rect = slick[0].getBoundingClientRect();

		// слайдер считается видимым если он появился на 1/2 высоты
		return (
			rect.top >= 0 &&
			rect.left >= 0 &&
			rect.bottom <= (rect.bottom - rect.top) / 2 + (window.innerHeight || document.documentElement.clientHeight) &&
			rect.right <= (window.innerWidth || document.documentElement.clientWidth)
		);
	}

	maybePushImpressions();

	var is_blocked = function( $node ) {
		return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
	};

	var block = function( $node ) {
		if ( ! is_blocked( $node ) ) {
			$node.addClass( 'processing' ).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			} );
		}
	};

	var unblock = function( $node ) {
		$node.removeClass( 'processing' ).unblock();
	};

	var update_cart_totals_div = function( html_str ) {
		$( '.cart_totals' ).replaceWith( html_str );
		$( document.body ).trigger( 'updated_cart_totals' );
	};

	var update_wc_div = function() {
		var $form = $( '.woocommerce-cart-form' );

		block( $(window) );

		// Make call to actual form post URL.
		$.ajax( {
			type:     'get',
			url:      $form.attr( 'action' ),
			dataType: 'html',
			success:  function( html_str ) {
				var $html       = $.parseHTML( html_str );
				var $new_form   = $( '.woocommerce-cart-form', $html );
				var $new_totals = $( '.cart_totals', $html );
				var $notices    = $( '.woocommerce-error, .woocommerce-message, .woocommerce-info', $html );

				// No form, cannot do this.
				if ( $( '.woocommerce-cart-form' ).length === 0 ) {
					return;
				}

				if ( $new_form.length === 0 ) {
					// If the checkout is also displayed on this page, trigger reload instead.
					if ( $( '.woocommerce-checkout' ).length ) {
						return;
					}

					// No items to display now! Replace all cart content.
					var $cart_html = $( '.cart-empty', $html ).closest( '.woocommerce' );
					$( '.woocommerce-cart-form__contents' ).closest( '.woocommerce' ).replaceWith( $cart_html );

					// Display errors
					if ( $notices.length > 0 ) {
						show_notice( $notices, $( '.cart-empty' ).closest( '.woocommerce' ) );
					}
				} else {
					// If the checkout is also displayed on this page, trigger update event.
					if ( $( '.woocommerce-checkout' ).length ) {
						$( document.body ).trigger( 'update_checkout' );
					}

					$( '.woocommerce-cart-form' ).replaceWith( $new_form );
					$( '.woocommerce-cart-form' ).find( ':input[name="update_cart"]' ).prop( 'disabled', true );

					if ( $notices.length > 0 ) {
						show_notice( $notices );
					}

					update_cart_totals_div( $new_totals );
				}

				$( document.body ).trigger( 'updated_wc_div' );
			},
			complete: function() {
				unblock( $(window) );
			}
		} );
	};

	$('div.still-buying a.bb-byu').on('click', function(e) {
		e.preventDefault();

		var data = {
			'product_id': $(this).data('product_id')
		};

		$.ajax( {
            context:  this,
            type:     'post',
			url:      wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' ),
			data:     data,
			dataType: 'json',
			success:  function( response ) {
				if ( ! response || response.error ) {
					console.log( response );
					return;
				}

				if ( response.fragments ) {
					jQuery.each( response.fragments, function( key ) {
						jQuery( key )
							.addClass( 'updating' )
							.fadeTo( '400', '0.6' )
							.block({
								message: null,
								overlayCSS: {
									opacity: 0.6
								}
							});
					});

					jQuery.each( response.fragments, function( key, value ) {
						jQuery( key ).replaceWith( value );
						jQuery( key ).stop( true ).css( 'opacity', '1' ).unblock();
					});

					jQuery( document.body ).trigger( 'wc_fragments_loaded' );

					// jQuery(".for-hov").addClass("active");
				}

				update_wc_div();

				let slidesToShow = slick[0].slick.slickGetOption('slidesToShow');
				let index = $(this).parents(".slick-active").data('slick-index') % (slidesToShow + 1);

				dataLayer.push({
					'ecommerce': {
						'currencyCode': 'UAH',
						'add': {
							'products': [{
								"id": upsells[index]['id'],
								"name": upsells[index]['title'],
								"price": upsells[index]['price_uah'],
								'quantity': 1
							}]
						}
					},
					'event': 'gtm-ee-event',
					'gtm-ee-event-category': 'Enhanced Ecommerce',
					'gtm-ee-event-action': 'Adding a Product to a Shopping Cart',
					'gtm-ee-event-non-interaction': 'False',
				});

				$('html, body').animate({
					scrollTop: $('form.woocommerce-cart-form').offset().top
				}, 1000);
			}
		} );
	});

	$('a.cred', 'form.woocommerce-cart-form').on('click', function(e) {
		var products = [];
		$('p.name', 'form.woocommerce-cart-form').each( function() {
			products.push( $(this).text().trim() );
		} );

		Anketa_Open_Button(
			products.join(', '),
			$('.woocommerce-Price-amount', 'div.total').text().replace(/[^\d|\.]/g, '')
		);
	});
});
