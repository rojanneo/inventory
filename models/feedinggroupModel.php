<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of feedinggroupModel
 *
 * @author Neo
 */
class FeedinggroupModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCollection() {
        $sql = "SELECT * FROM feeding_groups";
        $groups = $this->connection->Query($sql);
        if ($groups)
            return $groups;
        else
            return false;
    }

    public function getLitterDays() {
        $sql = "SELECT * FROM litter_days";
        $days = $this->connection->Query($sql);
        if ($days)
            return $days;
        else
            return false;
    }

}
