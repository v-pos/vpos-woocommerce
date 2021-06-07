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

class RequestHandler
{
    public function handle_payment($vpos, $mobile, $amount)
    {
        $response_data = $vpos->new_payment($mobile, $amount);

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

    public function handle_new_payment($vpos, $mobile, $amount)
    {
        return $vpos->new_payment($mobile, $amount);
    }

    public function handle_Poll_resource($vpos, $id)
    {
        $response_data = $vpos->poll_Resource($id);

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

    public function handle_get_transaction($vpos, $id)
    {
        $response_data = $vpos->get_transaction($id);

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
