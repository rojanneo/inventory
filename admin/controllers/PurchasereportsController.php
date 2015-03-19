<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchasereportsController
 *
 * @author Neo
 */
class PurchasereportsController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function indexAction() {
        $data['suppliers'] = getModel('supplier')->getActiveCollection();
        $data['categories'] = getModel('purchasecategory')->getActiveCollection();
        $this->view->renderAdmin('reports/purchase/form.phtml', $data);
    }

    public function viewAction() {
        loadHelper('inputs');
        $post_data = getPost();
        $data['category'] = $post_data['categories'];
        $data['supplier'] = $post_data['suppliers'];
        $data['realtime'] = $post_data['realtime'];
        $data['status_code'] = $post_data['status_code'];
        if (isset($post_data['periods']) and $post_data['periods'] and $post_data['periods'] != "") {
            $period = $post_data['periods'];
            if ($period == 1) {
                $dates = $this->getThisWeekDateRange();
            } else if ($period == 2) {
                $dates = $this->getLastWeekDateRange();
            } else if ($period == 3) {
                $dates = $this->getThisMonthDateRange();
            } else if ($period == 4) {
                $dates = $this->getLastMonthDateRange();
            } else if ($period == 5) {
                $dates = $this->getThreeMonthsDateRange();
            } else if ($period == 6) {
                $dates = $this->getSixMonthsDateRange();
            } else if ($period == 7) {
                $dates = $this->getThisYearDateRange();
            } else if ($period == 8) {
                $dates = $this->getLastYearDateRange();
            } else {
                $dates['start'] = '1992-02-19';
                $dates['end'] = '3000-01-01';
            }
            $data['start_date'] = $dates['start'];
            $data['end_date'] = $dates['end'];
        } else if (isset($post_data['date_from']) and $post_data['date_to']) {
            $data['start_date'] = $post_data['date_from'];
            $data['end_date'] = $post_data['date_to'];
        } else {
            $data['start_date'] = '1992-02-19';
            $data['end_date'] = '3000-01-01';
        }
        $report_data['reports'] = getModel('purchasereport')->generateReport($data);

        $this->view->renderAdmin('reports/purchase/report.phtml', $report_data);
    }

    function getThisYearDateRange() {
        $data['start'] = date('Y-m-d', strToTime('1/1 this year'));
        $data['end'] = date('Y-m-d', strToTime('12/31 this year'));
        return $data;
    }

    function getLastYearDateRange() {
        $data['start'] = date('Y-m-d', strToTime('1/1 last year'));
        $data['end'] = date('Y-m-d', strToTime('12/31 last year'));
        return $data;
    }

    function getThreeMonthsDateRange() {
        $data['start'] = date('Y-m-d', strtotime('first day of -3 months'));
        $data['end'] = date('Y-m-d', strtotime('last day of this months'));
        return $data;
    }

    function getSixMonthsDateRange() {
        $data['start'] = date('Y-m-d', strtotime('first day of -6 months'));
        $data['end'] = date('Y-m-d', strtotime('last day of this months'));
        return $data;
    }

    function getLastMonthDateRange() {
        $data['start'] = date('Y-m-d', strtotime('first day of last month'));
        $data['end'] = date('Y-m-d', strtotime('last day of last month'));
        return $data;
    }

    function getLastWeekDateRange() {
        $previous_week = strtotime("-1 week");
        $start_week = strtotime("last sunday midnight", $previous_week);
        $end_week = strtotime("next saturday", $start_week);

        $data['start'] = date("Y-m-d", $start_week);
        $data['end'] = date("Y-m-d", $end_week);
        return $data;
    }

    public function getThisMonthDateRange() {
        $data['start'] = date("Y-m-01");
        $data['end'] = date('Y-m-d', strtotime('last day of this month'));
        return $data;
    }

    public function getThisWeekDateRange() {
        $data['start'] = date("Y-m-d", strtotime('sunday last week'));
        $data['end'] = date("Y-m-d", strtotime("saturday this week"));
        return $data;
    }

    //put your code here
}
