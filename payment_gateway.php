<?php

/**
 * vPOS WooCommerce Payment Gateway class.
 */
class WP_Vpos_Gateway extends WC_Payment_Gateway
{
    private $token;
    private $pos_id;
    private $mode;
    private $merchant;
    private $orderId;

    public function __construct()
    {
        $this->id = "vpos";
        $this->init_form_fields();
        $this->init_settings();
        $this->has_fields = true;
        $this->method_title = "Multicaixa Express";
        $this->description = "The one stop shop for online payments in Angola, allowing you to process payments requests through vPOS.";
        $this->title = $this->get_option('title');
        $this->method_description = "The one stop shop for online payments in Angola, allowing you to process payments requests through vPOS.";
        $this->icon = "https://raw.githubusercontent.com/nextbss/vpos-woocommerce-checkout-widget/main/assets/img/logo.svg?token=ABH4VC2AHQCKTNCSLTI6AKTAIHVGM";
        $this->token = $this->get_option('vpos-token');
        $this->pos_id = $this->get_option('gpo_pos_id');
        $this->mode = 'yes' === $this->get_option('vpos_environment', 'no');
        $this->merchant = $this->get_option('merchant');
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields()
    {
        $this->form_fields = include("vpos-settings.php");
    }

    public function process_payment($orderId) 
    {
        $this->orderId = $orderId;
        storeInfoInCookies($this->merchant, $this->get_order_total());
        return array(
            'result'   => 'success',
            'redirect' => site_url() . "/?page_id=36"
        );
    }
}