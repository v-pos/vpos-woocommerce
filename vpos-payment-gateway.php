<?php

/**
 * Plugin Name:       vPOS - Payment Gateway
 * Plugin URI:        https://github.com/v-pos/vpos-woocommerce
 * Description:       Uma melhor forma de aceitar pagamentos.
 * Version:           1.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            vPOS
 * Author URI:        https://vpos.ao
 * License:           GNU General Public License
 * Text Domain:       vpos-woocommerce-plugin
 * Domain Path:       /languages
 */

    if (!defined('ABSPATH')) {
        exit;
    }

    define("VPOS_DIR", plugin_dir_path(__FILE__));

    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
    require_once(ABSPATH . "wp-admin/includes/class-wp-filesystem-direct.php");
    require_once("src/controllers/vpos_endpoint.php");

    function register_vpos_routes()
    {
        $routes = new VPOS_Routes();
        $routes->register_routes();
    }
    add_action("rest_api_init", "register_vpos_routes");

    function move_checkout_file_to_themes_dir()
    {
        $checkout_file_path = __DIR__  . "/vpos-checkout.php";
        $poll_file_path = __DIR__  . "/vpos-poll.php";
        $current_themes_path = get_template_directory();

        $filesystem = new WP_Filesystem_Direct(false);

        if ($filesystem->exists($checkout_file_path)) {
            if ($filesystem->copy($checkout_file_path, $current_themes_path . "/vpos-checkout.php", true)) {
            } else {
                error_log("failed to copy file from " . $checkout_file_path . " to " . $current_themes_path);
            }
        } else {
            error_log("vpos-checkout.php was not found in plugin directory");
        }

        if ($filesystem->exists($poll_file_path)) {
            if ($filesystem->copy($poll_file_path, $current_themes_path . "/vpos-poll.php", true)) {
            } else {
                error_log("failed to copy file from " . $poll_file_path . " to " . $current_themes_path);
            }
        } else {
            error_log("vpos-poll.php was not found in plugin directory");
        }
    }

    function add_checkout_page()
    {
        $page_title = 'vpos-checkout';
        $checkout_page = get_page_by_title($page_title, 'OBJECT', 'page');

        if (empty($checkout_page)) {
            wp_insert_post(
                array(
                'comment_status' => 'close',
                'post_author'    => 1,
                'post_title'     => ucwords($page_title),
                'post_name'      => strtolower(str_replace(' ', '-', trim($page_title))),
                'post_status'    => 'publish',
                'post_content'   => '',
                'post_type'      => 'page',
                'page_template'  => 'vpos-checkout.php'
                )
            );
        }
    }

    function add_poll_page()
    {
        $page_title = 'payment';
        $checkout_page = get_page_by_title($page_title, 'OBJECT', 'page');
        
        if (empty($checkout_page)) {
            wp_insert_post(
                array(
                'comment_status' => 'close',
                'post_author'    => 1,
                'post_title'     => ucwords($page_title),
                'post_name'      => strtolower(str_replace(' ', '-', trim($page_title))),
                'post_status'    => 'publish',
                'post_content'   => '',
                'post_type'      => 'page',
                'page_template'  => 'vpos-poll.php'
                )
            );
        }
    }

    function hide_vpos_pages_from_website($args)
    {
        $vpos_checkout_page = get_page_by_title('vpos-checkout', 'OBJECT', 'page');
        $vpos_poll_page = get_page_by_title('payment', 'OBJECT', 'page');

        $args['exclude'] = '';
        $args['exclude'] .= "" . $vpos_poll_page->ID . "," . "" . $vpos_checkout_page->ID . ",";
        return $args;
    }
    add_filter('wp_page_menu_args', 'hide_vpos_pages_from_website', 999, 1);

    function create_transactions_table()
    {
        global $wpdb;
        $transaction_repository = new TransactionRepository($wpdb);
        $transaction_repository->create_transactions_table();
    }

    function run_init_commands_after_installation()
    {
        $slug = (dirname(plugin_basename(__FILE__)));
        add_option('Activated_Plugin', $slug);
        move_checkout_file_to_themes_dir();
        add_checkout_page();
        add_poll_page();
        create_transactions_table();
    }
    register_activation_hook(__FILE__, 'run_init_commands_after_installation');
      
    function load_plugin()
    {
        $slug = (dirname(plugin_basename(__FILE__)));
        if (is_admin() && get_option('Activated_Plugin') == $slug) {
            delete_option('Activated_Plugin');
        }
    }
    add_action('admin_init', 'load_plugin');

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
        echo    _e('<b>vPOS WooCommerce</b> É necessário instalar o WooCommerce primeiro!', 'my-text-domain');
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
                $available_gateways['vpos']->order_button_text = "Proceder com Muticaixa Express";
            }
            return $available_gateways;
        }

        function put_billing_phone_number_in_cookies($order_billing_telephone)
        {
            setcookie("vpos_order_billing_telephone", $order_billing_telephone, time() + 3600, "/");
        }

        function put_in_cookies($merchant, $total_amount, $order_id, $order_billing_telephone)
        {
            setcookie("vpos_merchant", $merchant, time() + 3600, "/");
            setcookie("vpos_total_amount", $total_amount, time() + 3600, "/");
            setcookie("vpos_order_id", $order_id, time() + 3600, "/");
            setcookie("vpos_order_billing_telephone", $order_billing_telephone, time() + 3600, "/");
        }

        function formatTotalAmount($total_amount)
        {
            return number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $total_amount)), 2, ",", ".") . " Kz";
        }
    }
