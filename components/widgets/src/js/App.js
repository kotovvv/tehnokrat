// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

/* global tehnokrat */
import React, { useState } from 'react'
import OnlyInStockProductsSwitcher from './OnlyInStockProductsSwitcher'
import Products from './Products'
import Cookies from 'js-cookie'

const App = () => {
	const [inStock, setInStock] = useState(
		undefined === Cookies.get('onlyInStockProducts')
		||
		'true' === Cookies.get('onlyInStockProducts')
	)
	const switchInStock = () => {
		Cookies.set('onlyInStockProducts', !inStock, { expires: 365 })
		setInStock(!inStock)
	}

	return <>
		<OnlyInStockProductsSwitcher
			inStock={inStock}
			switchInStock={switchInStock}
			container={document.getElementById('only-in-stock-products-switcher')}
		/>
		<OnlyInStockProductsSwitcher
			inStock={inStock}
			switchInStock={switchInStock}
			container={document.getElementById('only-in-stock-products-switcher-mobile')}
		/>

		<Products
			inStock={inStock}
			container={document.getElementById('widget-product-variation-selector')}
		/>
	</>
}

export default App
