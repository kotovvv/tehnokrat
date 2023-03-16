// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

import React, { useState, useMemo, useEffect } from 'react'

const classNames = require('classnames')

const Selectors = ({
	variationsAttributes,
	selectedAttributesValues,
	setSelectedAttributesValues,
	variationsAttributesTitles
}) => {
	const prepareAttributeForSort = attribute => {
		attribute = tehnokrat.product_attributes.reduce((attribute, attributeData) => {
			return attribute.replace(attributeData.search, attributeData.replace)
		}, attribute.toString())

		return attribute.replace(/[^0-9]/g, '')
	}

	// перечень всех атрибутов и их всех возможный значений для селектора.
	const allAttributesValues = useMemo(() => {
		return variationsAttributes.reduce((accumulator, attributes) => {
			attributes.forEach((value, index) => {
				if (accumulator && accumulator[index]) {
					if (-1 === accumulator[index].indexOf(value)) {
						accumulator[index].push(value)
						accumulator[index].sort((a, b) => {
							return parseInt(prepareAttributeForSort(a)) - parseInt(prepareAttributeForSort(b))
						})
					}
				} else {
					accumulator[index] = [value]
				}
			})

			return accumulator
		}, [])
	}, [variationsAttributes])

	const availableAttributesValues = useMemo(() => {
		let result = []
		let attributeShouldBeUnavailable = 0

		selectedAttributesValues.forEach((attributeValue, attributeName) => {
			result[attributeName] = []

			// Все атрибуты, находящиеся через один от последнего выбранного, должны быть недоступны.
			if (attributeShouldBeUnavailable > 1) {
				return
			}

			// Найден последний выбранный атрибут. Устанавливаю признак недоступности последующих атрибутов.
			if (undefined === attributeValue) {
				attributeShouldBeUnavailable++
			}

			// Перебираю все вариации товара.
			// Просчитываю возможные варианты для атрибута.
			variationsAttributes.forEach(variationAttributes => {
				// Перебираю все атрибуты текущей вариации товара.
				let skipVariation = false
				variationAttributes.forEach((variationAttributeValue, variationAttributeName) => {
					// Искомый атрибут найдем, добавляю значение в перечень доступных значений.
					if (!skipVariation && variationAttributeName === attributeName) {
						if (-1 === result[attributeName].indexOf(variationAttributeValue)) {
							result[attributeName].push(variationAttributeValue)
						}

						skipVariation = true
					}

					// Прекратить дальнейшую обработку атрибутов текущей вариации товара, если атрибут уже выбран
					// пользователем и его значение не совпадает с атрибутом текущей вариации товара.
					if (!skipVariation && selectedAttributesValues[variationAttributeName] !== variationAttributeValue) {
						skipVariation = true
					}
				})
			})
		})

		return result
	}, [variationsAttributes, allAttributesValues, selectedAttributesValues])

	const [clickedElement, setClickedElement] = useState(undefined)

	useEffect(
		() => {
			if (undefined === clickedElement) {
				return
			}

			const [attributeName, value] = clickedElement
			setTimeout(() => setClickedElement(undefined), 100)

			const state = [...selectedAttributesValues]

			let valueWasSet = false

			state.forEach((stateAttributeValue, stateAttributeName) => {
				if (stateAttributeName === attributeName) {
					if (availableAttributesValues[attributeName].includes(value)) {
						state[stateAttributeName] = value
					}

					valueWasSet = true
				} else if (valueWasSet) {
					state[stateAttributeName] = undefined
				}
			})

			setMissingElements(state, attributeName, value)

			setSelectedAttributesValues({ key: 'attributes', value: state })
		},
		[clickedElement]
	)

	const getMatchVariations = state => variationsAttributes.filter(variation => {
		return variation.reduce((accumulator, attribute, index) => {
			if (undefined !== state[index]) {
				accumulator = accumulator && (attribute === state[index])
			}

			return accumulator
		}, true)
	})

	const [missingElements, _setMissingElements] = useState([])
	const setMissingElements = (state, attributeId, value) => {
		let _selectedAttributesValues = Array.from({ length: state.length })
		_selectedAttributesValues[attributeId] = value

		const notMatchAttributes = state.reduce((accumulator, attribute, index) => {
			if (accumulator.flat().length) {
				// console.log('Отличие уже было найдено')
				accumulator[index] = []
				// } else if ( -1 !== state.indexOf(undefined) && index >= state.indexOf(undefined)) {
			} else if (index >= attributeId) {
				// console.log('Аттрибут ниже кликнутого')
				accumulator[index] = []
			} else {
				let attributes = []
				getMatchVariations(_selectedAttributesValues).forEach(variation => {
					if (-1 === attributes.indexOf(variation[index])) {
						attributes.push(variation[index])
					}
				})

				if (attributes.includes(attribute)) {
					// console.log('Аттрибут содержит нужный элемент', attributes, attribute)
					attributes = []
				} else {
					// console.log('Найдено отличие', attributes)
				}
				accumulator[index] = attributes
			}

			_selectedAttributesValues[index] = attribute

			return accumulator
		}, [])

		_setMissingElements(notMatchAttributes)
	}

	return allAttributesValues.map((attributes, i) =>
		<div
			className={attributes[0].startsWith('#') ? 'color' : 'attribute'}
			key={i}
		>
			<h5>{variationsAttributesTitles[i]}</h5>
			<ul className={attributes[0].startsWith('#') ? 'color' : 'attribute'}>
				{attributes.map((value, ii) =>
					<li
						key={ii}
						className={classNames(
							value === selectedAttributesValues[i] ? 'active' : 'inactive',
							availableAttributesValues[i].includes(value) ? 'available' : 'unavailable',
							{ animate: (missingElements.length && missingElements[i].includes(value)) },
						)}
						onAnimationEnd={() => setMissingElements([])}
					>
						<a
							className={classNames(
								{ active: (clickedElement && i === clickedElement[0] && value === clickedElement[1]) }
							)}
							onClick={() => setClickedElement([i, value])}
							style={{ backgroundColor: value.startsWith('#') ? value : 'inherit' }}
						>
							{value.startsWith('#') === false && value}
						</a>
					</li>
				)}
			</ul>
		</div>
	)
}

export default Selectors
