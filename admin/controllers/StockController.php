<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StockController
 *
 * @author Neo
 */
class StockController extends Controller{
    public function __construct() {
        parent::__construct();
        loadHelper('url');
    }
    
    public function indexAction()
    {
        
    }
    
    public function listAction()
    {
        $this->view->renderAdmin('stock/list/list.phtml');
    }
    
    public function getCategoryListAction()
    {
        $data['categories'] = getModel('purchasecategory')->getActiveCollection();
        $this->view->renderWithoutAnything('stock/list/categoryList.phtml',$data);
    }
    
    public function getCompleteListAction()
    {
        $data['products'] = getModel('purchaseproduct')->getActiveCollection();
        $this->view->renderWithoutAnything('stock/list/completeList.phtml',$data);
    }
    
    public function getSupplierListAction()
    {
        $data['suppliers'] = getModel('supplier')->getActiveCollection();
        $this->view->renderWithoutAnything('stock/list/supplierList.phtml',$data);
    }
    
    public function dailyStockCountAction()
    {
        $data['categories'] = getModel('purchasecategory')->getActiveCollection();
        $data['daily_stock'] = getModel('stock')->getCurrentDailyStock();
        $this->view->renderAdmin('stock/daily/daily_stock_count.phtml',$data);
    }
    
    public function dailycountpostAction()
    {
        loadHelper('inputs');
        $post_data = getPost();
        if($post_data) extract ($post_data);
        getModel('stock')->DeleteDailyStockCount(date('Y-m-d'));
        foreach($daily_stock as $product_id => $ds)
        {
            $variance =$ds -  $calculated_quantity[$product_id];
            if(!getModel('stock')->InsertDailyStockCount($product_id, $product_name[$product_id], $calculated_quantity[$product_id], $ds, $unit[$product_id], $variance, $date))
            {
                die('Error');
            }
        }
        redirect('admin/stock/dailystockcount');
    }
    
    public function periodicClosingStocksAction()
    {
        $data['periodic_stocks'] = getModel('stock')->getPeriodicStockTitles();
        $this->view->renderAdmin('stock/periodic/list.phtml',$data);
    }
    
