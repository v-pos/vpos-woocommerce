<?php

WC()->session->set('payment_request_id', "1");
return array(
    'result'   => 'success',
    'redirect' => "http://localhost/"
);

// global $woocommerce;
// wp_safe_redirect($woocommerce->cart);
