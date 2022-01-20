<?php
 
/*
  Plugin Name: Centrovete Custom Shipping Alert
  Plugin URI: https://centrovete.it
  Description: Simple Price Shipping Alert
  Version: 1.0
  Author: Salvatore 
 */

// Display the shipping alert
add_action('centrovete_last_header','centrovete_shipping_alert_header', 10);
function centrovete_shipping_alert_header(){

	if(is_cart() ){ ?>
    
		<div class="d-md-none">
			<?php centrovete_print_shipping_alert(); ?>
		</div>
	
	<?php }

}

add_action('woocommerce_cart_collaterals','centrovete_shipping_alert_sidebar_cart', 5);
add_action('woocommerce_checkout_before_order_review', 'centrovete_shipping_alert_sidebar_cart', 5);
function centrovete_shipping_alert_sidebar_cart(){ ?>
	
	<div class="d-none d-lg-block">
		<?php centrovete_print_shipping_alert(); ?>
	</div>

<?php }

function centrovete_print_shipping_alert(){
	// get the cart total 
	$cart_total = WC()->cart->get_cart_contents_total();
	// get the shipping price
	$shipping_price = WC()->cart->get_shipping_total();
	// get threshold price for cheaper shipping
	$threshold_price = get_field('soglia_spedizione_scontata','option');
	// get the remaining amount for cheaper shipping
	$remaining_price = $threshold_price - $cart_total;
	// calculate the percentage of the remaining price
	$percentage = $cart_total / ($threshold_price / 100 );

	if($remaining_price > 0){ ?>

		<div class="shipping_alert">

			<span class="mb-2 d-block">
				<?php echo sprintf(get_field('testo_soglia_minima_carrello', 'option'),'<strong>'.wc_price($remaining_price).'</strong>'); ?>
			</span>

			<div class="progress_container">
				<span><?php echo wc_price(0, array('decimals'=> false)); ?></span>
				<div class="progress bg-centrovete">
					<div class="progress-bar" role="progressbar" style="width: <?php echo $percentage; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<span> <?php echo wc_price($threshold_price); ?> </span>
			</div>

		</div>

	  <?php } 
}


// Add custom css
add_action( 'wp_enqueue_scripts', 'centrovete_shipping_alert_style' );
function centrovete_shipping_alert_style(){

	wp_enqueue_style( 'centrovete_shipping_alert', plugin_dir_url(__FILE__) . '/centrovete_shipping_alert.css', array(), '1.0', 'all' );

}

