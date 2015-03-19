<?php

class CategoryModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCollection($condition = false) {
        $where = '';
        if ($condition and is_array($condition)) {
            $where = $this->generateWhereCondition($condition);
        } else {
            $where = "Where 1";
        }
        $sql = "SELECT * FROM `categories` " . $where . " ORDER BY sort_order";
        $categories = $this->connection->Query($sql);
        if ($categories)
            return $categories;
        else
            return false;
    }

    public function insert($data = false) {
        if ($data != false) {
            extract($data);
            if (!isset($parent_id)) {
                $parent_id = 0;
            }
            $sql = "INSERT INTO `categories`(
				`category_code`, 
				`category_name`, 
				`category_display_name`, 
				`category_description`, 
				`parent_id`, 
				`is_root`, 
				`sort_order`) 
				VALUES (
				'" . mysql_escape_string($category_code) . "',
				'" . mysql_escape_string($category_name) . "',
				'" . mysql_escape_string($category_display_name) . "',
				'" . mysql_escape_string($category_description) . "',
				'" . mysql_escape_string($parent_id) . "',
				'" . mysql_escape_string($is_root) . "',
				'" . mysql_escape_string($sort_order) . "')";
            $this->connection->InsertQuery($sql);
            return $this->connection->GetInsertID();
        } else
            return false;
    }

}
