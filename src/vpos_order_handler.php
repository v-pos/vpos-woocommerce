<?php

//     Copyright (C) <2021>  <Next Business Solutions>
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, either version 3 of the License, or
//     (at your option) any later version.

//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.

//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <https://www.gnu.org/licenses/>.

if (!defined('ABSPATH')) {
    exit;
}

class VposOrderHandler
{
    public static function update_order($order_id)
    {
        $order = wc_get_order($order_id);
        if ($order->has_downloadable_item()) {
            $order->update_status("processing");
        } else {
            $order->update_status("completed");
        }
    }
    
    public static function update_order_status($order_id, $status)
    {
        $order = wc_get_order($order_id);
        $order->update_status($status);
    }

    public static function flush_order_from_cookies()
    {
        setcookie("vpos_merchant", null, time() - 3600, "/");
        setcookie("vpos_total_amount", null, time() - 3600, "/");
        setcookie("vpos_order_id", null, time() - 3600, "/");
        setcookie("vpos_order_billing_telephone", null, time() - 3600, "/");
    }
}
