<?php
/**
 * @global integer $order_id
 * @global float $revenue
 * @global string $products
 */

?>

<script id="ga-ec-dynamic-data">
    window.ga_ec_dynamic_data = {
        'orderId': '<?= $order_id ?>',
        'revenue': '<?= $revenue ?>',
        'products': [<?= $products ?>]
    };
</script>
