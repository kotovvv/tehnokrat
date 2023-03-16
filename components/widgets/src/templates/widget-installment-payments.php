<?php

/**
 * @global array $cities
 * @global array $banks
 * @global string $current_bank_id
 * @global string $alfabank_description
 */

?>

<section class="product_cred_popup">
	<div class="product_popup_content">
		<i class="close"></i>
		<h2>Рассрочка</h2>
		<form class="pop-cont">
			<div class="form">
				<div class="bank-cont">
					<p>Выберите банк:</p>
					<div class="bank">
						<?php foreach ( $banks as $bank ) : ?>
							<div>
								<input
										name="bank"
										type="radio"
									<?php checked( $bank['value'], $current_bank_id ); ?>
										id="<?php echo esc_attr( $bank['value'] ); ?>"
										value="<?php echo esc_attr( $bank['value'] ); ?>"
										data-jcf="{wrapNative: true}"
										onchange="onBankChange()"
								/>
								<label for="<?php echo esc_attr( $bank['value'] ); ?>">
									<?php echo esc_attr( $bank['label'] ); ?>
								</label>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="other">
					<div class="form-item">
						<p>Выберите город:</p>
						<div class="cit">
							<input list="cities" name="city" id="city">
							<datalist id="cities">
								<?php foreach ( $cities as $city ) : ?>
									<option value="<?php echo esc_attr( $city ); ?>">
								<?php endforeach; ?>
							</datalist>
						</div>
					</div>
					<div class="form-item">
						<div class="month">
							<span class="range-p">Количество платежей: </span>
							<div class="range">
								<?php for ( $i = 2; $i <= 10; $i ++ ) : ?>
									<div>
										<input
												type="radio"
												name="month"
												id="m<?php echo esc_attr( $i ); ?>"
												onclick="onPartsCountChange()"
												value="<?php echo esc_attr( $i ); ?>"
											<?php checked( $i, 2 ); ?>
										>
										<label for="m<?php echo esc_attr( $i ); ?>"><?php echo esc_attr( $i ); ?></label>
									</div>
								<?php endfor; ?>
							</div>
							<span class="kolvo">Ежемесячный платеж:&nbsp;</span>
							<p class="priceinmonth"><span>2300 грн</span> в месяц</p>
						</div>
					</div>
					<div class="form-item">
						<p>Контактная информация:</p>
						<div class="pers-inf">
							<input name="fio" type="text" placeholder="Фамилия Имя" required>
							<input name="tel" type="tel" placeholder="Телефон" required>
							<input name="email" type="email" placeholder="E-mail" required>
						</div>
					</div>
				</div>
				<iframe style="height:100%; width:100%" frameborder="0" src=""></iframe>
			</div>
			<div class="info">
				<img src="../img/MACBOOKPRO.png" alt="">
				<p class="model">Macbook PRO Retina, 15-inch, Mid 2015</p>
				<div>
					<div class="numb">
						<input
								type="hidden"
								value="1"
								min="1"
								max="200"
								id="quantity"
								name="quantity"
								onchange="calculateTotal()"
						/>
					</div>
				</div>
				<p class="alfabank-description">
					<?php echo $alfabank_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>

				<p class="last-price">Итого: <span>16 193 грн</span></p>
				<input type="hidden" name="product-id">
				<input type="hidden" name="monthly-payment">
				<input type="hidden" name="total">
				<input type="submit" value="Отправить заявку">
			</div>
		</form>
	</div>
</section>
