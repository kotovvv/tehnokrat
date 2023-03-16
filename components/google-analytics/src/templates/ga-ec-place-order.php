<script>
    jQuery(document).ready(function ($) {
        $("form.checkout").on("checkout_place_order_success", function () {
            dataLayer.push({
                'ecommerce': {
                    'currencyCode': 'UAH',
                    'checkout': {
                        'actionField': {'step': 3},
                        'products': window.ga_ec_dynamic_data.products
                    }
                },
                'event': 'gtm-ee-event',
                'gtm-ee-event-category': 'Enhanced Ecommerce',
                'gtm-ee-event-action': 'Checkout Step 3',
                'gtm-ee-event-non-interaction': 'False',
            });
        });
    });
</script>
