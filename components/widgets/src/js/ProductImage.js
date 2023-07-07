// phpcs:disable Generic.Formatting
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceBeforeCloseBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.CloseBracketLine
// phpcs:disable WordPress.WhiteSpace.OperatorSpacing

import React, { useState } from 'react'
import Part from './Part'
import SliderPopup from './SliderPopup'

const ProductImage = ({ label, imageSrc, gallery, currentVariation }) => {
	const [showGallery, setShowGallery] = useState(false)

	return <>
		<div className="product-img" onClick={() => setShowGallery(true)}>
			<img loading="lazy" decoding="async" alt="" src={imageSrc} />
			{label.length > 0 &&
				<span className="pl" style={{ backgroundColor: tehnokrat.label_colors[label] }}>{label}</span>
			}
			{currentVariation != {} &&
				<Part part={currentVariation.part} partprivat={currentVariation.partprivat} />}
		</div>
		{true === showGallery && <SliderPopup gallery={gallery} closeGallery={() => setShowGallery(false)} />}
	</>
}

export default ProductImage
