// phpcs:disable Generic.Formatting
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceBeforeCloseBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.CloseBracketLine
// phpcs:disable WordPress.WhiteSpace.OperatorSpacing

import React, { useState } from 'react'
import SliderPopup from './SliderPopup'

const ProductImage = ({ label, imageSrc, gallery }) => {
	const [showGallery, setShowGallery] = useState(false)

	return <>
		<div className="product-img" onClick={() => setShowGallery(true)}>
			<img loading="lazy" decoding="async" alt="" src={imageSrc}/>
			{label.length > 0 &&
				<span className="pl" style={{backgroundColor: tehnokrat.label_colors[label]}}>{label}</span>
			}
		</div>
		{true === showGallery && <SliderPopup gallery={gallery} closeGallery={() => setShowGallery(false)}/>}
	</>
}

export default ProductImage
