<?php

class Transaction {
    private $uuid;
    private $transaction_id;
    private $amount;
    private $mobile;
    private $status;
    private $status_reason;
    private $type;

    public function __construct($uuid, $transaction_id, $amount, $mobile, $status, $status_reason, $type) {
        $this->uuid = trim($uuid);
        $this->transaction_id = trim($transaction_id);
        $this->amount = trim($amount);
        $this->mobile = trim($mobile);
        $this->status = trim($status);
        $this->status_reason = trim($status_reason);
        $this->type = trim($type);
    }

    public function get_uuid() {
        return $this->uuid;
    }

    public function get_transaction_id() {
        return $this->transaction_id;
    }

    public function get_amount() {
        return $this->amount;
    }

    public function get_mobile() {
        return $this->mobile;
    }

    public function get_status() {
        return $this->status;
    }

    public function get_status_reason() {
        return $this->status_reason;
    }

    public function get_type() {
        return $this->type;
    }
}
