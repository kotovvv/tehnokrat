// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceBeforeCloseBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.CloseBracketLine
// phpcs:disable WordPress.WhiteSpace

/* global tehnokrat */
import React, { useState, memo } from 'react'
import NotifyProductInStockPopup from './NotifyProductInStockPopup'
import BlockUI from './BlockUI'
// import BuyInInstallments from './BuyInInstallments'
import Part from './Part'


const classNames = require('classnames')

const AddToCart = memo(({ productName, currentVariation, inStock }) => {
	const isTouchEnable = (undefined !== document.body.ontouchstart)

	const [isNotifyProductInStockPopupVisible, setIsNotifyProductInStockPopupVisible] = useState(false)
	// const [isBuyInInstallmentsButtonVisible, setIsBuyInInstallmentsButtonVisible] = useState(false)
	// const [isBuyInInstallmentsPopupVisible, setIsBuyInInstallmentsPopupVisible] = useState(false)
	const [isSelectAttributesNotificationVisible, setIsSelectAttributesNotificationVisible] = useState(false)

	const addToCart = () => {
		setBlockUI(true)

		window.dataLayer && dataLayer.push({
			'ecommerce': {
				'currencyCode': 'UAH',
				'add': {
					'products': [{
						'id': currentVariation.id,
						'name': currentVariation.title1,
						'price': currentVariation.priceUAH,
						'quantity': 1
					}]
				}
			},
			'event': 'gtm-ee-event',
			'gtm-ee-event-category': 'Enhanced Ecommerce',
			'gtm-ee-event-action': 'Adding a Product to a Shopping Cart',
			'gtm-ee-event-non-interaction': 'False',
		})

		const data = new URLSearchParams({
			'product_id': currentVariation.id,
		})
		const request = new XMLHttpRequest()
		request.open('POST', wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'))
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
		request.send(data.toString())
		request.onload = () => {
			setBlockUI(false)

			if (request.status !== 200) {
				alert('Что-то пошло не так. Повторите, пожалуйста, запрос.')
			} else {
				const response = JSON.parse(request.response)

				if (!response || response.error) {
					console.log(response)
					return
				}

				// Trigger event so themes can refresh other areas.
				// jQuery( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, jQuery( 'a.add' ) ] );
				if (response.fragments) {
					jQuery.each(response.fragments, function (key) {
						jQuery(key)
							.addClass('updating')
							.fadeTo('400', '0.6')
							.block({
								message: null,
								overlayCSS: {
									opacity: 0.6
								}
							})
					})

					jQuery.each(response.fragments, function (key, value) {
						jQuery(key).replaceWith(value)
						jQuery(key).stop(true).css('opacity', '1').unblock()
					})

					jQuery(document.body).trigger('wc_fragments_loaded')

					if (jQuery(window).width() >= 768) {
						jQuery('.for-hov').addClass('active')
						jQuery('.backdrop').addClass('active')
					}
				}
			}
		}
		request.onerror = () => {
			alert('Что-то пошло не так.. Повторите, пожалуйста, запрос.')
		}
	}
	// const onTouchStart = () => {
	// 	if (isBuyInInstallmentsButtonVisible) {
	// 		setIsBuyInInstallmentsButtonVisible(false)
	// 		setIsBuyInInstallmentsPopupVisible(true)
	// 	} else {
	// 		setIsBuyInInstallmentsButtonVisible(true)
	// 		setTimeout(() => {
	// 			setIsBuyInInstallmentsButtonVisible(false)
	// 		}, 3000)
	// 	}
	// }
	// const buyInInstallmentsClick = () => {
	// 	setIsBuyInInstallmentsPopupVisible(true)
	// }
	// const buyInInstallmentsEnter = () => {
	// 	setIsBuyInInstallmentsButtonVisible(true)
	// }
	// const buyInInstallmentsLeave = () => {
	// 	setIsBuyInInstallmentsButtonVisible(false)
	// }
	const [blockUI, setBlockUI] = useState(false)

	if (undefined === currentVariation) {
		return <div className="sum-link clearfix">
			<div className="link w-cr">
				<a
					className="buy_pred"
					onClick={() => {
						setIsSelectAttributesNotificationVisible(true)
						setTimeout(() => setIsSelectAttributesNotificationVisible(false), 1500)
					}}
				>
					Купить
					<span className={isSelectAttributesNotificationVisible ? 'active' : ''}>
						*Выбраны не все параметры
					</span>
				</a>
			</div>
		</div>
	} else {
		return <>
			{true === blockUI && <BlockUI />}
			<div className="sum-link clearfix">
				<div className="link w-cr">
					{1 === currentVariation.in_stock && <>
						<a className="buy"
							onClick={addToCart}>
							<p>{tehnokrat.strings['Buy item']}</p>
						</a>
					</>
					}
					{0 === currentVariation.in_stock &&
						<a className="buy_pred" onClick={() => setIsNotifyProductInStockPopupVisible(true)}>
							{tehnokrat.strings['Report Admission']}
						</a>}
				</div>
				{true === currentVariation.tradeIn &&
					<a className="trade-but" href="/trade-in/">
						Trade-in
						<svg xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 50 45"
							fill="none">
							<path
								d="M44.2447 6.6847V11.2875C43.914 10.7143 43.5579 10.1543 43.1752 9.6099C41.8357 7.7045 40.2132 6.02749 38.3525 4.62538C36.4696 3.20655 34.3873 2.10322 32.1637 1.34608C29.8639 0.563026 27.4557 0.166016 25.0064 0.166016C22.4486 0.166016 19.9399 0.598106 17.5501 1.45034C15.2406 2.2739 13.0941 3.46998 11.1703 5.0054C9.2655 6.52563 7.63273 8.33512 6.31749 10.3836C4.97764 12.4704 4.00488 14.7479 3.42622 17.1526C3.05442 18.6977 4.00559 20.2518 5.55082 20.6236C5.77709 20.678 6.00352 20.7041 6.22639 20.7041C7.52573 20.7041 8.70449 19.8179 9.02185 18.499C9.86981 14.975 11.9078 11.7804 14.7604 9.50373C16.1856 8.36631 17.7746 7.48064 19.4831 6.87139C21.2508 6.24105 23.1091 5.92143 25.0064 5.92143C28.606 5.92143 32.0234 7.06275 34.889 9.22194C36.828 10.683 38.4267 12.5744 39.5557 14.7084H36.3674C34.7782 14.7084 33.4898 15.9968 33.4898 17.5861C33.4898 19.1754 34.7782 20.4638 36.3674 20.4638H47.1223C48.7117 20.4638 50 19.1754 50 17.5861V6.6847C50 5.0954 48.7117 3.80702 47.1223 3.80702C45.5331 3.80702 44.2447 5.0954 44.2447 6.6847Z"
								fill="#61779D" />
							<path
								d="M2.87768 40.9161C4.46698 40.9161 5.75536 39.6277 5.75536 38.0384V33.4162C7.46964 36.4001 9.85652 38.9499 12.7525 40.8705C16.3836 43.2787 20.6136 44.5531 24.9865 44.5569C24.9889 44.5569 24.9913 44.5571 24.9936 44.5571C24.9955 44.5571 24.9975 44.557 24.9994 44.557C25.0017 44.557 25.004 44.5571 25.0062 44.5571C25.0093 44.5571 25.0122 44.5569 25.0153 44.5569C27.5654 44.5544 30.0667 44.1225 32.4498 43.2727C34.7593 42.4491 36.9058 41.2531 38.8296 39.7177C40.7345 38.1975 42.3672 36.3879 43.6825 34.3395C45.0223 32.2527 45.9951 29.9752 46.5738 27.5705C46.9455 26.0254 45.9944 24.4714 44.4491 24.0996C42.9038 23.7278 41.3499 24.6789 40.9781 26.2241C40.1301 29.7482 38.0921 32.9428 35.2395 35.2194C33.8143 36.3568 32.2254 37.2426 30.5168 37.8517C28.7509 38.4814 26.8946 38.8009 24.9995 38.8016C21.7571 38.8003 18.6222 37.8572 15.9335 36.074C13.6149 34.5363 11.7369 32.456 10.4518 30.0147H13.6325C15.2219 30.0147 16.5102 28.7263 16.5102 27.137C16.5102 25.5477 15.2218 24.2593 13.6325 24.2593H2.87768C1.28838 24.2593 0 25.5477 0 27.137V38.0384C0 39.6277 1.28838 40.9161 2.87768 40.9161Z"
								fill="#61779D" />
						</svg>
					</a>
				}
				<div className="sum">
					<p>{currentVariation.priceUAH.toLocaleString('ru-RU')}<span> грн</span></p>
					<span>${currentVariation.priceUSD.toLocaleString('ru-RU')}</span>

					{inStock === false &&
						<i>{1 === currentVariation.in_stock ? tehnokrat.strings['Product in stock'] : tehnokrat.strings['Not available']}</i>}
				</div>
			</div>
			{true === isNotifyProductInStockPopupVisible &&
				<NotifyProductInStockPopup
					productName={productName}
					currentVariation={currentVariation}
					closePopup={() => setIsNotifyProductInStockPopupVisible(false)}
				/>}
			{/* {true === isBuyInInstallmentsPopupVisible &&
				<BuyInInstallments
					currentVariation={currentVariation}
					closePopup={() => setIsBuyInInstallmentsPopupVisible(false)}
				/>
			} */}
			<Part part={currentVariation.part} partprivat={currentVariation.partprivat} />
		</>
	}
})

export default AddToCart
