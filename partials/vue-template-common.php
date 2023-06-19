<?php

use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;

/**
 * @global Tehnokrat $tehnokrat
 */
global $tehnokrat;

global $wp_query;
?>

<!-- выбор. начало -->
<script type="text/x-template" id="tehnokrat-selector">
	<div :class="attribute">
		<h5>{{ title }}</h5>
		<ul :class="attribute">
			<li
				v-for="(item, index) in items"
				:class="[ { active: ( value == item ) }, _.includes( availableItems, item ) ? 'available' : 'unavailable' ]"
				:key="1 + index"
			>
				<a href="javascript:void(0)" @click="selectVariation( { [ attribute ]: item } )">{{ item }}</a>
			</li>
		</ul>
	</div>
</script>
<!-- выбор. конец -->


<!-- выбор цвета. начало -->
<script type="text/x-template" id="tehnokrat-color-selector">
	<div class="color">
		<h5>{{ title }}</h5>
		<ul class="color">
			<li
				v-for="(item, index) in items"
				:class="[ { active: ( value == item ) } ]"
				:key="1 + index"
			>
				<a :style="{ 'background-color': item }" href="javascript:void(0)" @click="selectVariation( { [ attribute ]: item } )"></a>&nbsp;
			</li>
		</ul>
	</div>
</script>
<script type="text/x-template" id="tehnokrat-color-selector-2">
	<div class="color">
		<h5>{{ title }}</h5>
		<ul class="color">
			<li
				v-for="(item, index) in items"
				:class="[ { active: ( value == item ) }, _.includes( availableItems, item ) ? 'available' : 'unavailable' ]"
				:key="1 + index"
			>
				<a :style="{ 'background-color': item }" href="javascript:void(0)" @click="selectVariation( { [ attribute ]: item } )"></a>&nbsp;
			</li>
		</ul>
	</div>
</script>
<!-- выбор цвета. конец -->


<!-- выбор модели. начало -->
<script type="text/x-template" id="models-selector">
	<li v-if="items.length">
		<h6>ВЫБЕРИТЕ МОДЕЛЬ</h6>
		<ul>
			<li :class="{active: (value == item)}" v-for="item in items">
				<a href="javascript:void(0)" @click="selectVariation( item )">{{ item }}</a>
			</li>
		</ul>
	</li>
</script>
<!-- выбор модели. конец -->

<!-- кнопка подробнее. начало -->
<script type="text/x-template" id="more-detailed">
	<li>
		<a class="more-prod-but" href="javascript:void(0)" @click="goToProductPage">
			<span>подробнее</span>
			<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.8082 5.53363L7.47457 1.19991C7.35086 1.0762 7.18599 1.0083 7.01018 1.0083C6.83419 1.0083 6.66941 1.0763 6.5457 1.19991L6.15224 1.59347C6.02863 1.71698 5.96053 1.88195 5.96053 2.05785C5.96053 2.23366 6.02863 2.40419 6.15224 2.5277L8.68041 5.06144H0.648287C0.286144 5.06144 0 5.34495 0 5.70719V6.26357C0 6.62582 0.286144 6.93791 0.648287 6.93791H8.7091L6.15234 9.48579C6.02873 9.6095 5.96063 9.76999 5.96063 9.94589C5.96063 10.1216 6.02873 10.2844 6.15234 10.408L6.5458 10.8003C6.6695 10.924 6.83428 10.9914 7.01028 10.9914C7.18609 10.9914 7.35096 10.9231 7.47467 10.7994L11.8083 6.46582C11.9323 6.34172 12.0005 6.17606 12 5.99997C12.0004 5.82329 11.9323 5.65753 11.8082 5.53363Z" fill="white"></path></svg>
		</a>
	</li>
</script>
<!-- кнопка подробнее. конец -->

<!-- ярлык. начало -->
<script type="text/x-template" id="product-label">
    <span v-if="label.length" class="pl" :style="{background: style}">{{label}}</span>
</script>
<!-- ярлык. конец -->

