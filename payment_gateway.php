<?php

//     Copyright (C) <2021>  <Next Business Solutions>
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, either version 3 of the License, or
//     (at your option) any later version.

//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.

//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <https://www.gnu.org/licenses/>.

if (!defined('ABSPATH')) {
    exit;
}

class WP_Vpos_Gateway extends WC_Payment_Gateway
{
    private $token;
    private $pos_id;
    private $mode;
    private $merchant;
    private $order_id;
    private $page_id;

    public function __construct()
    {
        $this->id = "vpos";
        $this->init_form_fields();
        $this->init_settings();
        $this->has_fields = true;
        $this->method_title = "Multicaixa Express";
        $this->description = "Uma melhor forma de aceitar pagamentos.";
        $this->title = "Multicaixa Express";
        $this->method_description = "Uma melhor forma de aceitar pagamentos.";
        $this->icon = "https://backoffice.vpos.ao/images/mcx-logo.svg";
        $this->token = $this->get_option('vpos-token');
        $this->pos_id = $this->get_option('gpo_pos_id');
        $this->mode = 'yes' === $this->get_option('vpos_environment', 'no');
        $this->merchant =  get_option('blogname');
        $this->page_id = "vpos-checkout";
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields()
    {
        $this->form_fields = include("vpos-settings.php");
    }

    public function process_payment($order_id)
    {
        $this->order_id = $order_id;
        $billing_phone_number = $this->get_customer_billing_number($order_id);
        $billing_phone_number = $this->strip_prefix_from_angolan_number($billing_phone_number);

        if ($this->is_valid_angolan_number($billing_phone_number)) {
            storeInfoInCookies($this->merchant, $this->get_order_total(), $this->order_id, $billing_phone_number);
        } else {
            storeInfoInCookies($this->merchant, $this->get_order_total(), $this->order_id, "");
        }

        return array(
            'result'   => 'success',
            'redirect' => site_url() . "/" . $this->page_id
        );
    }

    private function is_valid_angolan_number($mobile)
    {
        return strlen($mobile) == 9;
    }

    private function strip_prefix_from_angolan_number($mobile)
    {
        $mobile = trim($mobile);
        $mobile = str_replace("+244", "", $mobile);
        $mobile = str_replace("00244", "", $mobile);
        return $mobile;
    }

    private function get_customer_billing_number($order_id)
    {
        $order = new WC_Order($this->order_id);
        return $order->get_billing_phone();
    }
}
