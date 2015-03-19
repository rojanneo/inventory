<?php

class ProducttypeModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCollection($condition = false) {
        if ($condition and is_array($condition)) {
            $where = $this->generateWhereCondition($condition);
        } else {
            $where = "Where 1";
        }
        $sql = "SELECT id, product_type_name FROM products_type " . $where;
        $product_types = $this->connection->Query($sql);
        if ($product_types)
            return $product_types;
        else
            return false;
    }

}
