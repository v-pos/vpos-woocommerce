<?php

include("uuid.php");

class Vpos 
{
    private $api_endpoint;
    private $pos_id;
    private $token;
    private $refund_url;
    private $payment_url;
    private $http_client;

    public function __construct($pos_id, $token, $payment_url, $refund_url, $mode) 
    {
        if ($mode == true) 
        {
            $this->api_endpoint = "https://api.vpos.ao/api/v1";
        } else {
            $this->api_endpoint = "https://sandbox.vpos.ao/api/v1";
        }
        $this->http_client  = curl_init();
        $this->token        = $token;
        $this->payment_url  = $payment_url;
        $this->refund_url   = $refund_url;
    }

    public function newPayment($mobile, $amount) {
        $request_data["amount"] = $amount;
        $request_data["mobile"] = $mobile;
        $request_data["type"]   = "payment";
    
        curl_setopt_array(
        array(
            CURLOPT_URL => $this->$api_endpoint . "/transactions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $this->token,
                "Content-Type: application/json",
                "Idempotency-Key: " . uuid(),
            )
        ));
    }
}