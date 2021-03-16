<?php

class VposOrderHandler {
    
    public static function completeOrder($order_id) {
        $order = wc_get_order($order_id);
        $order->update_status('completed');
    }

    public static function flushOrderFromCookies() {
        setcookie("vpos_merchant", null, time() - 3600, "/");
		setcookie("vpos_total_amount", null, time() - 3600, "/");
		setcookie("vpos_order_id", null, time() - 3600, "/");
    }
}
