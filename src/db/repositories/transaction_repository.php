<?php

require VPOS_DIR . "/src/db/interfaces/repository.php";

class TransactionRepository implements BaseRepository
{
    private $db;
    private $table;

    public function __construct($wpdb)
    {
        $this->db = $wpdb;
        $this->table = $this->db->prefix . 'vpos_woocommerce_transacations';
    }
    
    public function get($uuid)
    {
        return $this->db->get_results(
            "SELECT * FROM $this->table WHERE ID = '$uuid'"
        )[0];
    }

    public function insert($transaction)
    {
        $this->db->insert(
            $this->table,
            array(
            'id' => $transaction->get_uuid(),
            'transaction_id' => $transaction->get_transaction_id(),
            'status' => $transaction->get_status(),
            'type' => $transaction->get_type(),
            'amount' => $transaction->get_amount(),
            'mobile' => $transaction->get_mobile(),
            'order_id' => $transaction->get_order_id(),
            'status_reason' => $transaction->get_status_reason(),
            'created_at' => current_time('mysql'),
            'updated_at' => null
          )
        );
    }

    public function create_table()
    {
        $charset_collate = $this->db->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $this->table (
            id varchar(255) PRIMARY KEY NOT NULL,
            transaction_id varchar(255),
            status varchar(255),
            type varchar(255),
            amount varchar(255),
            mobile varchar(255),
            order_id varchar(255),
            status_reason varchar(255),
            created_at timestamp,
            updated_at timestamp,

            INDEX (id),
            UNIQUE(id),
            UNIQUE(transaction_id)
          ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public function update($id, $transaction)
    {
        return $this->db->update($this->table, array(
          "type" => $transaction->get_type(),
          "status" => $transaction->get_status(),
          "status_reason" => $transaction->get_status_reason(),
          "updated_at" => current_time('mysql')
        ), array(
          "id" => $id
        ));
    }

    public function delete($id) {
      // TODO
    }
}
