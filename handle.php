<?php

require($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/plugins/woocommerce/woocommerce.php');
require("src/vpos.php");
require("src/request_handler.php");
require("src/vpos_order_handler.php");

if (!defined('ABSPATH')) {
    exit;
}

$gateway = WC()->payment_gateways->payment_gateways()['vpos'];
$settings = $gateway->settings;

$token = $settings['vpos_token'];
$pos_id = $settings['gpo_pos_id'];
$payment_url = $settings['vpos_payment_callback'];
$refund_url = $settings['vpos_refund_callback'];
$mode = $settings['vpos_environment'];

if ($gateway == null) {
    error_log("gateway has not been setup");
    exit(1);
}

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


