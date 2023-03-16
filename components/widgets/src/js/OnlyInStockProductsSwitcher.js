// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

/* global tehnokrat */
import React, { memo } from 'react'
import { createPortal } from 'react-dom'

const OnlyInStockProductsSwitcher = memo(({ inStock, switchInStock, container }) => {
	return createPortal(<form className="prod" action="">
			<label htmlFor="checkbox">{tehnokrat.strings['all products']}</label>
			<input checked={inStock} onChange={switchInStock} type="checkbox" className="checkbox" id="checkbox"/>
			<label htmlFor="checkbox">{tehnokrat.strings['in stock']}</label>
		</form>,
		container
	)
})

export default OnlyInStockProductsSwitcher