<!-- добавить в корзину. начало -->
<script type="text/x-template" id="add-to-cart">
	<div class="sum-link clearfix">
		<div class="link w-cr">
			<template v-if="$parent.inStock">
				<a href="javascript:void(0)"
                   v-bind:class="[{ hid: isBuyInInstallmentsVisible }, 'buy']"
                   @click="showProductCard"
                ><p>Купить товар</p></a>
            </template>
			<a v-else class="buy_pred" href="javascript:void(0)" @click="showProductCard">Сообщить о поступлении</a>
			<p>11111111111111111111111111</p>
		</div>
		<a v-if="$parent.currentVariation.tradeIn" class="trade-but" href="/trade-in/">
			Trade-in
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="912.193px" height="912.193px" viewBox="0 0 912.193 912.193" style="enable-background:new 0 0 912.193 912.193;" xml:space="preserve">
				<g>
					<path d="M807.193,170.092v83.973c-6.033-10.458-12.529-20.674-19.512-30.606c-24.436-34.762-54.037-65.357-87.984-90.937   c-34.352-25.885-72.34-46.014-112.908-59.827c-41.957-14.286-85.891-21.529-130.577-21.529c-46.663,0-92.432,7.883-136.03,23.431   c-42.135,15.025-81.295,36.846-116.393,64.858c-34.751,27.735-64.539,60.747-88.534,98.119   c-24.444,38.072-42.191,79.621-52.748,123.492c-6.783,28.19,10.57,56.542,38.761,63.325c4.128,0.993,8.259,1.469,12.325,1.469   c23.705,0,45.21-16.167,51-40.229c15.47-64.292,52.651-122.573,104.694-164.109c26.001-20.751,54.989-36.909,86.16-48.024   c32.249-11.5,66.151-17.331,100.765-17.331c65.672,0,128.018,20.822,180.297,60.214c35.375,26.656,64.541,61.161,85.139,100.095   h-58.166c-28.994,0-52.5,23.505-52.5,52.5s23.506,52.5,52.5,52.5h196.211c28.996,0,52.5-23.505,52.5-52.5V170.092   c0-28.995-23.504-52.5-52.5-52.5C830.699,117.592,807.193,141.097,807.193,170.092z"/>
					<path d="M52.5,794.602c28.995,0,52.5-23.504,52.5-52.5v-84.326c31.275,54.438,74.821,100.955,127.654,135.994   c66.246,43.936,143.417,67.186,223.196,67.254c0.044,0,0.087,0.004,0.13,0.004c0.035,0,0.071-0.002,0.106-0.002   c0.041,0,0.083,0.002,0.124,0.002c0.056,0,0.109-0.004,0.166-0.004c46.524-0.045,92.157-7.924,135.633-23.428   c42.135-15.025,81.295-36.846,116.393-64.857c34.752-27.734,64.539-60.748,88.535-98.119   c24.443-38.072,42.191-79.621,52.748-123.492c6.783-28.189-10.57-56.541-38.762-63.324s-56.541,10.57-63.324,38.76   c-15.471,64.293-52.652,122.574-104.695,164.109c-26,20.75-54.988,36.91-86.16,48.023c-32.217,11.488-66.082,17.318-100.657,17.33   c-59.154-0.023-116.346-17.229-165.398-49.762c-42.3-28.053-76.562-66.006-100.007-110.545h58.028c28.996,0,52.5-23.506,52.5-52.5   c0-28.996-23.505-52.5-52.5-52.5H52.5c-28.995,0-52.5,23.504-52.5,52.5v198.883C0,771.098,23.505,794.602,52.5,794.602z"/>
				</g>
			</svg>
		</a>
		<div class="sum">
			<p>{{ $parent.priceUAH.format( 0, 3, ' ', '.' ) }}<span> грн</span></p>
			<span>${{ $parent.priceUSD.format( 0, 3, ' ', '.' ) }}</span>
			<template v-if="!$root.onlyInStockProducts">
				<i v-if="$parent.inStock">Товар в наличии</i>
				<i v-else>Нет в наличии</i>
			</template>
		</div>
	</div>
