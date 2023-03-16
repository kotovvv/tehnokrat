<script>
    jQuery(document).ready(function ($) {
        dataLayer.push({
            'ecommerce': {
                'currencyCode': 'UAH',
                'purchase': {
                    'actionField': {
                        'id': window.ga_ec_dynamic_data.orderId,
                        'revenue': window.ga_ec_dynamic_data.revenue,
                    },
                    'products': window.ga_ec_dynamic_data.products
                }
            },
            'event': 'gtm-ee-event',
            'gtm-ee-event-category': 'Enhanced Ecommerce',
            'gtm-ee-event-action': 'Purchase',
            'gtm-ee-event-non-interaction': 'False',
        });
    });
</script>
