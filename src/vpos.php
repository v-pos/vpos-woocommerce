<?php

class Vpos {
    private $api_endpoint;
    private $pos_id;
    private $token;
    private $refund_url;
    private $payment_url;
    private $curl;
    private $http_response_header;
    private $location;
    private $http_code;
    private $http_message;
    private $LOCATION_INDEX = 27;
    private $data = array("message"=>null, "code"=>null, "location"=>null); 

    public function getLocation() {
        return $this->location;
    }

    // Since we are not able to get the headers in an organized way using CURL PHP, we use the function
    // below to acquire the necessary response headers
    public function getResponse() {
        if ($this->contains($this->http_response_header, "Accepted") == 1) {
            $this->http_message =  "Accepted";
            $this->http_code = 202;
            $this->data["message"] = $this->http_message;
            $this->data["code"] = $this->http_code;
            $this->data["location"] = substr($this->location, $LOCATION_INDEX);
            return $this->data;
        }

        if ($this->contains($this->http_response_header, "Bad Request") == 1) {
            $this->http_message =  "Bad Request";
            $this->http_code = 400;
            $this->data["message"] = $this->http_message;
            $this->data["code"] = $this->http_code;
            $this->data["location"] = null;
            return $this->data;
        }

        if ($this->contains($this->http_response_header, "OK") == 1) {
            $this->http_message =  "OK";
            $this->http_code = 200;
            $this->data["message"] = $this->http_message;
            $this->data["code"] = $this->http_code;
            $this->data["location"] = null;
            return $this->data;
        }

        if ($this->contains($this->http_response_header, "Internal Server Error") == 1) {
            $this->http_message =  "Internal Server Errir";
            $this->http_code = 500;
            $this->data["message"] = $this->http_message;
            $this->data["code"] = $this->http_code;
            $this->data["location"] = null;
            return $this->data;
        }

        if ($this->contains($this->http_response_header, "Created") == 1) {
            $this->http_message =  "Created";
            $this->http_code = 201;
            $this->data["message"] = $this->http_message;
            $this->data["code"] = $this->http_code;
            $this->data["location"] = null;
            return $this->data;
        }

        if ($this->contains($this->http_response_header, "Unauthorized") == 1) {
            $this->http_message =  "Unauthorized";
            $this->http_code = 401;
            $this->data["message"] = $this->http_message;
            $this->data["code"] = $this->http_code;
            $this->data["location"] = null;
            return $this->data;
        }

        if ($this->contains($this->http_response_header, "Service Unavaiable") == 1) {
            $this->http_message =  "Service Unavailable";
            $this->http_code = 503;
            $this->data["message"] = $this->http_message;
            $this->data["code"] = $this->http_code;
            $this->data["location"] = null;
            return $this->data;
        }

        if ($this->contains($this->http_response_header, "Not Found") == 1) {
            $this->http_message =  "Not Found";
            $this->http_code = 404;
            $this->data["message"] = $this->http_message;
            $this->data["code"] = $this->http_code;
            $this->data["location"] = null;
            return $this->data;
        }
        return $this->data;
    }

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

    public function contains($haystack, $needle, $caseSensitive = false) {
        return $caseSensitive ?
                (strpos($haystack, $needle) === FALSE ? FALSE : TRUE):
                (stripos($haystack, $needle) === FALSE ? FALSE : TRUE);
    }

    protected function handle_headers($curl, $header_line) {
        if ($this->contains($header_line, "HTTP/1.1") == 1) {
            $this->http_response_header = $header_line;
        }

        if ($this->contains($header_line, "location") == 1) {
            $this->location = $header_line;
        }
        return strlen($header_line);
    }

    public function newPayment($mobile, $amount) {
        $request_data["amount"]       = $amount;
        $request_data["mobile"]       = $mobile;
        $request_data["type"]         = "payment";
        $request_data["pos_id"]       = $this->pos_id;
        $request_data["callback_url"] = $this->payment_url;

        curl_setopt($this->curl, CURLOPT_URL, $this->api_endpoint  . "/transactions");
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, array($this, "handle_headers"));
        curl_setopt($this->curl, CURLOPT_ENCODING, "");
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($request_data));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $this->token,
            "Content-Type: application/json",
            "Accept: application/json",
        ));

        curl_exec($this->curl);

        return $this->getResponse();
    }
}
