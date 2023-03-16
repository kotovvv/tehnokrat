<?php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once('includes/Mobile_Detect.php');
$detect = new Mobile_Detect;

?>

		<footer class="footer_js">
			<div class="container">
				<div class="footer-content clearfix">
					<div>
						<h3><?= __('PRODUCTS AND SERVICES', 'tehnokrat') ?></h3>
						<?php wp_nav_menu( array( 'theme_location' => 'footer_left', 'container' => false, 'menu_class' => 'footer-left-menu' ) ); ?>
					</div>
					<div>
						<h3><?= __('CATEGORIES', 'tehnokrat') ?></h3>
						<?php wp_nav_menu( array( 'theme_location' => 'footer_middle', 'container' => false, 'menu_class' => 'footer-middle-menu' ) ); ?>
					</div>
					<div>
						<h3><?= __('ABOUT THE COMPANY', 'tehnokrat') ?></h3>
						<?php wp_nav_menu( array( 'theme_location' => 'footer_right', 'container' => false, 'menu_class' => 'footer-right-menu' ) ); ?>
					</div>
					<div class="foot-links">
						<img class="img-pay" src="/wp-content/uploads/2022/12/pay.png">
						<div class="numb">
							<a href="tel:0800600088">0 800 600 088</a>
							<span>КОНТАКТНИЙ ЦЕНТР</span>
						</div>
						<ul class="social">
							<li>
								<a target="_blank" href="https://tehnokrat.olx.ua/uk/"> 
									<img src="/wp-content/uploads/2022/12/olx-1.png">
								</a>
							</li>
							<li>
								<a target="_blank" href="https://www.facebook.com/tehnokrat.com.ua/">
									<img src="/wp-content/uploads/2022/12/fb-1.png">
								</a>
							</li>
							<li>
								<a target="_blank" href="https://www.instagram.com/tehnokrat.ua/">
									<img src="/wp-content/uploads/2022/12/inst-1.png">
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<?php if ( ! $detect->isMobile() ) : ?>
				<div class="tl-call-catcher">Vega Cloud PBX</div>
			<?php endif; ?>
		</footer>

	</div>

</div><!-- <div id="vue-app">-->

<?php wp_footer();?>
<script data-skip-moving="true">
	(function(w,d,u,b){
		s=d.createElement('script');r=(Date.now()/1000|0);s.async=1;s.src=u+'?'+r;
		h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
	})(window,document,'https://cdn.bitrix24.ua/b4365611/crm/site_button/loader_2_j7t3ol.js');
</script>
<?php if ( ! $detect->isMobile() ) : ?>
	<script>var telerWdWidgetId="49255634-9672-457d-9fb0-66618209d650";var telerWdDomain="pro100mac.pbx.vega.ua";</script> <script src="//pro100mac.pbx.vega.ua/public/widget/call-catcher/lib-v3.js"></script>
<?php endif; ?>
</body>
</html>
