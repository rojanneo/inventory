<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of purchasecategoryModel
 *
 * @author Neo
 */
class PurchasecategoryModel extends Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function getCollection($condition = false) {
        $where = $this->generateWhereCondition($condition);
        $sql = "SELECT * FROM purchase_categories" . $where;
        $cat = $this->connection->Query($sql);
        if ($cat)
            return $cat;
        else
            return false;
    }

    public function getActiveCollection() {
        $sql = "SELECT * FROM purchase_categories WHERE is_active = '1' and parent != 0";
        $cat = $this->connection->Query($sql);
        if ($cat)
            return $cat;
        else
            return false;
    }

    public function load($id) {
        $sql = "SELECT * FROM purchase_categories WHERE category_id = " . $id;
        $cat = $this->connection->Query($sql);
        if ($cat)
            return $cat[0];
        else
            return false;
    }

    public function insert($data = false) {
        if ($data) {
            extract($data);
            if (!isset($parent))
                $parent = null;

            $sql = "INSERT INTO `purchase_categories`(`category_sku`, `name`, `category_desc`, `parent`, `is_active`) "
                    . "VALUES ('" . mysql_escape_string($category_sku) . "','" . mysql_escape_string($category_name) . "','" . mysql_escape_string($category_desc) . "','" . mysql_escape_string($parent) . "','" . mysql_escape_string($is_active) . "')";
            $this->connection->InsertQuery($sql);
            return $this->connection->GetInsertID();
        }
        return false;
    }

    public function update($data = false) {
        if ($data) {
            extract($data);
            $sql = "UPDATE `purchase_categories` SET `category_sku`='" . mysql_escape_string($category_sku) . "',`name`='" . mysql_escape_string($category_name) . "',`category_desc`='" . mysql_escape_string($category_desc) . "',`parent`='" . mysql_escape_string($parent) . "',`is_active`='" . mysql_escape_string($is_active) . "' WHERE `category_id` = " . $category_id;
            $this->connection->UpdateQuery($sql);
            return true;
        } else
            return false;
    }

    public function getTopLevelCategories() {
        $sql = "Select * From purchase_categories WHERE parent = 0 LIMIT 1";
        $cat = $this->connection->Query($sql);
        if ($cat)
            return $cat[0];
        else
            return false;
    }

    public function getSubCategories($parent_id = 0) {
        if ($parent_id != 0)
            $sql = "Select * From purchase_categories WHERE parent = " . $parent_id;
        else {
            $sql = "Select * From purchase_categories WHERE parent = " . $parent_id . " LIMIT 1";
        }
        $cat = $this->connection->Query($sql);
        if ($cat) {
            if ($parent_id == 0)
                return $cat[0];
            else
                return $cat;
        } else
            return false;
    }

}
