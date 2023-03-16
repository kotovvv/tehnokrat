// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature
// phpcs:disable WordPress

import React, { useMemo } from 'react'

const Description = ({ description, selectedAttributesValues }) => {
	const selectedDescription = useMemo(() => {
		if (!Array.isArray(description)) {
			return description
		}

		let selectedDescription = ''
		for (let i = 0; i < (description.length); i += 2) {
			selectedDescription += '<li>' + description[i] + '</li>'
			selectedDescription += '<li>' +
				((!selectedAttributesValues.includes(undefined) || selectedAttributesValues[i / 2]) ? description[i + 1] : '-') +
				'</li>'
		}

		return '<ul>' + selectedDescription + '</ul>'
	}, [description, selectedAttributesValues])

	return <React.Fragment>
		<h6>{Object.keys(selectedAttributesValues).length < 2 ? tehnokrat.strings['Description'] : tehnokrat.strings['Configuration']}</h6>
		<div dangerouslySetInnerHTML={{ __html: selectedDescription }}/>
	</React.Fragment>
}

export default Description
