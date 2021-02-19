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
        $this->id = "vPOS woocommerce";
        $this->method_title = $this->get_option('title');
        $this->title = $this->get_option('title');
        $this->method_description = "The one stop shop for online payments in Angola, allowing you to process payments requests from EMIS GPO through vPOS.";
        $this->icon = "https://developer.vpos.ao/images/logo-366e50cc.png";
        $this->init_form_fields();
        $this->init_settings();
        $this->token = $this->get_option('vpos-token');
        $this->pos_id = $this->get_option('pos_id');
        $this->mode = 'yes' === $this->get_option('vpos_environment', 'no');
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options'));
    }

    public function init_form_fields()
    {
        $this->form_fields = include("vpos-settings.php");
    }
}