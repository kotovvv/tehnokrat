// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

/* global tehnokrat */
import React, { useState, useRef } from 'react'
import Cookies from 'js-cookie'
import { createPortal } from 'react-dom'
import ProductImage from './ProductImage'
import AddToCart from './AddToCart'
import classNames from 'classnames'

const App = () => {
	const [inStock] = useState('true' === Cookies.get('onlyInStockProducts'))

	const products = useRef(JSON.parse(tehnokrat.products)).current
	const currentProduct = useRef(products[0]).current
	const currentVariation = useRef(currentProduct.variations[0]).current

	const className = classNames(
		'items',
		{
			'not-available': (currentVariation && 0 === currentVariation.in_stock),
			'not-active': (undefined === currentVariation)
		}
	)

	return createPortal(<div className={className}>
		<ProductImage
			label={currentVariation.label}
			imageSrc={currentVariation.image}
			gallery={currentVariation.gallery}
		/>
		{currentVariation.model.length > 0 && <span className="model">{currentVariation.model}</span>}
		<p className="title">
			{currentProduct.name}
			{currentVariation.modification && currentVariation.modification.length &&
				<span>{currentVariation.modification}</span>}
		</p>
		<AddToCart productName={currentProduct.name} currentVariation={currentVariation} inStock={inStock} />
		<div dangerouslySetInnerHTML={{ __html: tehnokrat.strings.underProduct }}></div>
	</div>,
		document.getElementById('widget-one-product')
	)
}

export default App
