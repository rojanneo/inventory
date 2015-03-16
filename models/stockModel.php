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
    
    public function getTotalStock($product_id)
    {
        $sql = "SELECT SUM(quantity) AS total_quantity, unit_symbol,weight_unit_id FROM `stock_balance` JOIN units ON stock_balance.unit = units.weight_unit_id WHERE product_id = ".$product_id;
        $total = $this->connection->Query($sql);
        if($total) return $total[0];
        else return false;
    }
    public function DeleteDailyStockCount($date)
    {
        $sql = "DELETE FROM daily_stock_count WHERE date = '$date'";
        $this->connection->DeleteQuery($sql);

    }
    public function InsertDailyStockCount($pid, $pname, $cq, $aq, $u, $v, $d)
    {
        $sql = "INSERT INTO `daily_stock_count`(`product_id`, `product_name`, `calculated_quantity`, `actual_quantity`, `unit`, `variance`, `date`) "
                . "VALUES (".mysql_escape_string($pid).",'".  mysql_escape_string($pname)."',".mysql_escape_string($cq).",".mysql_escape_string($aq).",".mysql_escape_string($u).",'".mysql_escape_string($v)."','".mysql_escape_string($d)."')";
        $this->connection->InsertQuery($sql);
        return $this->connection->GetInsertID();
    }
    
    public function getCurrentDailyStock()
    {
        $date = date('Y-m-d');
        $sql = "SELECT * FROM daily_stock_count WHERE date = '$date'";
        $ds = $this->connection->Query($sql);
        if($ds)
        {
            $stock = array();
            foreach($ds as $d)
            {
                $stock[$d['product_id']] = $d['actual_quantity'];
            }
            return $stock;
        }
        else return false;
        
    }
}
