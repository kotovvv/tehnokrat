import React, { useState } from 'react'
import Blocks from './Blocks'
import Sort from './Sort'

const App = () => {

	const [inSort, setInSort] = useState('upcost')
	const switchInSort = (e) => {
		setInSort(e.target.value)
	}
	if (!document.body.classList.contains('bloks')) {
		document.body.classList.add('bloks')
	}

	return <>
		<Sort inSort={inSort} switchInSort={switchInSort} container={document.getElementById('only-in-stock-products-switcher')} />

		<Blocks
			inSort={inSort}

			container={document.getElementById('widget-product-variation-selector')} />
	</>
}

export default App
