Vue.directive('mask', {
	bind: function(el, binding) {
		window.Inputmask(binding.value).mask(el);
	}
});
/* Селекторы. Начало. */
var tehnokratSelectorComponent = {
	props: ['items', 'value', 'availableItems', 'title', 'attribute'],
	methods: {
		selectVariation: function (attribute) {
			this.$parent.selectVariation(attribute);
		}
	}
};
Vue.component('tehnokrat-selector', _.merge(
	{ template: '#tehnokrat-selector' },
	tehnokratSelectorComponent
) );
Vue.component('tehnokrat-color-selector', _.merge(
	{ template: '#tehnokrat-color-selector' },
	tehnokratSelectorComponent
) );
Vue.component('tehnokrat-color-selector-2', _.merge(
	{ template: '#tehnokrat-color-selector-2' },
	tehnokratSelectorComponent
) );
/* Селекторы. Конец. */


Vue.component( 'modelsSelector', {
	template: '#models-selector',
	props: ['items', 'value'],
	methods: {
		selectVariation: function (model) {
			this.$parent.selectVariationByModel(model);
		}
	}
} );

Vue.component( 'moreDetailed', {
	template: '#more-detailed',
	methods: {
		goToProductPage: function () {
			let productComponent = this.$parent;
			let currentVariation = productComponent.variations[productComponent.currentVariationID];

			dataLayer.push({
				'ecommerce': {
					'currencyCode': 'UAH',
					'click': {
						'actionField': {'list': 'category'},
						'products': [{
							"id": currentVariation.id,
							"name": currentVariation.title1,
							"price": currentVariation.priceUAH,
							"list": '',
							"list_name": productComponent.product.name,
							"position": productComponent.index + 1,
							"list_position": productComponent.currentVariationID + 1
						}]
					}
				},
				'event': 'gtm-ee-event',
				'gtm-ee-event-category': 'Enhanced Ecommerce',
				'gtm-ee-event-action': 'Product Clicks',
				'gtm-ee-event-non-interaction': 'False',
			});

			window.location.href = currentVariation.url;
		}
	}
} );

Vue.component( 'productLabel', {
	template: '#product-label',
	props: ['label'],
	computed: {
		style: function() {
			switch ( this.label ) {
				case 'Лучшая цена':
					return '#E1306C';
				case 'Новинка':
					return '#93D500';
				case 'Скидка':
					return '#FD5958';
				case 'Топ продаж':
					return '#00AE43';
				case 'Акция':
					return '#F80126';
				case 'Рекомендуем':
					return '#F6C000';
				case 'Восстановленный':
					return '#4084b3';
				case 'Dual SIM':
					return '#000000';
				case 'Open Box':
					return '#0085FF';
			}
		}
	},
} );

Vue.component('add-to-cart', {
	template: '#add-to-cart',
	data: function () {
		return {
			isBuyInInstallmentsVisible: false
		}
	},
	methods: {
		showProductCard: function () {
			this.$parent.showProductCard();
		},
		buyInInstallmentsEnter: function () {
			this.isBuyInInstallmentsVisible = true;
		},
		buyInInstallmentsLeave: function () {
			this.isBuyInInstallmentsVisible = false;
		},
		buyInInstallments: function () {
			jQuery(window).trigger("buyInInstallmentsShow", {
				id: this.$parent.currentVariation.id,
				title: this.$parent.currentVariation.title1,
				price: this.$parent.currentVariation.priceUAH,
				image: this.$parent.currentVariation.image,
				description: this.$parent.currentVariation.description
			});
		}
	}
});

Vue.component( 'product-card', {
	template: '#product-card',
	data: function () {
		return {
			paymentMethod: '',
			shippingMethod: '',
			deliveryCities: '',
			name: '',
			phone: '380',
			phoneMask: '+99(999)999-99-99',
			email: ''
		}
	},
	computed: {
		headerHeight: function() {
			return jQuery(window).width() < 768 ? jQuery('header').height() : 0;
		},
		selectedProduct: function() {
			return this.$root.currentProduct || {};
		},
		bankTransferFee: function () {
			return ( 'liqpay' == this.paymentMethod ) ? this.$root.bank_transfer_fee : 1;
		},
		priceUSD: function() {
			var exchange_rate = this.$root.exchange_rate || 1;

			return Math.ceil( this.priceUAH / exchange_rate );
		},
		priceUAH: function () {
			var value = this.selectedProduct.currentVariation.priceUAH || 0;

			return ( value * this.bankTransferFee );
		}
	},
	methods: {
		buyInInstallments: function () {
			Anketa_Open_Button(
				this.selectedProduct.currentVariation.title1,
				this.priceUAH.format( 0, 3, ' ', '.' )
			);
		},
		addToCart: function() {
			this.$root.addToCart( this.$data );
		},
		close: function() {
			this.$root.closeProductCard();
		}
	},
	updated: function () {
		this.$nextTick(function () {
			var popparam = jQuery('.product_popup .param div').height();
			jQuery('.product_popup .about-item').toggleClass( 'remaft', popparam < 200 );
		});
	}
} );

