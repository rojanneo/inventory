<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Stockperiod
 *
 * @author Neo
 */
class StockperiodController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function indexAction() {
        $fridays = $this->getFridaysForYear(2015);
        getModel('stockperiod')->deleteCurrentPeriods();
        $start_date = date('Y-m-01');
        foreach ($fridays as $id => $f) {
            $date = date('Y-m-d', $f);
//            echo ($id+1).'->'.$date.'<br>';
            getModel('stockperiod')->insert($start_date, $date, $id + 1);
            //echo ($id+1).'->'.$start_date.'<br>';
            $start_date = date('Y-m-d', strtotime($date . '+1 day'));
        }

        $current_year = date('Y');
        $current_period = getModel('stockperiod')->getCurrentPeriod(date('Y-m-d'))['period_number'];
        //echo $current_period;

        $closing_stocks = getModel('stock')->getLatestStockData();
        if ($closing_stocks) {
            echo 'here';
        } else {
            $period = --$current_period;
            $stock_balance = getModel('stock')->getStockBalance(0, date('Y-m-d'));
            foreach ($stock_balance as $sb) {
                $product = getModel('purchaseproduct')->load($sb['product_id']);
                $total_stock = getModel('stock')->getTotalStock($sb['product_id'])['total_quantity'];
                getModel('stock')->InsertFirstClosingStock($product['product_id'], $product['product_name'], $total_stock, $sb['unit'], $period, $current_year);
            }
        }
    }

    function getFridaysForYear($y) {
        date_default_timezone_set('America/New_York');

        $fridays = array();
        $dt = strtotime("{$y}-01-01 Friday"); // Black magic :-)
        $wk = 0;
        $d = date('j', $dt);

        while ($wk < 52) {
            $fridays[] = $dt;
            $wk++;
            $d += 7;
            $dt = mktime(0, 0, 0, 1, $d, $y);
        }

        return $fridays;
    }

}
