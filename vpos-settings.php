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

return array(
    'enabled' => array(
        'title' => __('Activar/Desactivar', 'woocommerce'),
        'type' => 'checkbox',
        'label' => __('Activar vPOS', 'vPOS'),
        'default' => 'yes'
    ),
    'vpos_token' => array(
        'title' => __('Chave de API do vPOS', 'vPOS'),
        'type' => 'text',
        'description' => 'O token de API fornecido por vPOS'
    ),
    'gpo_pos_id' => array(
        'title' => __('ID do Ponto de Venda*', 'vPOS'),
        'type' => 'text',
        'description' => __('O ID do ponto de venda fornecido pela EMIS', 'vPOS'),
        'desc_tip' => true
    )
);
