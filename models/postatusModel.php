<?php

class PostatusModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getStatusName($id) {
        $sql = "SELECT * FROM po_status WHERE id = " . $id . " Limit 1";
        $status = $this->connection->Query($sql);
        if ($status)
            return $status[0]['status_name'];
        else
            return false;
    }

}
