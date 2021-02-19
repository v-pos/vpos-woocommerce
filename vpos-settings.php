<?php

if (!defined('ABSPATH')) {
    exit;
}

return array(
    'enabled' => array(
        'title' => __('Enable/Disable', 'woocommerce'),
        'type' => 'checkbox',
        'label' => __('Activar vPOS Checkout', 'vPOS'),
        'default' => 'yes'
    ),
    'title' => array(
        'title' => __('Title*', 'woocommerce'),
        'type' => 'text',
        'description' => __('Descrição que o cliente verá durante o checkout.', 'woocommerce'),
        'default' => __('vPOS', 'woocommerce'),
        'desc_tip' => true
    ),
    'description' => array(
        'title' => __('Description', 'woocommerce'),
        'type' => 'text',
        'desc_tip' => true,
        'description' => __('Descrição que o cliente verá durante o checkout.', 'woocommerce'),
        'default' => __('Ao escolher vPOS Checkout como forma de pagamento, você finalizará
                 o seu pagamento por adicionar o seu número', 'vPOS')
    ),
    'vpos_token' => array(
        'title' => __('vPOS Token', 'vPOS'),
        'type' => 'title',
        'description' => 'O token de API fornecido por vPOS'
    ),
    'gpo_pos_id' => array(
        'title' => __('POS ID*', 'vPOS'),
        'type' => 'text',
        'description' => __('O ID do ponto de venda fornecido pela EMIS', 'vPOS'),
        'desc_tip' => true
    ),
    'gpo_supervisor_card' => array(
        'title' => __('Cartão do supervisor*', 'vPOS'),
        'type' => 'text',
        'description' => __('Cartão do supervisor fornecido pela EMIS', 'woocommerce'),
        'desc_tip' => true
    ),
    'vpos_refund_callback' => array(
        'title' => __('Refund Callback URL*', 'vPOS'),
        'type' => 'text',
        'description' => __('O URL que tratará das notificações de estorno', 'woocommerce'),
        'desc_tip' => true
    ),
    'vpos_payment_callback' => array(
        'title' => __('Payment Callback URL*', 'vPOS'),
        'type' => 'text',
        'description' => __('O URL que tratará das notificações de pagamento', 'woocommerce'),
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
        'title'       => __('Nome da sua loja ou comerciante', 'vPOS' ),
        'type'        => 'text',
        'label'       => __('Nome do comerciante', 'vPOS' ),
        'description' => "O nome do comerciante, loja ou estabelecimento",
    )		
);