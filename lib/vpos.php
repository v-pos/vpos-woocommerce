<?php


class Vpos 
{
    private $api_endpoint;
    private $pos_id;
    private $token;
    private $refund_url;
    private $payment_url;
    private $curl;

    public function __construct($pos_id, $token, $payment_url, $refund_url, $mode) 
    {
        if ($mode == "yes") 
        {
            $this->api_endpoint = "https://api.vpos.ao/api/v1";
        } else {
            $this->api_endpoint = "https://sandbox.vpos.ao/api/v1";
        }
        $this->curl         = curl_init();
        $this->token        = $token;
        $this->payment_url  = $payment_url;
        $this->refund_url   = $refund_url;
    }

    public function newPayment($mobile, $amount) {
        $request_data["amount"] = $amount;
        $request_data["mobile"] = $mobile;
        $request_data["type"]   = "payment";
        $curl                   = $this->curl;
    
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => $this->api_endpoint . "/transactions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $this->token,
                "Content-Type: application/json",
                "Idempotency-Key: ",
            )
        ));
        
        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        return $response;
    }
}