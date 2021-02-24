<?php

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
    'title' => array(
        'title' => __('Title*', 'woocommerce'),
        'type' => 'text',
        'description' => __('Descrição que o cliente verá durante o checkout.', 'woocommerce'),
        'default' => __('vPOS', 'woocommerce'),
        'desc_tip' => true
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
    ),
    'gpo_supervisor_card' => array(
        'title' => __('Número de Cartão do Supervisor*', 'vPOS'),
        'type' => 'text',
        'description' => __('Número do Cartão do supervisor fornecido pela EMIS', 'woocommerce'),
        'desc_tip' => true
    ),
    'vpos_refund_callback' => array(
        'title' => __('Refund Callback URL*', 'vPOS'),
        'type' => 'text',
        'description' => __('O URL que tratará das notificações de estorno, e.g https://domain.co.ao/api/callback', 'woocommerce'),
        'desc_tip' => true
    ),
    'vpos_payment_callback' => array(
        'title' => __('Payment Callback URL*', 'vPOS'),
        'type' => 'text',
        'description' => __('O URL que tratará das notificações de pagamento, e.g https://domain.co.ao/api/callback', 'woocommerce'),
        'desc_tip' => true
    ),
    'vpos_environment' => array(
        'title'       => __('Modo Sandbox', 'vPOS'),
        'type'        => 'checkbox',
        'label'       => __('Habilitar modo Sandbox', 'vPOS'),
        'default'     => 'no',
        'description' => "O ambiente que será usado e.g SANDBOX ou PRODUCTION",
    ),
    'merchant' => array(
        'title'       => __('Nome da sua loja', 'vPOS' ),
        'type'        => 'text',
        'label'       => __('Nome da sua loja', 'vPOS' ),
        'description' => "O nome do comerciante, loja ou estabelecimento",
    )		
);