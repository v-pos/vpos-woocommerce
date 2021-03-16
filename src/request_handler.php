<?php

class RequestHandler {

    public function __construct() { 
        // Empty Constructor
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