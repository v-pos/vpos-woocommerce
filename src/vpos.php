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
            $this->api_endpoint = "https://sandbox.vpos.ao/api/v1";
        } else {
            $this->api_endpoint = "https://api.vpos.ao/api/v1";
        }
        $this->curl         = curl_init();
        $this->token        = $token;
        $this->payment_url  = $payment_url;
        $this->refund_url   = $refund_url;
        $this->pos_id       = $pos_id;
    }

    public function newPayment($mobile, $amount) {
        $request_data["amount"]       = $amount;
        $request_data["mobile"]       = $mobile;
        $request_data["type"]         = "payment";
        $request_data["pos_id"]       = (int) $this->pos_id;
        $request_data["callback_url"] = $this->payment_url;

        $curl                   = $this->curl;
        include_once('uuid.php');
    
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => $this->api_endpoint . "/transactions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => json_encode($request_data),
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $this->token,
                "Content-Type: application/json",
                "Accept: application/json",
            )
        ));
        
        $response = curl_exec($curl);
        error_log($this->token);

        error_log("amount: " . $request_data["amount"]);

        curl_close($curl);
        return $response;
    }
}