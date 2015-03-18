<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of stockperiodModel
 *
 * @author Neo
 */
class StockperiodModel extends Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function deleteCurrentPeriods()
    {
        $sql = "DELETE FROM stock_period";
        $this->connection->DeleteQuery($sql);
    }
    
    public function insert($start_date, $date, $no)
    {
        $sql = "INSERT INTO stock_period(period_start_date,period_end_date,period_number) VALUES('$start_date', '$date', $no)";
        $this->connection->InsertQuery($sql);
    }
    
    public function getCurrentPeriod($date)
    {
        $sql = "SELECT * FROM stock_period WHERE period_start_date <= '$date' AND period_end_date >= '$date' LIMIT 1";
        $period = $this->connection->Query($sql);
        if($period) return $period[0];
        else return false;
    }
    
    public function loadbyPeriodNumber($period)
    {
        $sql = "SELECT * FROM stock_period WHERE period_number = $period LIMIT 1";
        $period = $this->connection->Query($sql);
        if($period) return $period[0];
        else return false;
    }
}
