<?php

/**
 * Plugin Name:       vPOS - Payment Gateway
 * Plugin URI:        https://vpos.ao
 * Description:       The one stop shop for online payments in Angola, allowing you to process payments requests through vPOS.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Next Business Solution
 * Author URI:        https://github.com/nextbss
 * License:           GNU General Public License
 * Text Domain:       vpos-woocommerce-plugin
 * Domain Path:       /languages
 */

    if (!defined('ABSPATH')) {
        exit;
    }

    function your_plugin_settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=vpos">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link');

    function woocommerce_required_admin_notice()
    {
        echo   '<div class="updated error notice"><p>';
        echo    _e('<b>vPOS Woocommerce</b> É necessário instalar o WooCommerce primeiro!', 'my-text-domain');
        echo  '</p></div>';
    }

    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        add_action('admin_notices', 'woocommerce_required_admin_notice');
    } else {
        add_action('plugins_loaded', 'init_vpos_gateway');
        function init_vpos_gateway()
        {
            include "payment_gateway.php";
        }

        add_action('template_redirect', 'init_vpos_payment_gateway');
        function init_vpos_payment_gateway()
        {
            if (get_query_var("payment_id") and get_query_var("id")) {
                $payment_id = get_query_var("payment_id");
                $payment_request_id = get_query_var("id");
                include_once "src/payment_confirm.php";
            }
        }

        add_filter("woocommerce_payment_gateways", "add_vpos");
        function add_vpos($methods)
        {
            $methods[] = 'WP_Vpos_Gateway';
            return $methods;
        }

        add_filter('woocommerce_available_payment_gateways', 'update_order_button');
        function update_order_button($available_gateways)
        {
            if (!is_checkout()) {
                return $available_gateways;
            }

            if (array_key_exists('vpos', $available_gateways)) {
                $available_gateways['vpos']->order_button_text = "Proceed with Muticaixa Express";
            }
            return $available_gateways;
        }

        //add_action('init', 'storeInfoInCookies');
        function storeInfoInCookies($merchant, $total_amount, $order_id)
        {
            setcookie("vpos_merchant", $merchant, time() + 3600, "/");
            setcookie("vpos_total_amount", $total_amount, time() + 3600, "/");
            setcookie("vpos_order_id", $order_id, time() + 3600, "/");
        }

        function formatTotalAmount($total_amount)
        {
            return number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $total_amount)), 2, ",", ".") . " Kz";
        }
    }
