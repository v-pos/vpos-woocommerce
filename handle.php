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

require($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/plugins/woocommerce/woocommerce.php');
require("src/vpos.php");
require("src/request_handler.php");
require("src/vpos_order_handler.php");

if (!defined('ABSPATH')) {
    exit;
}

$gateway = WC()->payment_gateways->payment_gateways()['vpos'];

if ($gateway == null) {
    error_log("gateway has not been setup");
    exit(1);
}

$settings = $gateway->settings;

if ($settings == null) {
    error_log("settings have not been defined");
    exit(1);
}

$token = $settings['vpos_token'];
$pos_id = $settings['gpo_pos_id'];
$payment_url = $settings['vpos_payment_callback'];
$refund_url = $settings['vpos_refund_callback'];
$mode = $settings['vpos_environment'];

$handler = new RequestHandler();
$vpos = new Vpos($pos_id, $token, $payment_url, $refund_url, $mode);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mobile = $_POST['mobile'];
    $amount = $_POST['amount'];

    $handler->handlePayment($vpos, $mobile, $amount);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET['id'];
    $type = $_GET['type'];
    $order_id = $_GET['order_id'];

    if ($type == 'poll') {
        $handler->handlePollResource($vpos, $id);
    }
    
    if ($type == 'get') {
        $handler->handleGetTransaction($vpos, $id);
    }

    if ($order_id != null && $type == 'complete-order') {
        VposOrderHandler::completeOrder($order_id);
        VposOrderHandler::flushOrderFromCookies();
        header('Content-Type: application/json');
        http_response_code(200);
    }
}
