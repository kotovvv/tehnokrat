// phpcs:disable Generic.Formatting
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

import React from 'react'

const classNames = require('classnames')

const ModelSelector = ({ models, selectedModel, setSelectedModel }) => {
	return <>
		<h6>{tehnokrat.strings['Choose a model']}</h6>
		<ul>
			{models.map((model, index) => {
				return <li className={classNames({ active: model === selectedModel })} key={index}>
					<a onClick={() => model !== selectedModel && setSelectedModel({
						key: 'model',
						value: model
					})}>{model}</a>
				</li>
			})}
		</ul>
	</>
}

export default ModelSelector
