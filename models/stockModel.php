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
class StockModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getStockBalance($product_id, $date) {
        if ($product_id != 0) {
            $sql = "SELECT * FROM stock_balance WHERE product_id = $product_id order by date ASC";
        } else {
            $sql = "SELECT * FROM stock_balance group by product_id order by date ASC";
        }
        $balance = $this->connection->Query($sql);
        if ($balance)
            return $balance;
        else
            return false;
    }

    public function getTotalStock($product_id) {
        $sql = "SELECT SUM(quantity) AS total_quantity, unit_symbol,weight_unit_id FROM `stock_balance` JOIN units ON stock_balance.unit = units.weight_unit_id WHERE product_id = " . $product_id;
        $total = $this->connection->Query($sql);
        if ($total)
            return $total[0];
        else
            return false;
    }

    public function DeleteDailyStockCount($date) {
        $sql = "DELETE FROM daily_stock_count WHERE date = '$date'";
        $this->connection->DeleteQuery($sql);
    }

    public function InsertDailyStockCount($pid, $pname, $cq, $aq, $u, $v, $d) {
        $sql = "INSERT INTO `daily_stock_count`(`product_id`, `product_name`, `calculated_quantity`, `actual_quantity`, `unit`, `variance`, `date`) "
                . "VALUES (" . mysql_escape_string($pid) . ",'" . mysql_escape_string($pname) . "'," . mysql_escape_string($cq) . "," . mysql_escape_string($aq) . "," . mysql_escape_string($u) . ",'" . mysql_escape_string($v) . "','" . mysql_escape_string($d) . "')";
        $this->connection->InsertQuery($sql);
        return $this->connection->GetInsertID();
    }

    public function getCurrentDailyStock() {
        $date = date('Y-m-d');
        $sql = "SELECT * FROM daily_stock_count WHERE date = '$date'";
        $ds = $this->connection->Query($sql);
        if ($ds) {
            $stock = array();
            foreach ($ds as $d) {
                $stock[$d['product_id']] = $d['actual_quantity'];
            }
            return $stock;
        } else
            return false;
    }

    public function getLatestStockData() {
        $year = date('Y');
        $sql = $sql = "SELECT * FROM periodic_closing_stock_after_final_save WHERE period = (SELECT MAX(period) FROM `periodic_closing_stock_after_final_save` WHERE year = '$year') AND year = '$year'";
        $stock = $this->connection->Query($sql);
        if ($stock)
            return $stock;
        else
            return false;
    }

    public function InsertFirstClosingStock($product_id, $product_name, $closing_stock, $unit, $period, $year) {
        $sql = "INSERT INTO periodic_closing_stock_after_final_save(product_id, product_name,opening_stock,purchased_stock,consumed_stock, calculated_balance, closing_stock, stock_variance, unit, reason, period, year, status)"
                . "Values($product_id, '$product_name',0,0,0,0,$closing_stock,0,$unit,NULL,$period,$year,'closed')";
        $this->connection->InsertQuery($sql);
        return $this->connection->GetInsertID();
    }

    public function getPeriodicStockTitles() {
        $sql = "SELECT * FROM `periodic_closing_stock_after_final_save` Group by period,year";
        $periods = $this->connection->Query($sql);
        if ($periods)
            return $periods;
        else
            return false;
    }

