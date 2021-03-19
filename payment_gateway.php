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

class WP_Vpos_Gateway extends WC_Payment_Gateway
{
    private $token;
    private $pos_id;
    private $mode;
    private $merchant;
    private $orderId;
    private $page_id;

    public function __construct()
    {
        $this->id = "vpos";
        $this->init_form_fields();
        $this->init_settings();
        $this->has_fields = true;
        $this->method_title = "Multicaixa Express";
        $this->description = "A solução número #1 para pagamentos online em Angola.";
        $this->title = $this->get_option('title');
        $this->method_description = "A solução número #1 para pagamentos online em Angola.";
        $this->icon = "https://backoffice.vpos.ao/images/mcx-logo.svg";
        $this->token = $this->get_option('vpos-token');
        $this->pos_id = $this->get_option('gpo_pos_id');
        $this->mode = 'yes' === $this->get_option('vpos_environment', 'no');
        $this->merchant = $this->get_option('merchant');
        $this->page_id = $this->get_option('payment_page_id');
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields()
    {
        $this->form_fields = include("vpos-settings.php");
    }

    public function process_payment($orderId)
    {
        $this->orderId = $orderId;
        storeInfoInCookies($this->merchant, $this->get_order_total(), $orderId);
        return array(
            'result'   => 'success',
            'redirect' => site_url() . "/?page_id=" . $this->page_id . ""
        );
    }
}
