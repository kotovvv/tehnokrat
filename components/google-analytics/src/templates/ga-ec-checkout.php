<script>
    jQuery(document).ready(function ($) {
        $("section.basket-page").on("click", "form.woocommerce-cart-form a.add.checkout-button", function () {
            dataLayer.push({
                'ecommerce': {
                    'currencyCode': 'UAH',
                    'checkout': {
                        'actionField': {'step': 2},
                        'products': window.ga_ec_dynamic_data.products
                    }
                },
                'event': 'gtm-ee-event',
                'gtm-ee-event-category': 'Enhanced Ecommerce',
                'gtm-ee-event-action': 'Checkout Step 2',
                'gtm-ee-event-non-interaction': 'False',
            });
        });
    });
</script>
