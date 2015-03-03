<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of uomModel
 *
 * @author Neo
 */
class uomModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getUnits()
    {
        $sql = "SELECT * FROM units";
        $units = $this->connection->Query($sql);
        if($units) return $units;
        else return false;
    }
    //put your code here
}
