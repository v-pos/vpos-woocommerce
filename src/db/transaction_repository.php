<?php

class TransactionRepository {
    private $db;
    private $transactions_table;

    public function __construct($wpdb) {
        $this->db = $wpdb;
        $this->transactions_table = $this->db->prefix . '_vpos_woocommerce_transacations_' . VPOS_VERSION;
    }
    
    public function get_transaction($uuid) {  
        return $this->db->get_results(
          "SELECT * FROM $this->transactions_table WHERE ID = '$uuid'"
        );
    }

    // TODO: This should probably be moved to a different class since it is not the 
    // repositories responsability to create the table schema.
    public function create_transactions_table() {
        $charset_collate = $this->db->get_charset_collate();

        $sql = "CREATE TABLE $this->transactions_table (
            id varchar(255) PRIMARY KEY NOT NULL,
            transaction_id varchar(255),
            status varchar(255),
            type varchar(255),
            amount varchar(255),
            mobile varchar(255),
            status_reason varchar(255),
            created_at timestamp,
            updated_at timestamp,

            INDEX (id)
          ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public function update_transaction($id, $body) {
        $type = $body->{"type"};
        $status = $body->{"status"};
        $status_reason = $body->{"status_reason"};
        $updated_at_date = current_time('mysql');
      
        return $this->db->update($this->transactions_table, array(
          "type" => $type,
          "status" => $status,
          "status_reason" => $status_reason,
          "updated_at" => $updated_at_date
        ), array(
          "id" => $id
        ));
      }
}

?>