// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceBeforeCloseBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.CloseBracketLine
// phpcs:disable WordPress.WhiteSpace

import React, { useState } from 'react'
import { createPortal } from 'react-dom'

const NotifyProductInStockPopup = ({ productName, currentVariation, closePopup }) => {
	const headerHeight = jQuery(window).width() < 768 ? jQuery('header').height() : 0

	const [result, setResult] = useState(undefined)

	const [deliveryCity, setDeliveryCity] = useState('')
	const [name, setName] = useState('')
	const [phone, setPhone] = useState('')

	const close = () => {
		setResult(undefined)
		closePopup()
	}

	const createPreOrder = e => {
		e.preventDefault()

		const params = new URLSearchParams({
			// 'XDEBUG_SESSION_START': 'PHPSTORM',
			'action': 'tehnokrat_create_pre_order',
			'product_id': currentVariation.id,
			'deliveryCity': deliveryCity,
			'name': name,
			'phone': phone
		})
		const request = new XMLHttpRequest()
		request.open('POST', tehnokrat.ajax_url)
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
		request.send(params.toString())
		request.onload = () => {
			if (request.status !== 200) {
				alert(wp.i18n.__('Something went wrong. Please repeat your request.', 'tehnokrat'))
			} else {
				const response = JSON.parse(request.response)

				if (response.success) {
					setResult(true)
				} else {
					alert(response.data)
				}
			}
		}
		request.onerror = () => {
			alert(wp.i18n.__('Something went wrong. Please repeat your request.', 'tehnokrat'))
		}
	}
	useEffect(() => {
		// Добавление классов
		document.getElementsByTagName('body')[0].classList.add('popup');
		document.getElementsByClassName('wrapper')[0].classList.add('popup');

		// Функция очистки эффекта
		return () => {
			// Удаление классов
			document.getElementsByTagName('body')[0].classList.remove('popup');
			document.getElementsByClassName('wrapper')[0].classList.remove('popup');
		};
	}, []);

	return createPortal(<>
		{undefined === result &&
			<section className="product_pred_popup active">
				<div className="product_pred_popup_content"
					style={{ paddingTop: headerHeight + 20 + 'px' }}>
					<i className="close" onClick={close} style={{ top: headerHeight + 10 + 'px' }} />
					<p className="h2">{wp.i18n.__('Report Admission', 'tehnokrat')}</p>
					<p className="h3">{productName}</p>
					{/* <p>{currentVariation.title1}</p> */}
					<form onSubmit={createPreOrder}>
						<div className="item">
							<div className="for-img">
								<img loading="lazy" decoding="async" src={currentVariation.image} alt="" />
								<div className="op">
									<p>
										{wp.i18n.__('As soon as the item is in stock, we will send you a message.', 'tehnokrat')}
									</p>
								</div>
							</div>
							<div className="about-item">
								<div className="form">
									<input
										type="text"
										placeholder={wp.i18n.__('City', 'tehnokrat')}
										value={deliveryCity}
										onChange={({ target }) => setDeliveryCity(target.value)}
										required
									/>
									<input
										type="text"
										placeholder={wp.i18n.__('Full name', 'tehnokrat')}
										value={name}
										onChange={({ target }) => setName(target.value)}
										required
									/>
									<input
										className="number"
										type="tel"
										placeholder={wp.i18n.__('Phone number', 'tehnokrat')}
										value={phone}
										onChange={({ target }) => setPhone(target.value)}
										required
									/>
								</div>
							</div>
							<div className="buttons">
								<div className="lb">
									<a className="back" onClick={close}>
										<i className="icon-left-open-big" />{wp.i18n.__('Back', 'tehnokrat')}
									</a>
								</div>
								<div className="bb">
									<input type="submit" value={wp.i18n.__('Report Admission', 'tehnokrat')} />
								</div>
							</div>
						</div>
					</form>
				</div>
			</section>
		}
		{true === result &&
			<section className="thank_popup active">
				<div className="thank_popup_content clearfix">
					<i className="close" onClick={close} />
					<h3>{wp.i18n.__('Thanks for your order', 'tehnokrat')}</h3>
					<p>{wp.i18n.__('We will contact you as soon as the item is available.', 'tehnokrat')}</p>
				</div>
			</section>
		}
	</>,
		document.body
	)
}

export default NotifyProductInStockPopup
