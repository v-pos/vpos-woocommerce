<?php

require_once(VPOS_DIR . "/src/uuid.php");
require_once(VPOS_DIR . "/src/vpos_order_handler.php");
require(VPOS_DIR . "src/db/entities/transaction.php");
require(VPOS_DIR . "src/db/repositories/transaction_repository.php");

class VPOS_Routes extends WP_REST_Controller
{
    public function register_routes()
    {
        $namespace = "vpos-woocommerce/v1";
        $base = "cart";

        // This route will generate the following URI:
        // http://your-site.com/wp-json/vpos-woocommerce/v1/cart/fc4d77b0-a4c2-4417-b537-a62f7c88dd06/confirmation
        register_rest_route($namespace, $base . "/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/confirmation", array(
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
        register_rest_route($namespace, $base . "/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}", array(
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
