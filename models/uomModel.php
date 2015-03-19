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
class uomModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getUnits() {
        $sql = "SELECT * FROM units";
        $units = $this->connection->Query($sql);
        if ($units)
            return $units;
        else
            return false;
    }

    public function getConvertibleUnits($unit) {
        $sql = "SELECT * FROM `unit_of_measure` AS UOM JOIN units ON units.weight_unit_id = UOM.unit_from WHERE unit_from = " . $unit . " OR unit_to = " . $unit;
        $units = $this->connection->Query($sql);
        if ($units)
            return $units;
        else
            return false;
    }

    public function load($id) {
        $sql = "SELECT * FROM units WHERE weight_unit_id = " . $id . " LIMIT 1";
        $unit = $this->connection->Query($sql);
        if ($unit)
            return $unit[0];
        else
            return false;
    }

    public function getMultiplierFactor($from, $to) {
        if ($from == $to)
            return 1;
        $sql = "SELECT * FROM unit_of_measure WHERE unit_from = " . $from . " AND unit_to = " . $to . " Limit 1";
        $uom = $this->connection->Query($sql);
        if ($uom)
            return $uom[0]['multiply_factor'];
        else
            return false;
    }

    public function getUnitSymbol($id) {
        $u = $this->load($id);
        if ($u)
            return $u['unit_symbol'];
        else
            return false;
    }

    public function getConstantFactor($from, $to) {
        if ($from == $to)
            return 0;
        $sql = "SELECT * FROM unit_of_measure WHERE unit_from = " . $from . " AND unit_to = " . $to . " Limit 1";
        $uom = $this->connection->Query($sql);
        if ($uom)
            return $uom[0]['constant_factor'];
        else
            return false;
    }

    //put your code here
}
