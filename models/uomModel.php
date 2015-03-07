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
    
    public function getConvertibleUnits($unit)
    {
       $sql = "SELECT * FROM `unit_of_measure` AS UOM JOIN units ON units.weight_unit_id = UOM.unit_from WHERE unit_from = ".$unit." OR unit_to = ".$unit;
       $units = $this->connection->Query($sql);
       if($units) return $units;
       else return false;
    }

    public function load($id)
    {
      $sql = "SELECT * FROM units WHERE weight_unit_id = ".$id." LIMIT 1";
      $unit = $this->connection->Query($sql);
      if($unit) return $unit[0];
      else return false;
    }
    //put your code here
}
