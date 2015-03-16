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
}
