// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

import React, { memo } from 'react'
import ProductImage from './ProductImage'
import MoreDetailed from './MoreDetailed'
import AddToCart from './AddToCart'
import Selectors from './Selectors'
import ModelSelector from './ModelSelector'
import Description from './Description'

const ProductTemplateWithMultipleAttributes = memo(({
	className,
	firstVariation,
	currentVariation,
	productName,
	productIndex,
	inStock,
	variationsAttributes,
	variationsAttributesTitles,
	selectedAttributesValues,
	models,
	selectedModel,
	select,
	availableVariations
}) => {
	return <div className={className}>
		<ProductImage
			label={currentVariation ? currentVariation.label : ''}
			imageSrc={currentVariation ? currentVariation.image : firstVariation.image}
			gallery={currentVariation ? currentVariation.gallery : firstVariation.gallery}
		/>
		<div className="description clearfix">
			<div className="model">
				<ul className="model-item">
					<li>
						{models.length > 0 &&
						<ModelSelector
							models={models}
							selectedModel={selectedModel.current}
							setSelectedModel={select}
						/>
						}
					</li>
					<li>
						<p className="h6">{tehnokrat.strings['Model']}</p>
						<span>{selectedModel.current ? selectedModel.current : '-'}</span>
					</li>
					<li>
						<MoreDetailed
							productName={productName}
							productIndex={productIndex}
							currentVariation={currentVariation}
							variationIndex={0}
							// variationIndex={currentVariationID}
						/>
					</li>
					<li>
						<Description
							description={availableVariations ? availableVariations[0].description2 : ''}
							selectedAttributesValues={selectedAttributesValues.current}
						/>
					</li>
				</ul>
			</div>

			<div className="title">
				<p className="h2">
					{productName}
					{undefined !== currentVariation && currentVariation.modification && currentVariation.modification.length &&
					<span>{currentVariation.modification}</span>}
				</p>
				<MoreDetailed
					productName={productName}
					productIndex={productIndex}
					currentVariation={currentVariation}
					variationIndex={0}
					// variationIndex={currentVariationID}
				/>
				<Selectors
					variationsAttributes={variationsAttributes}
					variationsAttributesTitles={variationsAttributesTitles}
					selectedAttributesValues={selectedAttributesValues.current}
					setSelectedAttributesValues={select}
				/>

				<AddToCart productName={productName} currentVariation={currentVariation} inStock={inStock}/>

			</div>
		</div>
	</div>
})

export default ProductTemplateWithMultipleAttributes
