<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of purchaseproductModel
 *
 * @author Neo
 */
class PurchaseproductModel extends Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function insert($data = false)
    {
        if($data)
        {
            extract($data);
            $sql = "INSERT INTO `purchase_products`(`product_sku`, `product_name`, `product_description`, `product_weight_unit`, `product_quantity`, `product_unit_price`, `product_status`) "
                    . "VALUES ('".mysql_escape_string($product_sku)."','".mysql_escape_string($product_name)."','".mysql_escape_string($product_desc)."','".mysql_escape_string($product_weight_unit)."',"
                    . "'".mysql_escape_string($product_quantity)."','".mysql_escape_string($product_unit_price)."','".mysql_escape_string($product_status)."')";
            
            $this->connection->InsertQuery($sql);
            return $this->connection->GetInsertID();
        }
        else return false;
    }
    
    public function update($data = false)
    {
        if($data)
        {
            extract($data);
            $sql = "UPDATE `purchase_products` SET `product_sku`='".mysql_escape_string($product_sku)."',`product_name`='".mysql_escape_string($product_name)."',`product_description`='".mysql_escape_string($product_desc)."',`product_weight_unit`='".mysql_escape_string($product_weight_unit)."',`product_quantity`='".mysql_escape_string($product_quantity)."',`product_unit_price`='".mysql_escape_string($product_unit_price)."',`product_status`='".mysql_escape_string($product_status)."' WHERE `product_id` = ".$product_id;
            $this->connection->UpdateQuery($sql);
            return true;
        }
        else return false;
    }
    
    public function updateCategories($categories, $product_id)
    {
        $this->deleteCategories($product_id);
        $this->insertCategories($categories,$product_id);
        return true;
    }
    
    public function deleteCategories($product_id)
    {
        $sql = "DELETE FROM purchase_products_categories WHERE product_id = ".$product_id;
        $this->connection->DeleteQuery($sql);
    }
    
    public function updateSuppliers($suppliers, $product_id)
    {
        $this->deleteSuppliers($product_id);
        $this->insertsuppliers($suppliers,$product_id);
        return true;
    }
    
    public function deleteSuppliers($product_id)
    {
        $sql = "DELETE FROM purchase_products_suppliers WHERE product_id = ".$product_id;
        $this->connection->DeleteQuery($sql);
    }
    
    public function load($id)
    {
        $sql = "SELECT * FROM purchase_products WHERE product_id = ".$id." LIMIT 1";
        $product = $this->connection->Query($sql);
        if($product)return $product[0];
        else return false;
    }
    
    public function insertCategories($categories = false, $product_id)
    {
        if($categories)
        {
            foreach($categories as $category)
            {
                $sql = "INSERT INTO `purchase_products_categories`(`product_id`, `category_id`) VALUES (".$product_id.",".$category.")";
                $this->connection->InsertQuery($sql);
            }
        }
        else return false;
    }
    
    public function insertSuppliers($suppliers = false, $product_id)
    {
        if($suppliers)
        {
            foreach($suppliers as $supplier)
            {
                $sql = "INSERT INTO `purchase_products_suppliers`(`product_id`, `supplier_id`) VALUES (".$product_id.",".$supplier.")";
                $this->connection->InsertQuery($sql);
            }
        }
        else return false;
    }
    
    public function getCategories($id)
    {
        $sql = "SELECT category_id FROM purchase_products_categories WHERE product_id = ".$id;
        $cats = $this->connection->Query($sql);
        $cats_array = array();
        if($cats)
        {
            foreach($cats as $cat)
            {
                array_push($cats_array, $cat['category_id']);
            }
            return $cats_array;
        }
        else return false;
    }
    
    public function getSuppliers($id)
    {
        $sql = "SELECT supplier_id FROM purchase_products_suppliers WHERE product_id = ".$id;
        $supps = $this->connection->Query($sql);
        $supps_array = array();
        if($supps)
        {
            foreach($supps as $supp)
            {
                array_push($supps_array, $supp['supplier_id']);
            }
            return $supps_array;
        }
        else return false;
    }
}
