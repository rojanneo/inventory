<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of purchasereportModel
 *
 * @author Neo
 */
class purchasereportModel extends Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function generateReport($data = false)
    {
        if($data)
        {
            extract($data);
            $sql = "CALL po('".$category."','".$supplier."','".$start_date."','".$end_date."','".$realtime."', '".$status_code."')";            
            $report = $this->connection->Query($sql);
            if($report) return $report;
            else return false;
        }
        else return false;
    }
}
