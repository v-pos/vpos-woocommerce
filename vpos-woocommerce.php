<?php

	if (!defined('ABSPATH')) {
		exit;
	}

	// Add settings link on plugin page
	function your_plugin_settings_link($links) { 
		$settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=vpos-woocommerce">Settings</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
	}

	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link' );

	function woocommerce_required_admin_notice() {
		echo   '<div class="updated error notice"><p>';
			echo    _e( '<b>vPOS Woocommerce</b> É necessário instalar o WooCommerce primeiro!', 'my-text-domain' ); 
		echo  '</p></div>';
	}

	# Is woocommerce installed?
	if (!in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' )))) {
	    add_action( 'admin_notices', 'woocommerce_required_admin_notice' ); } 
    else
	{
		function log($message){
			$log = new WC_Logger();
			$log->add('vpos', $message );
			echo '<script type="text/javascript">alert("' . $message . '")</script>';
		
		}

		# Initialize Gateway
		add_action( 'plugins_loaded', 'init_vpos_gateway' );
		function init_vpos_gateway()
		{
			include "payment_gateway.php";
		}

		# Look for redirect from instamojo.
		add_action('template_redirect', 'init_vpos_payment_gateway' );
		function init_vpos_payment_gateway(){
			if(get_query_var("payment_id") and get_query_var("id")){
				$payment_id = get_query_var("payment_id");
				$payment_request_id = get_query_var("id");
				// include_once "payment_confirm.php";
			}
		}

		# Add paymetnt method to payment gateway list
		add_filter("woocommerce_payment_gateways","add_vpos");
		function add_vpos($methods){
			$methods[] = 'WP_Gateway_vpos';
			return $methods;
		}
	}