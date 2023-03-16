jQuery(document).ready(function ($) {
	$(document.body).on('payment_method_selected', () => {
		$('form.checkout').trigger('update')
	});
});
