// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

import React, { memo } from 'react'
import ProductImage from './ProductImage'
import MoreDetailed from './MoreDetailed'
import AddToCart from './AddToCart'

const ProductTemplateWithoutAttributes = memo(({
	className,
	currentVariation,
	productName,
	productIndex,
	inStock
}) => {
	return <div className={className}>
		<ProductImage
			label={currentVariation.label}
			imageSrc={currentVariation.image}
			gallery={currentVariation.gallery}
			currentVariation={currentVariation}
		/>
		<div className="description clearfix">
			<div className="title-accses">
				<div className="accses">
					<p className="h4">
						{productName}
						{currentVariation.modification && currentVariation.modification.length &&
							<span>{currentVariation.modification}</span>}
					</p>
					<MoreDetailed
						productName={productName}
						productIndex={productIndex}
						currentVariation={currentVariation}
						variationIndex={0}
					// variationIndex={currentVariationID}
					/>
					<div>
						<p className="h6">{tehnokrat.strings['Description']}</p>
						<p>
							<span dangerouslySetInnerHTML={{ __html: currentVariation.description2 }} />
							<MoreDetailed
								productName={productName}
								productIndex={productIndex}
								currentVariation={currentVariation}
								variationIndex={0}
							// variationIndex={currentVariationID}
							/>
						</p>
					</div>
				</div>

				<AddToCart productName={productName} currentVariation={currentVariation} inStock={inStock} />

			</div>
		</div>
	</div>
})

export default ProductTemplateWithoutAttributes