</script>
<!-- добавить в корзину. конец -->

<script type="text/x-template" id="tehnokrat-cart-old">
	<section class="up_order">
		<div class="up_order_content clearfix">
			<div v-if="selectedProduct.currentVariation" class="product">
				<i class="close" @click="close"></i>
				<img loading="lazy" decoding="async" :src="selectedProduct.currentVariation.image" alt="">
				<h2>{{ selectedProduct.product.name }}<span>{{ selectedProduct.currentVariation.modification }}</span></h2>
				<p>Модель</p><span>{{ selectedProduct.currentVariation.model }}</span></li>
				<div v-if="selectedProduct.currentVariation" v-html="selectedProduct.currentVariation.description"></div>
				<div class="sum">
					<p v-if="selectedProduct.price">{{ priceUAH.format( 0, 3, ' ', '.' ) }}<span> грн</span></p>
					<span v-if="selectedProduct.price">${{ price.format( 2, 3, ' ', '.' ) }}</span>
				</div>
			</div>
			<form v-on:submit.prevent="createOrder">
				<div v-show="!selectedProduct.inStock">
					<h4>Оформить предварительный заказ</h4>
				</div>
				<div v-show="selectedProduct.inStock">
					<h4>Оформление заказа</h4>
					<select v-model="paymentMethod" data-jcf='{"wrapNative": false, "wrapNativeOnMobile": false}'>
						<option disabled value="">Выберите способ оплаты</option>
						<option value="liqpay">Visa / MasterCard</option>
						<option value="cash">Наличные</option>
					</select>
					<select v-model="shippingMethod" data-jcf='{"wrapNative": false, "wrapNativeOnMobile": false}'>
						<option disabled value="">Выберите способ доставки</option>
						<option value="pickup">Самовывоз</option>
						<option value="novaposhta">Новая Почта</option>
					</select>
				</div>
				<div class="compulsory">
					<input
						v-model="deliveryCities"
						:class="[ { 'error': !deliveryCities.length } ]"
						type="text"
						list="delivery_cities"
						placeholder="Город"
					/>
					<span></span>
				</div>
				<datalist id="delivery_cities">
					<?php
						if ( is_array(
							$delivery_cities = $tehnokrat->get_setting( 'delivery_cities', 'delivery_cities' )
						) ) {
							foreach ( $delivery_cities as $city ) {
								echo '<option value="' . $city . '">' . $city . '</option>';
							}
						}
					?>
				</datalist>
				<div class="compulsory">
					<input
						v-model="name"
						:class="[ { 'error': !name.length } ]"
						type="text"
						placeholder="Имя Фамилия"
					/>
					<span></span>
				</div>
				<div class="compulsory">
					<input
						v-model="phone"
						:class="[ 'phone-number', { 'error': 17 !== phone.length } ]"
						type="tel"
						v-mask="phoneMask"
						placeholder="Ваш номер телефона"
					/>
					<span></span>
				</div>
				<input v-model="email" type="email" placeholder="E-mail">
				<div class="g-recaptcha" data-sitekey="<?php $tehnokrat->get_setting( 'recaptcha', 'site_key' ); ?>"></div>
				<input
					type="submit"
					value="Оформить заказ"
					:class="{ 'error': (17 !== phone.length || !deliveryCities.length || !name.length ) }"
					:disabled="17 !== phone.length"
				/>
			</form>
		</div>
	</section>
</script>

