<?php

require_once 'connection.php';

class Model {

    public $connection;

    public function __construct() {
        $this->connection = Connection::GetInstance();
    }

    public function generateWhereCondition($condition = false) {
        if ($condition) {
            $where = ' WHERE ';
            if (isset($condition['type'])) {
                $type = $condition['type'];
                unset($condition['type']);
            } else
                $type = 'AND';
            foreach ($condition as $con => $val) {
                if (!is_array($val))
                    $where.=$con . "= '" . $val . "' " . $type . " ";
                else {
                    foreach ($val as $v) {
                        $where.=$con . "= '" . $v . "' " . $type . " ";
                    }
                    $where = rtrim($where, ' ' . $type . ' ');
                }
            }

            $where = rtrim($where, ' ' . $type . ' ');
            return $where;
        } else
            return false;
    }

}

?>