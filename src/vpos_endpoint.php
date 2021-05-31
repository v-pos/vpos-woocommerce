<?php

require_once("uuid.php");
require("db/transaction_repository.php");

class VPOS_Routes extends WP_REST_Controller {

    public function register_routes() {
        error_log("invoked register_routes!!");
        $namespace = "vpos-woocommerce/v1";
        $base = "cart/vpos";

        // Endpoint -> http://your-site.com/wp-json/vpos-woocommerce/v1/cart/vpos/fc4d77b0-a4c2-4417-b537-a62f7c88dd06/confirmation
        register_rest_route($namespace, $base . "/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/confirmation", array(
            array(
                "methods"       => "POST",
                "callback"      => array($this, 'handle_payment_confirmation')
            )
        ));
    }


 /**
 * Handle Payment Confirmation
 * 
 * This function is responsable for handling payment confirmation from vPOS.
 * 
 * If transaction exists locally and payment has been accepted, update local transaction
 * and return 200 or 201 to indicate succesfull confirmation of payment.
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|WP_REST_Response
 */
  public function handle_payment_confirmation($request) {
    $body = json_decode($request->get_body());
    $route = $request->get_route();
    $uuid = $this->extract_uuid_from_route($route);

    global $wpdb;
    $transaction_repository = new TransactionRepository($wpdb);

    $result = $transaction_repository->get_transaction($uuid);

    if (count($result) == 0) {
      return new WP_REST_Response("", 400);
    } 

    $transaction = $result[0];
    $update_transaction_result = $transaction_repository->update_transaction($uuid, $body);
    return new WP_REST_Response(null, 201);
  }

  public static function extract_uuid_from_route($route) {
    $modified_route = substr($route, 31);
    return str_replace("/confirmation", "", $modified_route);
  }
}