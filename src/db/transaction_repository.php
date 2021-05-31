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