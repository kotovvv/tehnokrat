// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

import React, { memo, useMemo, useReducer, useRef, useState } from 'react'
import ProductTemplateWithoutAttributes from './ProductTemplateWithoutAttribures'
import ProductTemplateWithOneAttribute from './ProductTemplateWithOneAttribute'
import ProductTemplateWithMultipleAttributes from './ProductTemplateWithMultipleAttributes'

const classNames = require('classnames')

const Product = memo(({ productIndex, productName, inStock, variations, align }) => {
	const lowestPriceVariation = useMemo(() => {
		let _lowestPriceVariation = variations.reduce((_variation, variation) => {
			const price = (undefined === _variation) ? 1000000 : _variation.priceUAH

			return (1 === variation.in_stock && variation.priceUAH < price) ? variation : _variation
		}, undefined)

		return (undefined === _lowestPriceVariation) ? variations[0] : _lowestPriceVariation
	}, [variations])

	const variationsAttributesTitles = useRef(
		Array.isArray(variations[0].description2)
			? variations[0].description2.filter((title, index) => (index % 2 === 0))
			: [tehnokrat.strings['color']]
	).current

	const selectedAttributesValues = useRef(lowestPriceVariation.attributes2)
	const setSelectedAttributesValues = value => selectedAttributesValues.current = value
	const selectedModel = useRef(lowestPriceVariation.model)
	const setSelectedModel = value => selectedModel.current = value
	const [selected, select] = useReducer((prevSelected, data) => {
		if ('model' === data.key) {
			setSelectedModel(data.value)
		} else {
			setSelectedAttributesValues(data.value)
			setSelectedModel(undefined)
		}
		return data
	}, { key: 'model', value: selectedModel.current })

	const _inStock = useRef(inStock)
	if (_inStock.current !== inStock) {
		setSelectedModel(lowestPriceVariation.model)
		setSelectedAttributesValues(lowestPriceVariation.attributes2)
		_inStock.current = inStock
	}

	const availableVariations = useMemo(() => {
		return variations.filter(variation => {
			if ('model' === selected.key) {
				return variation.model === selectedModel.current
			}

			for (let i = 0; i < variation.attributes2.length; i++) {
				if (
					undefined !== selectedAttributesValues.current[i]
					&&
					variation.attributes2[i] !== selectedAttributesValues.current[i]
				) {
					return false
				}
			}

			return true
		})
	}, [variations, selected, selectedModel, selectedAttributesValues])

	const currentVariation = useMemo(() => {
		if (1 === availableVariations.length) {
			if ('model' === selected.key) {
				setSelectedAttributesValues(availableVariations[0].attributes2)
				return availableVariations[0]
			} else if (!selectedAttributesValues.current.includes(undefined)) {
				setSelectedModel(availableVariations[0].model)
				return availableVariations[0]
			}
		} else {
			return undefined
		}
	}, [selected, availableVariations, selectedAttributesValues])

	/*
		const currentVariationID = useMemo(() => {
			let id = 0

			variations.filter((variation, index) => {
				if (0 !== id) {
					return false
				}

				const variationAttributeNames = Object.keys(variation.attributes)
				const selectedAttributeNames = Object.keys(selectedAttributesValues.current)

				if (variationAttributeNames.length !== selectedAttributeNames.length) {
					return false
				}

				for (let variationAttributeName of variationAttributeNames) {
					if (variation.attributes[variationAttributeName] !== selectedAttributesValues.current[variationAttributeName]) {
						return false
					}
				}

				id = index

				return true
			})

			return id
		}, [selectedAttributesValues])
	*/
	/**********************************************************************************************************
	 * Аттрибуты
	 *********************************************************************************************************/
		// перечень атрибутов и их значений для каждой вариации товара.
	const variationsAttributes = useMemo(() => {
			return variations.map(variation => variation.attributes2)
		}, [variations])

	/**********************************************************************************************************
	 * Модели
	 *********************************************************************************************************/
		// перечень моделей для селектора.
	const models = useMemo(() => {
			return variations.reduce((accumulator, variation, index) => {
				if (variation.is_featured) {
					accumulator[index] = variation.model
				}

				return accumulator
			}, [])
		}, [variations])

	const className = classNames(
		'items',
		'clearfix',
		align,
		{
			'not-available': (currentVariation && 0 === currentVariation.in_stock),
			'not-active': (undefined === currentVariation)
		}
	)

	switch (variations[0].attributes2.length) {
		case 0:
			return <ProductTemplateWithoutAttributes
				className={className}
				currentVariation={currentVariation}
				productName={productName}
				productIndex={productIndex}
				inStock={inStock}
			/>
		case 1:
			return <ProductTemplateWithOneAttribute
				className={className}
				currentVariation={currentVariation}
				productName={productName}
				productIndex={productIndex}
				inStock={inStock}
				variationsAttributes={variationsAttributes}
				variationsAttributesTitles={variationsAttributesTitles}
				selectedAttributesValues={selectedAttributesValues}
				select={select}
				firstVariation={availableVariations[0]}
			/>
		default:
			return <ProductTemplateWithMultipleAttributes
				className={className}
				currentVariation={currentVariation}
				productName={productName}
				productIndex={productIndex}
				inStock={inStock}
				variationsAttributes={variationsAttributes}
				variationsAttributesTitles={variationsAttributesTitles}
				selectedAttributesValues={selectedAttributesValues}
				select={select}
				firstVariation={availableVariations[0]}
				models={models}
				selectedModel={selectedModel}
				availableVariations={availableVariations}
			/>
	}
})

export default Product
