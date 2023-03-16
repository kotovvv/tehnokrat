// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

import React, { useRef, memo } from 'react'
import { createPortal } from 'react-dom'
import Product from './Product'

const Products = memo(({ inStock, container }) => {
	const products = useRef(JSON.parse(tehnokrat.products)).current

	let alignLeft = true

	return createPortal(products.map((product, i) => {
			const variations = product.variations.filter(variation => {
				return inStock ? variation.in_stock : true
			})

			if (variations.length > 0) {
				alignLeft = !alignLeft

				return <Product
					key={i}
					productIndex={i}
					productName={product.name}
					inStock={inStock}
					variations={variations}
					align={alignLeft ? 'left' : 'right'}
				/>
			}
		})
		,
		container
	)
})

export default Products
