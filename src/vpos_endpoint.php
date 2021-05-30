<?php

require_once("uuid.php");

class VPOS_Routes extends WP_REST_Controller {

    public function __construct() {
        
    }
    
    public function register_routes() {
        error_log("invoked register_routes!!");
        $namespace = "vpos-woocommerce/v1";
        $base = "cart/vpos";

        // Endpoint -> http://your-site.com/wp-json/vpos-woocommerce/v1/cart/vpos/fc4d77b0-a4c2-4417-b537-a62f7c88dd06
        register_rest_route($namespace, $base . "/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/", array(
            array(
                "methods"       => "GET",
                "callback"      => array($this, 'perform_payment')
            )
        ));
    }


 /**
 * Perform payment
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|WP_REST_Response
 */
  public function perform_payment($request) {    
    return new WP_REST_Response(uuid(), 200);
  }
}