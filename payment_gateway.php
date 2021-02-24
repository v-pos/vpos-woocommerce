<?php

/**
 * vPOS WooCommerce Payment Gateway class.
 */
class WP_Vpos_Gateway extends WC_Payment_Gateway
{
    private $token;
    private $pos_id;
    private $mode;

    public function __construct()
    {
        $this->id = "vpos";
        $this->init_form_fields();
        $this->init_settings();
        $this->has_fields = true;
        $this->method_title = "vPOS ";
        $this->description = "The one stop shop for online payments in Angola, allowing you to process payments requests through vPOS.";
        $this->title = $this->get_option('title');
        $this->method_description = "The one stop shop for online payments in Angola, allowing you to process payments requests through vPOS.";
        $this->icon = "https://developer.vpos.ao/images/logo-366e50cc.png";
        $this->token = $this->get_option('vpos-token');
        $this->pos_id = $this->get_option('gpo_pos_id');
        $this->mode = 'yes' === $this->get_option('vpos_environment', 'no');
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields()
    {
        $this->form_fields = include("vpos-settings.php");
    }

    public function process_payment($orderId) 
    {
        return array(
            'result'   => 'success',
            'redirect' => $this->get_return_url($order)
        );
    }
}