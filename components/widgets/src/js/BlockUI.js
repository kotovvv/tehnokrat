// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

/* global tehnokrat */
import React, { memo } from 'react'
import { createPortal } from 'react-dom'
import Slider from 'react-slick'

const BlockUI = memo(({ element }) => {
	return createPortal(<div style={{
			zIndex: 1000,
			border: 'none',
			margin: 0,
			padding: 0,
			width: '100%',
			height: '100%',
			top: 0,
			left: 0,
			background: 'rgb(255, 255, 255)',
			opacity: 0.6,
			cursor: 'wait',
			position: 'fixed'
		}}>
			<svg style={{
				height: '1em',
				width: '1em',
				display: 'block',
				position: 'absolute',
				top: '50%',
				left: '50%',
				marginLeft: '-0.5em',
				marginTop: '-0.5em',
				content: '',
				animation: 'spin 1s ease-in-out infinite',
				// background: url(../images/icons/loader.svg) center center;
				// background-size: 'cover',
				lineHeight: 1,
				textAlign: 'center',
				fontSize: '2em',
				color: 'rgba(0,0,0,.75)'
			}} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 91.3 91.1">
				<circle cx="45.7" cy="45.7" r="45.7"/>
				<circle fill="#FFF" cx="45.7" cy="24.4" r="12.5"/>
			</svg>
		</div>,
		element || document.body
	)
})

export default BlockUI
