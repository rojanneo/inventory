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
}