//    public function getClosingStocks($period, $year)
//    {
//        $sql = "SELECT * FROM periodic_closing_stock_after_final_save WHERE period = $period AND year = $year";
//        $stock = $this->connection->Query($sql);
//        if($stock) return $stock;
//        else return false;
//    }

    public function getPeriodStatus($period, $year) {
        $sql = "SELECT status from periodic_closing_stock_after_final_save WHERE period = $period AND year = $year LIMIT 1";
        //echo $sql;
        $status = $this->connection->Query($sql);
        if ($status)
            return $status[0]['status'];
        else
            return false;
    }

    public function getOpeningStocks($period, $year) {
        $previous_period = $period - 1;
        if (date('m') == '01')
            $previous_year = $year - 1;
        else
            $previous_year = $year;

        $sql = "SELECT product_id, closing_stock FROM periodic_closing_stock_after_final_save WHERE period = $previous_period AND year = $previous_year";
        $closing_stocks = $this->connection->Query($sql);
        $stocks = array();
        if ($closing_stocks) {
            foreach ($closing_stocks as $cs) {
                $stocks[$cs['product_id']] = $cs['closing_stock'];
            }
            return $stocks;
        } else
            return false;
    }

    public function getCurrentOpeningStocks($period, $year) {

        $sql = "SELECT product_id, opening_stock FROM periodic_closing_stock_after_final_save WHERE period = $period AND year = $year";
        $closing_stocks = $this->connection->Query($sql);
        $stocks = array();
        if ($closing_stocks) {
            foreach ($closing_stocks as $cs) {
                $stocks[$cs['product_id']] = $cs['opening_stock'];
            }
            return $stocks;
        } else
            return false;
    }

    public function getClosingStocks($period, $year) {
        $sql = "SELECT product_id, closing_stock FROM periodic_closing_stock_after_final_save WHERE period = $period AND year = $year";
        $closing_stocks = $this->connection->Query($sql);
        $stocks = array();
        if ($closing_stocks) {
            foreach ($closing_stocks as $cs) {
                $stocks[$cs['product_id']] = $cs['closing_stock'];
            }
            return $stocks;
        } else
            return false;
    }

    public function getPurchasedStocks($period, $year) {
        $sql = "SELECT product_id, purchased_stock FROM periodic_closing_stock_after_final_save WHERE period = $period AND year = $year";
        $closing_stocks = $this->connection->Query($sql);
        $stocks = array();
        if ($closing_stocks) {
            foreach ($closing_stocks as $cs) {
                $stocks[$cs['product_id']] = $cs['purchased_stock'];
            }
            return $stocks;
        } else
            return false;
    }

    public function getConsumedStocks($period, $year) {
        $sql = "SELECT product_id, consumed_stock FROM periodic_closing_stock_after_final_save WHERE period = $period AND year = $year";
        $closing_stocks = $this->connection->Query($sql);
        $stocks = array();
        if ($closing_stocks) {
            foreach ($closing_stocks as $cs) {
                $stocks[$cs['product_id']] = $cs['consumed_stock'];
            }
            return $stocks;
        } else
            return false;
    }

    public function getBalanceStocks($period, $year) {
        $sql = "SELECT product_id, calculated_balance FROM periodic_closing_stock_after_final_save WHERE period = $period AND year = $year";
        $closing_stocks = $this->connection->Query($sql);
        $stocks = array();
        if ($closing_stocks) {
            foreach ($closing_stocks as $cs) {
                $stocks[$cs['product_id']] = $cs['calculated_balance'];
            }
            return $stocks;
        } else
            return false;
    }

    public function getReasons($period, $year) {
        $sql = "SELECT product_id, reason FROM periodic_closing_stock_after_final_save WHERE period = $period AND year = $year";
        $closing_stocks = $this->connection->Query($sql);
        $stocks = array();
        if ($closing_stocks) {
            foreach ($closing_stocks as $cs) {
                $stocks[$cs['product_id']] = $cs['reason'];
            }
            return $stocks;
        } else
            return false;
    }

    public function getVariances($period, $year) {
        $sql = "SELECT product_id, stock_variance FROM periodic_closing_stock_after_final_save WHERE period = $period AND year = $year";
        $closing_stocks = $this->connection->Query($sql);
        $stocks = array();
        if ($closing_stocks) {
            foreach ($closing_stocks as $cs) {
                $stocks[$cs['product_id']] = $cs['stock_variance'];
            }
            return $stocks;
        } else
            return false;
    }

    public function getUnits($period, $year) {
        $sql = "SELECT product_id, unit FROM periodic_closing_stock_after_final_save WHERE period = $period AND year = $year";
        $units = $this->connection->Query($sql);
        $units_array = array();
        if ($units) {
            foreach ($units as $unit) {
                $units_array[$unit['product_id']] = $unit['unit'];
            }
            return $units_array;
        } else
            return false;
    }

    public function DeleteClosingStock($p, $y) {
        $sql = "DELETE FROM periodic_closing_stock_after_final_save WHERE period =$p AND year = $y";
        $this->connection->DeleteQuery($sql);
    }

    public function InsertClosingStock($pid, $pname, $os, $ps, $cos, $cb, $cs, $sv, $u, $r, $p, $y, $s) {
        $sql = "INSERT INTO `periodic_closing_stock_after_final_save`(`product_id`, `product_name`, `opening_stock`, `purchased_stock`, `consumed_stock`, `calculated_balance`, `closing_stock`, `stock_variance`, `unit`, `reason`, `period`, `year`, `status`) "
                . "VALUES ($pid,'$pname',$os,$ps,$cos,$cb," . mysql_escape_string($cs) . ",$sv,$u,'" . mysql_escape_string($r) . "',$p,$y,'$s')";
        $this->connection->InsertQuery($sql);
    }

    public function CloseStockPeriod($pid, $pname, $os, $ps, $cos, $cb, $cs, $sv, $u, $r, $p, $y, $s) {
        $sql = "INSERT INTO `periodic_closing_stock_after_final_save`(`product_id`, `product_name`, `opening_stock`, `purchased_stock`, `consumed_stock`, `calculated_balance`, `closing_stock`, `stock_variance`, `unit`, `reason`, `period`, `year`, `status`) "
                . "VALUES ($pid,'$pname',$os,$ps,$cos,$cb," . mysql_escape_string($cs) . ",$sv,$u,'" . mysql_escape_string($r) . "',$p,$y,'$s')";
        $this->connection->InsertQuery($sql);

        getModel('dailyfeed')->useFeed($pid, abs($sv), $u);
    }

    public function InsertFinalSaveStock($pid, $pname, $os, $cs, $u, $p, $y) {
        $sql = "INSERT INTO `periodic_closing_stock_before_final_save`(`product_id`, `product_name`, `opening_stock`, `closing_stock`, `unit`, `period`, `year`) "
                . "VALUES ($pid,'$pname',$os," . mysql_escape_string($cs) . ",$u,$p,$y)";
        $this->connection->InsertQuery($sql);
    }

    public function DeleteFinalSaveStock($p, $y) {
        $sql = "DELETE FROM periodic_closing_stock_before_final_save WHERE period =$p AND year = $y";
        $this->connection->DeleteQuery($sql);
    }

    public function ChangeStatus($product_id, $p, $y, $cs, $status) {
        $purchase = $this->getPurchases($product_id, $p, $y);
        $usage = $this->getConsumption($product_id, $p, $y);
        if (!$usage)
            $usage = 0;
        if (!$purchase)
            $purchase = 0;
        $opening_stock = 0;
        $sql = "SELECT opening_stock FROM periodic_closing_stock_after_final_save WHERE product_id = $product_id AND period = $p AND year = $y";
        $temp = $this->connection->Query($sql);
        if ($temp)
            $opening_stock = $temp[0]['opening_stock'];
        $balance = $opening_stock + $purchase - $usage;
        $variance = $cs - $balance;
        $sql = "UPDATE periodic_closing_stock_after_final_save SET status = '$status', purchased_stock = $purchase, consumed_stock = $usage, calculated_balance = $balance, closing_stock = $cs, stock_variance = $variance WHERE period = $p AND year = $y AND product_id  = $product_id";
        $this->connection->UpdateQuery($sql);
    }

    public function getPurchases($pid, $p, $y) {
        $period = getModel('stockperiod')->loadbyPeriodNumber($p);
        $start_date = date($y . '-m-d', strtotime($period['period_start_date']));
        $end_date = date($y . '-m-d', strtotime($period['period_end_date']));
        $sql = "SELECT SUM(quantity) AS purchased_quantity FROM `periodic_transactions` WHERE product_id = $pid AND date>='$start_date' AND date<='$end_date' AND type='po'";
        $purchase = $this->connection->Query($sql);
        if ($purchase)
            return $purchase[0]['purchased_quantity'];
        else
            return false;
    }

    public function getConsumption($pid, $p, $y) {
        $period = getModel('stockperiod')->loadbyPeriodNumber($p);
        $start_date = date($y . '-m-d', strtotime($period['period_start_date']));
        $end_date = date($y . '-m-d', strtotime($period['period_end_date']));
        $sql = "SELECT SUM(quantity) AS used_quantity FROM `periodic_transactions` WHERE product_id = $pid AND date>='$start_date' AND date<='$end_date' AND type='usage'";
        $consumption = $this->connection->Query($sql);
        if ($consumption)
            return $consumption[0]['used_quantity'];
        else
            return false;
    }

}
