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

require_once(VPOS_DIR . "/src/uuid.php");
require_once(VPOS_DIR . "/src/vpos_order_handler.php");
require(VPOS_DIR . "src/db/entities/transaction.php");
require(VPOS_DIR . "src/db/repositories/transaction_repository.php");
require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/woocommerce/woocommerce.php');
require_once(VPOS_DIR . "src/http/vpos.php");
require_once(VPOS_DIR . "src/http/request_handler.php");

class VPOS_Routes extends WP_REST_Controller
{
    private $gateway;
    private $settings;

    public function __construct()
    {
        $this->gateway = WC()->payment_gateways->payment_gateways()['vpos'];
        $this->settings = $this->gateway->settings;

        if ($this->gateway == null) {
            error_log("gateway has not been setup");
            exit(1);
        }

        if ($this->settings == null) {
            error_log("settings have not been defined");
            exit(1);
        }
    }

    public function register_routes()
    {
        $namespace = "vpos-woocommerce/v1";

        // This route will generate the following URI:
        // http://your-site.com/wp-json/vpos-woocommerce/v1/payment
        register_rest_route($namespace, "payment", array(
            array(
                "methods" => "POST",
                "callback" => array(
                    $this,
                    'perform_payment_request'
                ),
                'permission_callback' => '__return_true'
            )
        ));

        // This route will generate the following URI:
        // http://your-site.com/wp-json/vpos-woocommerce/v1/cart/fc4d77b0-a4c2-4417-b537-a62f7c88dd06/confirmation
        register_rest_route($namespace, "cart" . "/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/confirmation", array(
            array(
                "methods" => "POST",
                "callback" => array(
                    $this,
                    'handle_confirmation'
                ),
                'permission_callback' => '__return_true'
            )
        ));
        // This route will generate the following URI:
        // http://your-site.com/wp-json/vpos-woocommerce/v1/cart/fc4d77b0-a4c2-4417-b537-a62f7c88dd06
        register_rest_route($namespace, "cart" . "/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}", array(
            array(
                "methods" => "GET",
                "callback" => array(
                    $this,
                    'get_transaction_status'
                ),
                'permission_callback' => '__return_true'
            )
        ));
    }

    public function perform_payment_request($request)
    {
        $body = json_decode($request->get_body());

        if (empty($body->{"mobile"})) {
            return new WP_REST_Response(null, 400);
        }

        if (empty($body->{"amount"})) {
            return new WP_REST_Response(null,  400);
        }

        $uuid = uuid();
        $payment_callback_url = get_rest_url(null, "vpos-woocommerce/v1/cart/" . $uuid . "/confirmation");

        $token = $this->settings['vpos_token'];
        $pos_id = $this->settings['gpo_pos_id'];
        $payment_url = $payment_callback_url;
        $refund_url = "https://hard_coded_link"; // Leave this here for now, until vPOS makes it optional
        $mode = $this->settings['vpos_environment'];

        $handler = new RequestHandler();
        $vpos = new Vpos($pos_id, $token, $payment_url, $refund_url, $mode);

        $mobile = $body->{"mobile"};
        $amount = $body->{"amount"};

        put_billing_phone_number_in_cookies($mobile);

        // response_data contains headers we receive from vPOS
        $response_data = $handler->handleNewPayment($vpos, $mobile, $amount); 
        $transaction_id = $response_data["location"];

        $status_reason = null;
        $status = null;
        $type = null;
        $order_id = $_COOKIE['vpos_order_id'];

        $transaction = new Transaction($uuid, $transaction_id, $amount, $mobile, $status, $status_reason, $type, $order_id);
        global $wpdb;
        $transacion_repository = new TransactionRepository($wpdb);
        $transacion_repository->insert_transaction($transaction);

        return new WP_REST_Response($uuid, $response_data["code"]);
    }

    public function get_transaction_status($request)
    {
        $route = $request->get_route();
        $uuid = $this->extract_uuid_from_route($route);

        global $wpdb;
        $transaction_repository = new TransactionRepository($wpdb);

        $result = $transaction_repository->get_transaction($uuid);

        if ($result == null) {
            $message = json_encode(array(
                "error" => "transaction not found"
            ));
            return new WP_REST_Response($message, 404);
        }

        $response = json_encode(array(
            "status" => $result->status,
            "status_reason" => $result->status_reason
        ));

        return new WP_REST_Response($response, 200);
    }

    /**
     * Handle Confirmation Webhook
     *
     * This function is responsable for handling payment and refund confirmation from vPOS.
     *
     * If transaction exists locally and payment has been accepted, update local transaction
     * and return 200 or 201 to indicate succesfull confirmation of payment.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function handle_confirmation($request)
    {
        $body = json_decode($request->get_body());
        $route = $request->get_route();
        $transaction_uuid = $this->extract_uuid_from_route($route);

        global $wpdb;
        $transaction_repository = new TransactionRepository($wpdb);

        $result = $transaction_repository->get_transaction($transaction_uuid);

        if ($result == null) {
            $message = json_encode(array(
                "error" => "transaction not found"
            ));
            return new WP_REST_Response($message, 404);
        }

        if ($result->transaction_id != $body->{"id"}) {
            $message = json_encode(array(
                "error" => "transaction not found"
            ));
            return new WP_REST_Response($message, 404);
        }

        if ($result->mobile != $body->{"mobile"}) {
            $message = json_encode(array(
                "error" => "transaction not found"
            ));
            return new WP_REST_Response($message, 404);
        }

        if ($result->status == "accepted" && $result->status == "rejected") {
            return new WP_REST_Response(null, 201);
        }

        if ($body->{"status"} == "accepted") {
            $transaction_id = null;
            $amount = null;
            $mobile = null;
            $status = $body->{"status"};
            $status_reason = $body->{"status_reason"};
            $type = $body->{"type"};
            
            $order_id = $result->order_id;
            VposOrderHandler::update_order($order_id);
           
            $transaction_model = new Transaction($transaction_uuid, $transaction_id, $amount, $mobile, $status, $status_reason, $type, $order_id);
            $transaction_repository->update_transaction($transaction_uuid, $transaction_model);
            
            VposOrderHandler::flush_order_from_cookies();
        }
        
        if ($body->{"status"} == "rejected") {
            $transaction_id = null;
            $amount = null;
            $mobile = null;
            $status = $body->{"status"};
            $status_reason = $body->{"status_reason"};
            $type = $body->{"type"};

            $order_id = $result->order_id;
            VposOrderHandler::update_order_status($order_id, 'failed');
        
            $transaction_model = new Transaction($transaction_uuid, $transaction_id, $amount, $mobile, $status, $status_reason, $type, $order_id);
            $transaction_repository->update_transaction($transaction_uuid, $transaction_model);
            
            VposOrderHandler::flush_order_from_cookies();
        }

        return new WP_REST_Response(null, 201);
    }

    /**
     *  Helper function to extract the uuid from the route.
     *
     *  Example:
     *  php > $route = "/vpos-woocommerce/v1/cart/fc4d77b0-a4c2-4417-b537-a62f7c88dd06/confirmation";
     *  php > echo extract_uuid_from_route($route);
     *  php > fc4d77b0-a4c2-4417-b537-a62f7c88dd06
     */
    private function extract_uuid_from_route($route)
    {
        $modified_route = substr($route, 26);
        return str_replace("/confirmation", "", $modified_route);
    }
}
