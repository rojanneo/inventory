<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of stockModel
 *
 * @author Neo
 */
class StockModel extends Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function getStockBalance($product_id,$date)
    {
        $sql = "SELECT * FROM stock_balance WHERE product_id = $product_id order by date ASC";
        $balance = $this->connection->Query($sql);
        if($balance) return $balance;
        else return false;
    }
}
