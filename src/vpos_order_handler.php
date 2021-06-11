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
        $products = $order->get_items();

        if (count($products) == 1) {
            if ($order->has_downloadable_item()) {
                $order->update_status("completed");
            } else {
                $order->update_status("processing");
            }
        } elseif (count($products) >= 2) {
            $results = VposOrderHandler::get_product_types_binary_list($products);
            $sum = array_sum($results);

            if (VposOrderHandler::all_products_are_downloable($sum)) {
                $order->update_status("completed");
            } elseif (VposOrderHandler::all_products_are_not_downloadble($sum, $results)) {
                $order->update_status("processing");
            } elseif (VposOrderHandler::products_are_diverse($sum, $results)) {
                $order->update_status("processing");
            }
        } else {
            error_log("can't have order with 0 items");
        }
    }

    public static function get_product_types_binary_list($products)
    {
        $results = array();
            
        foreach ($products as $key=>$item) {
            $_product = new WC_Product($item['id']);
            if ($_product->is_downloadable()) {
                $results[$key] = 0;
            } else {
                $results[$key] = 1;
            }
        }

        return $results;
    }

    public static function products_are_diverse($sum, $results)
    {
        return $sum > 0 && $sum < count($results);
    }

    public static function all_products_are_downloable($sum)
    {
        return $sum == 0;
    }

    public static function all_products_are_not_downloadble($sum, $results)
    {
        return $sum == count($results);
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