<?php if (
	! empty( $wp_query ) && ! empty( $wp_query->queried_object )
	&&
	isset( $wp_query->queried_object->term_id, $wp_query->queried_object->parent )
) {
	$is_accessory = in_array( 621, array( $wp_query->queried_object->term_id, $wp_query->queried_object->parent ) );
} else {
	$is_accessory = false;
}?>
<script type="text/x-template" id="product-card">
	<section class="product_popup">
		<div class="ok_popup_content">
			<h3>Товар добавлен в корзину!</h3>
		</div>
		<div v-if="selectedProduct.currentVariation" class="product_popup_content" v-bind:style="{ 'padding-top': headerHeight + 20 + 'px' }">
			<i class="close" @click="close" v-bind:style="{ 'top': headerHeight + 10 + 'px' }"></i>
			<h3>{{ selectedProduct.product.name }}</h3>
			<p>{{ selectedProduct.currentVariation.title1 }}</p>
			<div class="item <?php echo $is_accessory ? 'acc' : ''; ?>">
				<div class="for-img">
					<img loading="lazy" decoding="async" :src="selectedProduct.currentVariation.image" alt="">
				</div>
				<div class="about-item <?php echo $is_accessory ? 'acc' : ''; ?>">
					<ul class="price">
						<li>
							<p v-if="selectedProduct.priceUAH" class="pr-grn">{{ priceUAH.format( 0, 3, ' ', '.' ) }} грн</p>
						</li>
						<li>
							<p v-if="selectedProduct.priceUSD" class="pr-dol">${{ priceUSD.format( 2, 3, ' ', '.' ) }}</p>
						</li>
					</ul>
					<div class="param">
						<ul><li>Модель</li><li>{{ selectedProduct.currentVariation.model }}</li></ul>
						<div v-if="selectedProduct.currentVariation" v-html="selectedProduct.currentVariation.description"></div>
					</div>
				</div>
				<div class="buttons">
					<div class="lb">
						<a class="back ba" href="<?php echo esc_url( wc_get_cart_url() ); ?>">Перейти в корзину<i class="icon-right-open-big"></i></a>
					</div>
					<div class="bb">
						<?php if (
							! empty( $wp_query ) && ! empty( $wp_query->queried_object )
							&&
							isset( $wp_query->queried_object->term_id, $wp_query->queried_object->parent )
							&&
							in_array( 609, array( $wp_query->queried_object->term_id, $wp_query->queried_object->parent ) )
						) : ?>
							<a class="tr" href="<?php echo site_url('trade-in'); ?>" target="_blank">MAC TRADE-IN</a>
						<?php endif; ?>
						<a class="add" href="javascript:void(0)" @click="addToCart">Добавить в корзину</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</script>

<script type="text/x-template" id="product_pred_popup">
	<section class="product_pred_popup">
		<div v-if="selectedProduct.currentVariation" class="product_pred_popup_content" v-bind:style="{ 'padding-top': headerHeight + 20 + 'px' }">
			<i class="close" @click="close" v-bind:style="{ 'top': headerHeight + 10 + 'px' }"></i>
			<h2>Сообщить о поступлении</h2>
			<h3>{{ selectedProduct.product.name }}</h3>
			<p>{{ selectedProduct.currentVariation.title1 }}</p>
			<form @submit.prevent="createPreOrder">
				<div class="item">
					<div class="for-img">
						<img loading="lazy" decoding="async" :src="selectedProduct.currentVariation.image" alt="">
						<div class="op">
							<p>Как только товар будет в наличии, мы отправим Вам сообщение</p>
						</div>
					</div>
					<div class="about-item">
						<div class="form">
							<input type="text" placeholder="Город" v-model="deliveryCity" required />
							<input type="text" placeholder="ФИО" v-model="name" required />
							<input type="tel" placeholder="Номер телефона" v-model="phone" class="number" required />
						</div>
					</div>
					<div class="buttons">
						<div class="lb">
							<a class="back" @click="close"><i class="icon-left-open-big"></i> Назад</a>
						</div>
						<div class="bb">
							<input type="submit" value="Сообщить о поступлении">
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
</script>

<script type="text/x-template" id="tehnokrat-thank-you-popup">
	<section class="thank_popup">
		<div class="thank_popup_content clearfix">
			<i class="close" @click="close"></i>
			<h3>Спасибо за заказ</h3>
			<p>Мы свяжемся с вами как только товар будет доступен.</p>
		</div>
	</section>
</script>