Vue.component( 'product_pred_popup', {
	template: '#product_pred_popup',
	data: function () {
		return {
			deliveryCity: '',
			name: '',
			phone: ''
		}
	},
	computed: {
		headerHeight: function() {
			return jQuery(window).width() < 768 ? jQuery('header').height() : 0;
		},
		selectedProduct: function() {
			return this.$root.currentProduct || {};
		},
		bankTransferFee: function () {
			return ( 'liqpay' == this.paymentMethod ) ? this.$root.bank_transfer_fee : 1;
		},
		priceUSD: function() {
			var exchange_rate = this.$root.exchange_rate || 1;

			return Math.ceil( this.priceUAH / exchange_rate );
		},
		priceUAH: function () {
			var value = this.selectedProduct.currentVariation.priceUAH || 0;

			return ( value * this.bankTransferFee );
		}
	},
	methods: {
		createPreOrder: function() {
			var data = {
				'action':       'tehnokrat_create_pre_order',
				'product_id':   this.selectedProduct.currentVariation.id,
				'deliveryCity': this.deliveryCity,
				'name':         this.name,
				'phone':        this.phone
			};

			jQuery.post( tehnokrat.ajax_url, data, function( responce ) {
				if ( false === responce.success ) {
					console.log( responce.data );
				}
				jQuery('.product_pred_popup').removeClass('active');
				jQuery('.thank_popup').addClass('active');
			} );
		},
		close: function() {
			vm.currentProduct = undefined;

			jQuery('.product_pred_popup').removeClass('active');
			jQuery('body').removeClass('popup');
		}
	}
} );

Vue.component( 'tehnokrat-thank-you-popup', {
	template: '#tehnokrat-thank-you-popup',
	methods: {
		close: function() {
			jQuery('.thank_popup').removeClass('active');
			jQuery('.wrapper').removeClass('popup');
			jQuery('body').removeClass('popup');
		}
	}
} );

var dummyVariation = {
	id: '',
	model: '',
	attributes: {
		color: '',
		memory: '',
		processor: '',
		frequency: '',
		screen_diagonal: ''
	},
	description: '',
	modification: '',
	priceUSD: 0,
	priceUAH: 0,
	in_stock: 0,
	image: ''
};

