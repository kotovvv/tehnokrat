import React, { useState } from 'react'
import OnlyInStockProductsSwitcher from './OnlyInStockProductsSwitcher'
import Products from './Products'
import Blocks from './Blocks'
import TypeDisplay from './TypeDisplay'
import Sort from './Sort'
import Cookies from 'js-cookie'

const App = () => {
	const [inSort, setInSort] = useState('')
	const switchInSort = (e) => {
		setInSort(e.target.value)
	}
	const [inStock, setInStock] = useState(
		undefined === Cookies.get('onlyInStockProducts')
		||
		'true' === Cookies.get('onlyInStockProducts')
	)
	const switchInStock = () => {
		Cookies.set('onlyInStockProducts', !inStock, { expires: 365 })
		setInStock(!inStock)
	}

	const [inDisplay, setInDisplay] = useState(
		Cookies.get('onlyInDisplay') ?? 'lines')

	const switchInDisplay = (e) => {
		Cookies.set('onlyInDisplay', e.target.value, { expires: 365 })
		setInDisplay(e.target.value)
		if (e.target.value === 'bloks') {
			document.body.classList.add('bloks')
		} else {
			document.body.classList.remove('bloks')
		}
	}
	if (Cookies.get('onlyInDisplay') === 'bloks') {
		document.body.classList.add('bloks')
	}

	return <>
		<TypeDisplay inDisplay={inDisplay} switchInDisplay={switchInDisplay} container={document.getElementById('type-display-switcher')} />

		{inDisplay === 'lines' ?
			<>
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
					container={document.getElementById('widget-product-variation-selector')} />
			</>
			:
			<>
				<Sort inSort={inSort} switchInSort={switchInSort} container={document.getElementById('only-in-stock-products-switcher')} />

				<Blocks inStock={inStock} inSort={inSort}
					container={document.getElementById('widget-product-variation-selector')} />
			</>
		}
	</>
}

export default App
