jQuery(document).ready(function ($) {
	jQuery(window).on('buyInInstallmentsShow', buyInInstallmentsShow)
	jQuery(window).on('buyInInstallmentsHide', buyInInstallmentsHide)

	$('.cr').on('touchstart', function () {
		$(this).prev('.buy').addClass('hid')
		setTimeout(function () {
			$(this).prev('.buy').removeClass('hid')
		}, 3000)
	})
	$('.cr p').on('click', buyInInstallmentsShow)
	if ($(window).width() >= 1024) {
		$('.cr').mouseenter(function () {
			$(this).prev('.buy').addClass('hid')
		})
		$('.cr').mouseleave(function () {
			$(this).prev('.buy').removeClass('hid')
		})
		$('.cr ').on('click', buyInInstallmentsShow)
	}

	$('.product_cred_popup .close').on('click', buyInInstallmentsHide)
	$('.product_cred_popup .back').on('click', buyInInstallmentsHide)

	function buyInInstallmentsShow (event, productData) {
		$('input[name=product-id]', 'section.product_cred_popup').val(productData.id)
		$('img', 'section.product_cred_popup').attr('src', productData.image)
		$('p.model', 'section.product_cred_popup').html(productData.title)
		$('p.description', 'section.product_cred_popup').html(productData.description)
		document.buyInInstallmentsPrice = productData.price

		const searchParams = new URLSearchParams({
			n: productData.title,
			q: 1,
			p: productData.price,
			i: productData.image,
			pu: productData.url
		})
		$('iframe', 'form.pop-cont').attr('src', 'https://api.sloncredit.com.ua/pos/iframe?c=306&' + searchParams.toString())

		onBankChange()

		$('.product_cred_popup').addClass('active')
		$('body').addClass('popup')
	}

	function buyInInstallmentsHide () {
		$('.product_cred_popup').removeClass('active')
		$('body').removeClass('popup')
	}

	window.onBankChange = function () {
		const bank = $('input[name=bank]:checked', '.pop-cont').val()

		$('div.form', 'form.pop-cont').css({
			'flex-basis': 'alf' === bank ? '65%' : '100%',
			'min-height': 'alf' === bank ? 'auto' : '50vh',
		})
		$('div.other', 'form.pop-cont').toggle('alf' === bank)
		$('div.info', 'form.pop-cont').toggle('alf' === bank)
		$('iframe', 'form.pop-cont').toggle('alf' !== bank)
	}

	window.onPartsCountChange = function () {
		calculateTotal()
	}

	window.calculateTotal = function () {
		const price = document.buyInInstallmentsPrice,
			quantity = document.getElementById('quantity').value,
			parts = $('input[name=month]:checked', '.pop-cont').val(),
			bank = $('input[name=bank]:checked', '.pop-cont').val()
		let total = price * quantity,
			monthlyPayment = total / parts

		// вычисляю ежемесячный платеж
		if ('alf' === bank && parts > 5) {
			monthlyPayment *= 1.05
		} else if ('pr' === bank) {
			monthlyPayment += total * 0.029
		}
		monthlyPayment = Math.ceil(monthlyPayment)

		// корректирую сумму с учетом округления
		total = monthlyPayment * parts

		$('input[name=monthly-payment]', '.pop-cont').val(monthlyPayment)
		$('input[name=total]', '.pop-cont').val(total)

		$('.priceinmonth span').text(monthlyPayment.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' грн')
		$('.last-price span').text(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' грн')
	}

	jcf.replaceAll()

	$('.wr-cit').on('click', function () {
		$('.cit .jcf-radio').removeClass('jcf-checked')
		$(this).prev('.jcf-radio').removeClass('jcf-unchecked')
		$(this).prev('.jcf-radio').addClass('jcf-checked')
		$('#wr').attr('checked', true)
	})

	$('form.pop-cont').submit(function (event) {
		event.preventDefault()

		const popup = $('.product_cred_popup')
		vm.block(popup)

		let data = {
			nonce: tehnokrat.nonce,
			action: 'create_order_in_installments',
		}
		$(this).serializeArray().forEach(function (item) {
			data[item['name']] = item['value']
		})

		console.log(data)

		// Ajax action.
		$.post(tehnokrat.ajax_url, data, function (response) {
			if (!response || response.error) {
				console.log(response)
				return
			}

			if ('alf' === data.bank) {
				window.location.href = response.data
			} else {
				$('<iframe>', {
					src: response.data,
					// id:  'myFrame',
					frameborder: 0,
					width: '100%',
					height: '400px'
				}).appendTo('.product_popup_content')
				$('form', '.product_popup_content').hide()
			}
		}).always(function () {
			vm.unblock(popup)
		})
	})
})
