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

class RequestHandler {

    public function __construct() {

    }

    public function handlePayment($vpos, $mobile, $amount) {
        
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

    public function handlePollResource($vpos, $id) {
        $response_data = $vpos->pollResource($id);

        if ($response_data["message"] == "See Other") {
            header('Content-Type: application/json');
            http_response_code($response_data["code"]);
            echo $response_data["location"];
        } else {
            header('Content-Type: application/json');
            http_response_code($response_data["code"]);
            echo $response_data["message"];
        }
    }

    public function handleGetTransaction($vpos, $id) {
        $response_data = $vpos->getTransaction($id);

        if ($response_data["code"] == 200) {
            header('Content-Type: application/json');
            http_response_code($response_data["code"]);
            echo json_encode($response_data["decoded_body"]);
        } else {
            header('Content-Type: application/json');
            http_response_code($response_data["code"]);
            echo $response_data["body"];
        }
    }
}

$handler = new RequestHandler();
$vpos = new Vpos($pos_id, $token, $payment_url, $refund_url, $mode);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mobile = $_POST['mobile'];
    $amount = $_POST['amount'];

    if ($gateway == null) {
        echo json_encode("gateway is null");
    } else {
        $handler->handlePayment($vpos, $mobile, $amount);
    }
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
        $order = wc_get_order($order_id);
        $order->update_status('completed');
        setcookie("vpos_merchant", $merchant, time() - 3600, "/");
		setcookie("vpos_total_amount", $total_amount, time() - 3600, "/");
		setcookie("vpos_order_id", $order_id, time() - 3600, "/");
        header('Content-Type: application/json');
        http_response_code(200);
    }
    
}


