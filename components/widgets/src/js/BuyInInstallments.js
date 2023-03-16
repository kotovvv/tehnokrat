// phpcs:disable Generic.Formatting
// phpcs:disable Generic.WhiteSpace
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.SpaceBeforeCloseBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket
// phpcs:disable PEAR.Functions.FunctionCallSignature.CloseBracketLine
// phpcs:disable WordPress.WhiteSpace

import React, { useState, memo, useRef, useMemo } from 'react'
import { createPortal } from 'react-dom'
import BlockUI from './BlockUI'

const RadioButton = memo(({ state, setState, id, name, value, label }) => {
	const isActive = (state === value)

	return <div onClick={() => setState(value)}>
		<span className={'jcf-radio ' + (isActive ? 'jcf-checked' : 'jcf-unchecked')}>
			<input
				type="radio"
				name={name}
				id={id}
				defaultChecked={isActive}
				style={{ position: 'absolute', opacity: 0 }}
			/>
		</span>
		<label htmlFor={'m' + id} className={isActive ? 'jcf-label-active' : ''}>{label}</label>
	</div>
})

const BuyInInstallments = memo(({ currentVariation, closePopup }) => {
	const sloncreditApiUrl = useMemo(() => {
			const searchParams = new URLSearchParams({
				n: currentVariation.title1,
				q: 1,
				p: currentVariation.priceUAH,
				i: currentVariation.image,
				pu: currentVariation.url
			})

			return 'https://api.sloncredit.com.ua/pos/iframe?c=306&' + searchParams.toString()
		},
		[currentVariation]
	)
	const cities = useRef(tehnokrat.cities).current
	const [city, setCity] = useState('')

	const [selectedBank, selectBank] = useState('slon')
	const [numberOfPayments, setNumberOfPayments] = useState(2)

	const monthlyPayment = useMemo(() => {
			let monthlyPayment = currentVariation.priceUAH / numberOfPayments

			if ('alf' === selectedBank && numberOfPayments > 5) {
				monthlyPayment *= 1.05
			}

			return Math.ceil(monthlyPayment)
		},
		[currentVariation, numberOfPayments, selectedBank]
	)

	const total = useMemo(() => monthlyPayment * numberOfPayments, [monthlyPayment, numberOfPayments])

	const [fio, setFio] = useState('')
	const [tel, setTel] = useState('')
	const [email, setEmail] = useState('')

	const [blockUI, setBlockUI] = useState(false)

	const onSubmit = e => {
		e.preventDefault()

		setBlockUI(true)

		const params = new URLSearchParams({
			nonce: tehnokrat.nonce,
			action: 'create_order_in_installments',
			'product-id': currentVariation.id,
			'fio': fio,
			'tel': tel,
			'email': email,
			'bank': selectedBank,
			'city': city,
			'quantity': 1,
			'input_con': numberOfPayments,
			'monthly-payment': monthlyPayment,
			'total': total,
			'XDEBUG_SESSION_START': 'PHPSTORM'
		})
		const request = new XMLHttpRequest()
		request.open('POST', tehnokrat.ajax_url)
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
		request.send(params.toString())
		request.onload = () => {
			setBlockUI(false)

			if (request.status !== 200) {
				alert('Что-то пошло не так. Повторите, пожалуйста, запрос.')
			} else {
				const response = JSON.parse(request.response)

				if (response.success) {
					if ('alf' === selectedBank) {
						window.location.href = response.data
					}
				} else {
					alert(response.data)
				}
			}
		}
		request.onerror = () => {
			alert('Что-то пошло не так.. Повторите, пожалуйста, запрос.')
		}
	}

	return createPortal(<>
			{true === blockUI && <BlockUI/>}
			<section className="product_cred_popup active">
				<div className="product_popup_content">
					<i className="close" onClick={closePopup}/>
					<h2>{tehnokrat.strings['Installment plan']}</h2>
					<form className="pop-cont" onSubmit={onSubmit}>
						<div
							className="form"
							style={{
								flexBasis: 'alf' === selectedBank ? '65%' : '100%',
								minHeight: 'alf' === selectedBank ? 'auto' : '50vh',
							}}
						>
							<div className="bank-cont">
								<p>{tehnokrat.strings['Choose a bank']}:</p>
								<div className="bank">
{/*
									<RadioButton
										state={selectedBank}
										setState={selectBank}
										id="alf"
										name="bank"
										value="alf"
										label="АльфаБанк"
									/>
*/}
									<RadioButton
										state={selectedBank}
										setState={selectBank}
										id="slon"
										name="bank"
										value="slon"
										label="SlonCredit"
									/>
								</div>
							</div>
							{'alf' === selectedBank &&
							<div className="other">
								<div className="form-item">
									<p>Выберите город:</p>
									<div className="cit">
										<input list="cities" id="city" value={city}
											   onChange={({ target }) => setCity(target.value)}/>
										<datalist id="cities">
											{cities.map((city, index) => <option key={index} value={city}/>)}
										</datalist>
									</div>
								</div>
								<div className="form-item">
									<div className="month">
										<span className="range-p">Количество платежей: </span>
										<div className="range">
											{[2, 3, 4, 5, 6, 7, 8, 9, 10].map(i => {
												return <RadioButton
													key={i}
													state={numberOfPayments}
													setState={setNumberOfPayments}
													id={'m' + i}
													name="month"
													value={i}
													label={i}
												/>
											})}
										</div>
										<span className="kolvo">Ежемесячный платеж:&nbsp;</span>
										<p className="priceinmonth">
											<span>{monthlyPayment.toLocaleString('ru-RU')}</span> грн в месяц
										</p>
									</div>
								</div>
								<div className="form-item">
									<p>Контактная информация:</p>
									<div className="pers-inf">
										<input
											type="text"
											placeholder="Фамилия Имя"
											required
											value={fio}
											onChange={({ target }) => setFio(target.value)}
										/>
										<input
											type="tel"
											placeholder="Телефон"
											required
											value={tel}
											onChange={({ target }) => setTel(target.value)}
										/>
										<input
											type="email"
											placeholder="E-mail"
											value={email}
											onChange={({ target }) => setEmail(target.value)}
										/>
									</div>
								</div>
							</div>}
							{'slon' === selectedBank &&
							<iframe style={{ height: '100%', width: '100%' }} frameBorder="0"
									src={sloncreditApiUrl}/>}
						</div>
						{'alf' === selectedBank &&
						<div className="info">
							<img src={currentVariation.image} alt=""/>
							<p className="model">{currentVariation.title1}</p>
							<div>
								<div className="numb">
									<input
										type="hidden"
										value="1"
										min="1"
										max="200"
										id="quantity"
										name="quantity"
									/>
								</div>
							</div>
							<p className="alfabank-description">
								Возраст клиента: от 21 до 70 лет<br/>
								Процентная ставка: 0,01%<br/>
								Сумма кредита: до 100 000 грн.<br/>
								Ежемесячная комиссия: 0,00%<br/>
								Одноразовая комиссия: 0,00%<br/>
								Первый взнос: от 0,00%<br/>
								Страхование: до 5 мес. 0%<br/>
								С 6 мес., единоразовая комиссия 5%
							</p>
							<p className="last-price">Итого: <span>{total.toLocaleString('ru-RU')} грн</span></p>
							<input type="submit" value="Отправить заявку"/>
						</div>}
					</form>
				</div>
			</section>
		</>,
		document.body
	)
})

export default BuyInInstallments