var generalComponent = {
	props: ['product', 'index', 'ignoreOnlyInStockProducts'],
	data: function() {
		return {
			currentVariationID: undefined,
			isVisible: false
		};
	},
	computed: {
		variations: function () {
			var variations = this.product.variations;

			if ( this.$root.onlyInStockProducts && this.ignoreOnlyInStockProducts !== true ) {
				variations = _.filter(variations, function (variation) {
					return variation.in_stock;
				});
			}

			if ( _.isEmpty( variations ) ) {
				variations.push( dummyVariation );
			}
			this.currentVariationID =  0;

			return variations;
		},
		currentVariation: function () {
			this.pushImpression();

			return this.variations[this.currentVariationID];
		},
		inStock: function () {
			// return this.$parent.currentVariation.in_stock;
			return this.currentVariation.in_stock || 0;
		},
		priceUSD: function() {
			var exchange_rate = this.$root.exchange_rate || 1;

			return ( this.priceUAH / exchange_rate );
		},
		priceUAH: function () {
			return this.currentVariation.priceUAH || 0;
		}
	},
	methods: {
		showProductCard: function () {
			this.$root.showProductCard( this );
		},
		buyInInstallments: function () {
			this.$root.buyInInstallments( this );
		},
		showGallery: function () {
			if ( this.currentVariation.gallery.length ) {
				jQuery('.slider').slick( 'slickRemove', true, true, true );
				_.forEach( this.currentVariation.gallery, function( url ) {
					jQuery('.slider').slick( 'slickAdd', jQuery( '<div/>' ).append( jQuery( '<img/>', { src: url } ) ) );
				});
				jQuery('body').addClass('popup');
				jQuery('.wrapper').addClass('popup');
				jQuery('.slider_popup').addClass('active');
			}
		},
		getSortedAttribute: function( attributeName ) {
			return this.attributes[ attributeName ].sort( function( a, b ) {
				switch( attributeName ) {
					case 'drive':
						a = a.replace( 'MB', '' ).replace( 'GB', '000' ).replace( 'TB', '000000' );
						b = b.replace( 'MB', '' ).replace( 'GB', '000' ).replace( 'TB', '000000' );
						break;
					case 'memory':
						a = a.replace( 'MB', '' ).replace( 'GB', '000' ).replace( 'TB', '000000' );
						b = b.replace( 'MB', '' ).replace( 'GB', '000' ).replace( 'TB', '000000' );
						break;
					// 'processor'
					// 'graphics'
					// 'color'
					// 'size'
					// 'band'
					// 'ethernet'
					// 'connectivity'
					default:
						a = a.toString().replace(/[^0-9]/g, '');
						b = b.toString().replace(/[^0-9]/g, '');
						break;
				}

				a = parseInt( a );
				b = parseInt( b );

				return a - b;
			} );

		},
		pushImpression: function () {
			if ( !this.isVisible || 'pushed' in this.variations[this.currentVariationID] ) {
				return;
			}

			this.variations[this.currentVariationID].pushed = true;

			if ('template0' === this.$options._componentTag) {
				window.dataLayer.push({
					'ecommerce': {
						'currencyCode': 'UAH',
						'detail': {
							'actionField': {'list': 'Product detail page'},
							'products': [{
								"id": this.variations[this.currentVariationID].id,
								"name": this.variations[this.currentVariationID].title1,
								"price": this.variations[this.currentVariationID].priceUAH
							}]
						}
					},
					'event': 'gtm-ee-event',
					'gtm-ee-event-category': 'Enhanced Ecommerce',
					'gtm-ee-event-action': 'Product Details',
					'gtm-ee-event-non-interaction': 'True',
				});
			} else {
				window.dataLayer.push({
					"ecommerce": {
						"currencyCode": "UAH",
						"impressions": [{
							"id": this.variations[this.currentVariationID].id,
							"name": this.variations[this.currentVariationID].title1,
							"price": this.variations[this.currentVariationID].priceUAH,
							"list": '',
							"list_name": this.product.name,
							"position": this.index + 1,
							"list_position": this.currentVariationID + 1
							// "brand": "Poyo",
							// "category": "T-Shirts",
							// "variant": "" //	Текст	Нет	Вариант товара. Пример: "Черный".
						}]
					}
				});
			}
		},
		setIsVisible: function () {
			if ( !this.$el || typeof this.$el.getBoundingClientRect !== 'function' ) {
				return false;
			}

			const rect = this.$el.getBoundingClientRect();

			this.isVisible = (
				rect.top >= 0 &&
				rect.left >= 0 &&
				rect.bottom <= 80+(window.innerHeight || document.documentElement.clientHeight) &&
				rect.right <= (window.innerWidth || document.documentElement.clientWidth)
			);
		}
	},
	created: function() {
		window.addEventListener('scroll', this.setIsVisible);
	},
	mounted: function() {
		this.setIsVisible();
		this.pushImpression();
	},
	destroyed () {
		window.removeEventListener('scroll', this.setIsVisible);
	},
};

