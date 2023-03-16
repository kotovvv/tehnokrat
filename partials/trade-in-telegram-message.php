<?php
// phpcs:disable WordPress.Security

/* @global $args array */
?>

⚙️ Заявка на «Trade-in» №<?php echo esc_html( $args['proposal_id'] ) . PHP_EOL; ?>
📅 <?php echo wp_date( 'd.m.Y (H:i)' ) . PHP_EOL; ?>
<?php if ( $args['name'] ) : ?>
👤 <?php echo esc_html( $_POST['name'] ) . PHP_EOL; ?>
<?php endif; ?>
<?php if ( $args['phone'] ) : ?>
☎️ <?php echo esc_html( $_POST['phone'] ) . PHP_EOL; ?>
<?php endif; ?>
<?php if ( $args['email'] ) : ?>
📧 <?php echo esc_html( $_POST['email'] ) . PHP_EOL; ?>
<?php endif; ?>
<?php if ( $args['description'] ) : ?>
📝 <?php echo esc_html( $_POST['description'] ) . PHP_EOL; ?>
<?php endif; ?>
<?php if ( $args['serial-number'] ) : ?>
🔎 <?php echo esc_html( $_POST['serial-number'] ) . PHP_EOL; ?>
<?php endif; ?>
<?php if ( $args['rechange-cycles'] ) : ?>
🔋 <?php echo esc_html( $_POST['rechange-cycles'] ) . PHP_EOL; ?>
<?php endif; ?>
🪛 <?php echo ( $args['attr1'] ? '✅' : '❌' ) . PHP_EOL; ?>
💧 <?php echo ( $args['attr2'] ? '✅' : '❌' ) . PHP_EOL; ?>
