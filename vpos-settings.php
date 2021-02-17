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
    'merchant_vpos_token' => array(
        'title' => __('Chave de API', 'vPOS'),
        'type' => 'title',
        'description' => ''
    ),
    'gpo_pos_id' => array(
        'title' => __('Point of Sale ID*', 'vPOS'),
        'type' => 'text',
        'description' => __('', 'vPOS'),
        'desc_tip' => true
    ),
    'gpo_supervisor_card' => array(
        'title' => __('Supervisor card*', 'vPOS'),
        'type' => 'text',
        'description' => __('', 'woocommerce'),
        'desc_tip' => true
    ),
    'vpos_refund_callback' => array(
        'title' => __('Refund Callback URL*', 'vPOS'),
        'type' => 'text',
        'description' => __('', 'woocommerce'),
        'desc_tip' => true
    ),
    'vpos_payment_callback' => array(
        'title' => __('Payment Callback URL*', 'vPOS'),
        'type' => 'text',
        'description' => __('', 'woocommerce'),
        'desc_tip' => true
    )
);