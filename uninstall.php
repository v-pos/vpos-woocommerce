<?php

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

function drop_tables()
{
    global $wpdb;
    $sql = "DROP TABLE 'wp__vpos_woocommerce_transactions_1_1'";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

drop_tables();
