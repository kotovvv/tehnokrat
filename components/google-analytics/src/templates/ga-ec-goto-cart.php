<script>
    jQuery(document).ready(function ($) {
        $("div.for-ba").on("click", "a", function () {
            dataLayer.push({
                'ecommerce': {
                    'currencyCode': 'UAH',
                    'checkout': {
                        'actionField': {'step': 1, 'option': 'Переход в корзину'},
                        'products': window.ga_ec_dynamic_data.products
                    }
                },
                'event': 'gtm-ee-event',
                'gtm-ee-event-category': 'Enhanced Ecommerce',
                'gtm-ee-event-action': 'Checkout Step 1',
                'gtm-ee-event-non-interaction': 'False',
            });
        });
    });
</script>
