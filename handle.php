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

require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/woocommerce/woocommerce.php');
require_once("src/vpos.php");
require_once("src/request_handler.php");
require_once("src/vpos_order_handler.php");
require_once("src/db/transaction_repository.php");
require_once("src/db/transaction.php");

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

$uuid = uuid();
$payment_callback_url = get_rest_url(null, "vpos-woocommerce/v1/cart/vpos/" . $uuid . "/confirmation");

error_log("Callback URL: " . $payment_callback_url);

$token = $settings['vpos_token'];
$pos_id = $settings['gpo_pos_id'];
$payment_url = $payment_callback_url; // change to wordpress host url + page to handle callback eg: https://soba-store.com/vpos-confirmation
$refund_url = "https://hard_coded_link"; // change to wordpress host url + page to handle callback eg: https://soba-store.com/vpos-confirmation
$mode = $settings['vpos_environment'];

$handler = new RequestHandler();
$vpos = new Vpos($pos_id, $token, $payment_url, $refund_url, $mode);


function register_transaction($uuid, $mobile, $amount, $transaction_id) {
    global $wpdb;

    $status_reason = null;
    $status = null;
    $type = null;

    $transaction = new Transaction($uuid, $transaction_id, $amount, $mobile, $status, $status_reason, $type);
	$transacion_repository = new TransactionRepository($wpdb);
    $transacion_repository->insert_transaction($transaction);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mobile = $_POST['mobile'];
    $amount = $_POST['amount'];

    $response_data = $handler->handleNewPayment($vpos, $mobile, $amount);
    $transaction_id = $response_data["location"];

    register_transaction($uuid, $mobile, $amount, $transaction_id);
    header('Content-Type: application/json');
    http_response_code($response_data["code"]);
    echo $uuid;
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