var componentWithVariations = _.merge(
	{
		methods: {
			getAttributeValues: function( attributeName ) {
				var values = [];

				_.forEach( this.variations, _.bind( function( variation ) {
					// выбрать только вариации оличающиеся только одним атрибу
					// if ( this.compareVariationWithCurrent( variation.attributes ) < 2 ) {
						_.forEach( variation.attributes, _.bind( function( value, key ) {
							if( attributeName == key ) {
								values.push( {
									'value': value,
									'unavailable': ( this.compareVariationWithCurrent( variation.attributes ) > 1 )
								} )
							}
						}, this ) );
					// }
				}, this ) );

				return _.uniqBy( values, 'value' ).sort( function( a, b ) { return parseFloat(a) - parseFloat(b); } );
			},
			compareVariationWithCurrent: function( variation ) {
				var currentAttributes = this.currentVariation.attributes;

				return _.reduce( variation, function( result, value, key ) {
					return _.isEqual( value, currentAttributes[ key ] ) ? result : result + 1;
				}, 0 );
			},
			compareVariations: function( variation1, variation2 ) {
				var diff = 0;
				variation2 = variation2 || this.currentVariation.attributes;

				jQuery.each( variation1, function( key, values ) {
					// если в атрибутах есть цвет - добавляем 1000
					if ( 'color' == key ) {
						diff += 1000;
					}
					if ( variation2[ key ] != values ) {
						diff += 1;
						// несоответствие цвета добавляет +100 к разнице
						if ( 'color' == key ) {
							diff += 100;
						}
					}
				} );

				return diff;
			},
			selectVariation: function( attribute ) {
				var _diff = 10000,
					diff = 10000,
					attributeName = Object.keys( attribute )[0],
					attributeValue = attribute[ attributeName ],
					_currentVariationID = this.currentVariationID,
					item = jQuery.extend( {}, this.currentVariation.attributes, attribute );

				// если выбран текущий вариант - выход
				if( _.isEqual( this.currentVariation.attributes, item ) ) {
					return;
				}

				_.forEach( this.variations, _.bind( function( variation, index ) {
					// не проверять если:
					// 1. уже найден подходящий вариан;
					// 2. проверяемый вариант является текущим
					// 3. проверяемый вариант не содержит желаемого значения
					if ( diff > 0  && this.currentVariationID != index && variation.attributes[ attributeName ] == attributeValue ) {
						_diff = this.compareVariations( variation.attributes, item );
						if ( _diff < diff ) {
							diff = _diff;
							_currentVariationID = index;
						}
					}
				}, this) );

				this.currentVariationID = _currentVariationID;
			}
		},
		computed: {
			attributes: function() {
				var attributes = {};

				_.forEach( this.variations, _.bind( function( variation ) {
					_.forEach( variation.attributes, _.bind( function( value, key ) {
						if( ! attributes[key] ) {
							// _.assign( attributes, { [key]: [] } );
							attributes[ key ] = [];
						}
						attributes[key].push( value );
					}, this ) );
				}, this ) );

				attributes = _.mapValues( attributes, function( values ) {
					return _.uniq( values ).sort( function( a, b ) { return parseFloat(a) - parseFloat(b); } );
				} );

				return attributes;
			},
			availableAttributes: function () {
				var attributes = {},
					diff       = 0;

				_.forEach( this.variations, _.bind( function( variation ) {

					diff = this.compareVariations( variation.attributes );
					// если в атрибутах нет цвета и разница только в один атрибут
					// или
					// если в атрибутах есть цвет и цвета одинаковые
					if ( ( diff < 1000 && diff < 2 ) || ( diff >= 1000 && diff < 1002 ) ) {
						_.forEach(variation.attributes, _.bind(function (value, key) {
							if (!attributes[key]) {
								attributes[key] = [];
							}
							attributes[key].push(value);
						}, this));
					}
				}, this ) );

				attributes = _.mapValues( attributes, function( values ) {
					return _.uniq( values ).sort( function( a, b ) { return parseFloat(a) - parseFloat(b); } );
				} );

				return attributes;
			}
		}
	},
	generalComponent
);

var componentWithVariationsAndModels = _.merge(
	{
		computed: {
			models: function() {
				var models = [];

				_.forEach( this.variations, function( variation ) {
					if ( variation.is_featured ) {
						models.push( variation.model );
					}
				} );

				return models;
			}
		},
		methods: {
			selectVariationByModel: function( model ) {
				_.forEach( this.variations, _.bind( function( variation, index ) {
					if( variation.model === model ) {
						this.currentVariationID = index;
					}
				}, this) );
			}
		}
	},
	componentWithVariations
);

Vue.component( 'template0', {
	template: '#template0',
	extends: generalComponent
} );

Vue.component( 'template1', {
	template: '#template1',
	extends: generalComponent
} );

Vue.component( 'template2', {
	template: '#template2',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template3', {
	template: '#template3',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template4', {
	template: '#template4',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template5', {
	template: '#template5',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template6', {
	template: '#template6',
	extends: componentWithVariations
} );

