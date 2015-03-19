<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SupplierModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCollection($condition = false) {
        $where = $this->generateWhereCondition($condition);
        $sql = "SELECT * FROM purchase_suppliers" . $where;
        $suppliers = $this->connection->Query($sql);
        if ($suppliers)
            return $suppliers;
        else
            return false;
    }

    public function getActiveCollection($condition = false) {
        $sql = "SELECT * FROM purchase_suppliers WHERE is_active = '1'";
        $suppliers = $this->connection->Query($sql);
        if ($suppliers)
            return $suppliers;
        else
            return false;
    }

    public function insert($data = false) {
        if ($data != false) {
            extract($data);
            $sql = "INSERT INTO `purchase_suppliers`(`supplier_name`, `supplier_address`, `supplier_email`, `supplier_phone`,`is_active`) "
                    . "VALUES ('" . mysql_escape_string($supplier_name) . "','" . mysql_escape_string($supplier_address) . "','" . mysql_escape_string($supplier_email) . "','" . mysql_escape_string($supplier_phone) . "','" . mysql_escape_string($supplier_status) . "')";

            $this->connection->InsertQuery($sql);
            return $this->connection->GetInsertID();
        } else
            return false;
    }

    public function getFilteredList($data) {
        extract($data);

        $sql = "SELECT * FROM purchase_suppliers WHERE `supplier_id` LIKE '%" . $supplier_id . "%' AND `supplier_name` LIKE '%" . $supplier_name . "%' AND `supplier_email` LIKE '%" . $supplier_email . "%' AND `supplier_phone` LIKE '%" . $supplier_phone . "%' AND `supplier_address` LIKE '%" . $supplier_address . "%' AND `is_active` LIKE '%" . $is_active . "%'";
        $filtered_supplierd = $this->connection->Query($sql);
        if ($filtered_supplierd)
            return $filtered_supplierd;
        else
            return false;
    }

    public function load($supplier_id) {
        $sql = "SELECT * FROM purchase_suppliers WHERE supplier_id = " . $supplier_id . " LIMIT 1";
        $supplier = $this->connection->Query($sql);
        if ($supplier)
            return $supplier[0];
        else
            return false;
    }

    public function update($data) {
        if ($data and $data['supplier_id']) {
            extract($data);
            $sql = "UPDATE `purchase_suppliers` SET `supplier_name`='" . mysql_escape_string($supplier_name) . "',`supplier_address`='" . mysql_escape_string($supplier_address) . "',`supplier_email`='" . mysql_escape_string($supplier_email) . "',`supplier_phone`='" . mysql_escape_string($supplier_phone) . "',`is_active`='" . mysql_escape_string($supplier_status) . "' WHERE `supplier_id` = '" . $supplier_id . "'";
            $this->connection->UpdateQuery($sql);
            return true;
        } else
            return false;
    }

    public function getProductSuppliers($product_id) {
        $sql = "SELECT pps.supplier_id, suppliers.supplier_name FROM purchase_suppliers AS suppliers JOIN purchase_products_suppliers AS pps ON suppliers.supplier_id = pps.supplier_id WHERE product_id = " . $product_id;
        $suppliers = $this->connection->Query($sql);
        if ($suppliers)
            return $suppliers;
        else
            return false;
    }

}
