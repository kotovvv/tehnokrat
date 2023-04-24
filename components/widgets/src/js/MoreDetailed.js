// phpcs:disable Generic.Formatting
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceBeforeCloseBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.CloseBracketLine
// phpcs:disable WordPress.WhiteSpace

import React from 'react'

const MoreDetailed = ({ productName, productIndex, currentVariation, variationIndex = 0 }) => {
	const goTo = (e) => {
		e.preventDefault();

		if (undefined !== currentVariation) {
			window.dataLayer && dataLayer.push({
				'ecommerce': {
					'currencyCode': 'UAH',
					'click': {
						'actionField': { 'list': 'category' },
						'products': [{
							'id': currentVariation.id,
							'name': currentVariation.title1,
							'price': currentVariation.priceUAH,
							'list': '',
							'list_name': productName,
							'position': productIndex + 1,
							'list_position': variationIndex + 1
						}]
					}
				},
				'event': 'gtm-ee-event',
				'gtm-ee-event-category': 'Enhanced Ecommerce',
				'gtm-ee-event-action': 'Product Clicks',
				'gtm-ee-event-non-interaction': 'False',
			})

			window.location.href = currentVariation.url
		}
	}
const href_url = (undefined !== currentVariation)?currentVariation.url:''


	return <a className="more-prod-but" href={href_url} onClick={goTo}>
		<span>{tehnokrat.strings['more']}</span>
		<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path
				d="M11.8082 5.53363L7.47457 1.19991C7.35086 1.0762 7.18599 1.0083 7.01018 1.0083C6.83419 1.0083 6.66941 1.0763 6.5457 1.19991L6.15224 1.59347C6.02863 1.71698 5.96053 1.88195 5.96053 2.05785C5.96053 2.23366 6.02863 2.40419 6.15224 2.5277L8.68041 5.06144H0.648287C0.286144 5.06144 0 5.34495 0 5.70719V6.26357C0 6.62582 0.286144 6.93791 0.648287 6.93791H8.7091L6.15234 9.48579C6.02873 9.6095 5.96063 9.76999 5.96063 9.94589C5.96063 10.1216 6.02873 10.2844 6.15234 10.408L6.5458 10.8003C6.6695 10.924 6.83428 10.9914 7.01028 10.9914C7.18609 10.9914 7.35096 10.9231 7.47467 10.7994L11.8083 6.46582C11.9323 6.34172 12.0005 6.17606 12 5.99997C12.0004 5.82329 11.9323 5.65753 11.8082 5.53363Z"
				fill="white"/>
		</svg>
	</a>
}

export default MoreDetailed
