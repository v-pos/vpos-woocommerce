<?php

/**
 * Plugin Name:       vPOS Woocommerce Plugin
 * Plugin URI:        https://vpos.ao
 * Description:       The one stop shop for online payments in Angola, allowing you to process payments requests through vPOS.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Next Business Solution
 * Author URI:        https://github.com/alexjuca
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       vpos-woocommerce-plugin
 * Domain Path:       /languages
 */


	if (!defined('ABSPATH')) {
		exit;
	}

	// Add settings link on plugin page
	function your_plugin_settings_link($links) { 
		$settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=vpos">Settings</a>'; 
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

		# Add payment method to payment gateway list
		add_filter("woocommerce_payment_gateways","add_vpos");
		function add_vpos($methods){
			$methods[] = 'WP_Vpos_Gateway';
			return $methods;
		}
	}