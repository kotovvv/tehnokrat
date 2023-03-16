// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

/* global tehnokrat */
import React, { memo } from 'react'
import { createPortal } from 'react-dom'
import Slider from 'react-slick'

const SliderPopup = memo(({ gallery, closeGallery }) => {
	return createPortal(<section className="slider_popup active myslider">
			<div className="slider_popup_content clearfix">
				<i className="close" onClick={closeGallery}/>
				<div className="slider">
					<Slider {...{
						dots: true,
						infinite: true,
						slidesToShow: 1,
						slidesToScroll: 1,
						nextArrow: <div><img src="/wp-content/themes/tehnokrat/img/Arrow.png" alt=""/></div>,
						prevArrow: <div><img src="/wp-content/themes/tehnokrat/img/Arrow.png" alt=""/></div>,
					}}>
						{gallery.map((slide, index) => {
							return <div key={index}>
								<img src={slide} alt={slide}/>
							</div>
						})}
					</Slider>
				</div>
			</div>
		</section>,
		document.body
	)
})

export default SliderPopup
