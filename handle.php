<?php

require($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/plugins/woocommerce/woocommerce.php');
require("src/vpos.php");

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

$mobile = $_POST['mobile'];
$amount = $_POST['amount'];

$vpos = new Vpos($pos_id, $token, $payment_url, $refund_url, $mode);

if ($gateway == null) {
    echo json_encode("gateway is null");
} else {
    $response_data = $vpos->newPayment($mobile, $amount);

    if ($response_data["message"] == "Accepted") {
        header('Content-Type: application/json');
        http_response_code($response_data["code"]);
        echo $response_data["location"];
    } else {
        header('Content-Type: application/json');
        http_response_code($response_data["code"]);
        echo $response_data["message"];
    }
}