Vue.component( 'template7', {
	template: '#template7',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template8', {
	template: '#template8',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template9', {
	template: '#template9',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template10', {
	template: '#template10',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template11', {
	template: '#template11',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template12', {
	template: '#template12',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template13', {
	template: '#template13',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template14', {
	template: '#template14',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template15', {
	template: '#template15',
	extends: componentWithVariations
} );

Vue.component( 'template16', {
	template: '#template16',
	extends: componentWithVariations
} );

Vue.component( 'template17', {
	template: '#template17',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template18', {
	template: '#template18',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template19', {
	template: '#template19',
	extends: componentWithVariationsAndModels
} );

Vue.component( 'template20', {
	template: '#template20',
	extends: componentWithVariationsAndModels
} );

vm = new Vue({
	el: '#vue-app',
	data: {
		products:            JSON.parse( tehnokrat.products ),
		exchange_rate:       tehnokrat.exchange_rate || 1,
		bank_transfer_fee:   tehnokrat.bank_transfer_fee || 1,
		onlyInStockProducts: true,
		currentProduct:      undefined
	},
	methods: {
		showProductCard: function( productComponent ) {
			this.currentProduct = productComponent;

			if ( 1 === this.currentProduct.currentVariation.in_stock ) {
				this.addToCart();
			} else {
				jQuery('body').addClass('popup');
				jQuery('.product_pred_popup').addClass('active');
			}
		},
		closeProductCard: function () {
			this.currentProduct = undefined;

			jQuery('.product_popup').removeClass('active');
			jQuery('body').removeClass('popup');
		},
		buyInInstallments: function( productComponent ) {
			Anketa_Open_Button(
				productComponent.product.name + ' (' + productComponent.currentVariation.model + ')',
				productComponent.priceUAH.format( 0, 3, ' ', '.' )
			);
		},
		is_blocked: function( $node ) {
			return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
		},
		block: function( $node ) {
			if ( ! this.is_blocked( $node ) ) {
				$node.addClass( 'processing' ).block( {
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				} );
			}
		},
		unblock: function( $node ) {
			$node.removeClass( 'processing' ).unblock();
		},
		addToCart: function() {
			dataLayer.push({
				'ecommerce': {
					'currencyCode': 'UAH',
					'add': {
						'products': [{
							"id": this.currentProduct.currentVariation.id,
							"name": this.currentProduct.currentVariation.title1,
							"price": this.currentProduct.currentVariation.priceUAH,
							'quantity': 1
						}]
					}
				},
				'event': 'gtm-ee-event',
				'gtm-ee-event-category': 'Enhanced Ecommerce',
				'gtm-ee-event-action': 'Adding a Product to a Shopping Cart',
				'gtm-ee-event-non-interaction': 'False',
			});

			var data = {
				'product_id': this.currentProduct.currentVariation.id
			};

			this.block(jQuery(window));

			// Ajax action.
			jQuery.post( wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' ), data, function( response ) {
				if ( ! response || response.error ) {
					console.log( response );
					return;
				}

				// Trigger event so themes can refresh other areas.
				// jQuery( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, jQuery( 'a.add' ) ] );
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

					if ( jQuery(window).width() >= 768) {
						jQuery('.for-hov').addClass('active');
						jQuery('.backdrop').addClass('active');
					}
				}
			})
			.always(function () {
				this.unblock(jQuery(window));
			}.bind(this));
		}
	},
	watch: {
		onlyInStockProducts: function( value ) {
			if (1 === _.size(this.products) && 1 === _.size(this.products[0].variations)) {
			} else {
				Cookies.set( 'onlyInStockProducts', value );
			}
		}
	},
	created: function() {
		var onlyInStockProducts = Cookies.get( 'onlyInStockProducts' );

		if (1 === _.size(this.products) && 1 === _.size(this.products[0].variations)) {
			this.onlyInStockProducts = false;
		} else {
			this.onlyInStockProducts = ( typeof onlyInStockProducts === 'undefined' || onlyInStockProducts === 'true' );
		}

		window.dataLayer = window.dataLayer || [];
	},
	mounted: function() {
		(function (d) {
			var s = d.createElement('script');
			s.defer = true;
			s.src = 'https://multisearch.io/plugin/10995';
			if (d.head) d.head.appendChild(s);
		})(document);
	}
});