    public function openperiodicstockAction()
    {
         $current_period = getModel('stockperiod')->getCurrentPeriod(date('Y-m-d'));
         if($current_period == 1) $previous_period = 52;
         else $previous_period = $current_period['period_number'] - 1;
         $month = date('m');
         $year = date('Y');
         if($month == '01') $previous_year = $year-1;
         else $previous_year = $year;
         
         $current_period_status = getModel('stock')->getPeriodStatus($current_period['period_number'], $year);
         if($current_period_status == 'open')
         {
             $data['current_period'] = $current_period;
                $data['opening_stocks'] = getModel('stock')->getOpeningStocks($current_period['period_number'], $year);
                $data['closing_stocks'] = getModel('stock')->getClosingStocks($current_period['period_number'], $year);
                $data['units'] = getModel('stock')->getUnits($previous_period, $previous_year);
                $data['categories'] = getModel('purchasecategory')->getActiveCollection();
                $this->view->renderAdmin('stock/periodic/form-before-final-save-with-data.phtml',$data);
         }
         else if($current_period_status == 'final saved')
         {
             $data['current_period'] = $current_period;
             $data['categories'] = getModel('purchasecategory')->getActiveCollection();
             $data['opening_stocks'] = getModel('stock')->getOpeningStocks($current_period['period_number'], $year);
             $data['closing_stocks'] = getModel('stock')->getClosingStocks($current_period['period_number'], $year);
             $data['purchased_stocks']=getModel('stock')->getPurchasedStocks($current_period['period_number'], $year);
             $data['consumed_stocks']=getModel('stock')->getConsumedStocks($current_period['period_number'], $year);
             $data['balances']=getModel('stock')->getBalanceStocks($current_period['period_number'], $year);
             $data['variances']=getModel('stock')->getVariances($current_period['period_number'], $year);
             $data['units'] = getModel('stock')->getUnits($previous_period, $previous_year);
             $this->view->renderAdmin('stock/periodic/form-after-final-save.phtml',$data);
             //echo 'here';
         }
         else if($current_period_status == 'closed')
         {}
         else if(!$current_period_status)
         {
             $previous_period_status = getModel('stock')->getPeriodStatus($previous_period,$previous_year);
            if($previous_period_status == 'closed')
            {
                $data['current_period'] = $current_period;
                $data['opening_stocks'] = getModel('stock')->getOpeningStocks($current_period['period_number'], $year);
                $data['units'] = getModel('stock')->getUnits($previous_period, $previous_year);
                $data['categories'] = getModel('purchasecategory')->getActiveCollection();
                $this->view->renderAdmin('stock/periodic/form-before-final-save.phtml',$data);
            }
            else if($previous_period_status == 'open')
            {
                $pv = getModel('stockperiod')->getCurrentPeriod(date('Y-m-d'),strtotime('2015-03-21'));
                $data['current_period'] = $pv;
                $data['opening_stocks'] = getModel('stock')->getOpeningStocks($previous_period, $previous_year);
                $data['closing_stocks'] = getModel('stock')->getClosingStocks($previous_period, $previous_year);
                $data['units'] = getModel('stock')->getUnits($previous_period, $previous_year);
                $data['categories'] = getModel('purchasecategory')->getActiveCollection();
                AdminSession::addErrorMessage('Please close previous stock period first');
                $this->view->renderAdmin('stock/periodic/form-before-final-save-with-data.phtml',$data);

            }
            else if($previous_period_status == 'final saved')
            {
                $current_period = getModel('stockperiod')->getCurrentPeriod(date('Y-m-d'),strtotime('2015-03-21'));
                $data['current_period'] = $current_period;
             $data['categories'] = getModel('purchasecategory')->getActiveCollection();
             $data['opening_stocks'] = getModel('stock')->getOpeningStocks($current_period['period_number'], $year);
             $data['closing_stocks'] = getModel('stock')->getClosingStocks($current_period['period_number'], $year);
             $data['purchased_stocks']=getModel('stock')->getPurchasedStocks($current_period['period_number'], $year);
             $data['consumed_stocks']=getModel('stock')->getConsumedStocks($current_period['period_number'], $year);
             $data['balances']=getModel('stock')->getBalanceStocks($current_period['period_number'], $year);
             $data['variances']=getModel('stock')->getVariances($current_period['period_number'], $year);
             $data['units'] = getModel('stock')->getUnits($previous_period, $previous_year);
              AdminSession::addErrorMessage('Please close previous stock period first');
             $this->view->renderAdmin('stock/periodic/form-after-final-save.phtml',$data);
            }

         }
    }
    
    public function saveperiodicstockAction()
    {
        loadHelper('inputs');
        $data = getPost();
        extract($data);
        $p = $period;
        $y = $year;
        getModel('stock')->DeleteClosingStock($p, $y);
        foreach($closing_stock as $pid => $cs)
        {
            $pname = $product_name[$pid];
            $os = $opening_stock[$pid];
            $u = $unit[$pid];
            getModel('stock')->InsertClosingStock($pid, $pname, $os, 0, 0, 0, $cs, 0, $u, NULL, $p, $y, 'open');
        }
        redirect('stock/periodicClosingStocks');
    }
    
    public function saveperiodicstockafterfinalsaveAction()
    {
        loadHelper('inputs');
        $data = getPost();
        var_dump($data);die;
    }
    
    public function finalsaveperiodicstockAction()
    {
        loadHelper('inputs');
        $data = getPost();
        extract($data);
        $p = $period;
        $y = $year;
        getModel('stock')->DeleteFinalSaveStock($p,$y);
        foreach($closing_stock as $pid => $cs)
        {
            $pname = $product_name[$pid];
            $os = $opening_stock[$pid];
            $u = $unit[$pid];
            getModel('stock')->InsertFinalSaveStock($pid, $pname, $os, $cs,$u, $p, $y);
            getModel('stock')->ChangeStatus($pid, $p, $y, $cs, 'final saved');
        }
    }
    
    public function testAction()
    {
        $product_id = 1;
        $p = 11;
        $y = 2015;
        getModel('stock')->getPurchases($product_id, $p, $y);
    }
}
