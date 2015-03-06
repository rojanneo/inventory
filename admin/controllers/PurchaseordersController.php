<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseordersController
 *
 * @author Neo
 */
class PurchaseordersController extends Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function indexAction()
    {}
    
    public function newAction()
    {
        $data['suppliers'] = getModel('supplier')->getActiveCollection();
        $data['categories'] = getModel('purchasecategory')->getActiveCollection();
        $data['products'] = getModel('purchaseproduct')->getActiveCollection();
        
        $this->view->renderAdmin('purchaseorders/form.phtml',$data);
    }
    
    public function getProductsAction()
    {
        loadHelper('inputs');
        $supplier = getPost('supplier');
        $category = getPost('category');
        
        $data['products'] = getModel('purchaseproduct')->getFilteredProducts($supplier,$category,1);
        $html = $this->view->renderWithoutAnything('purchaseorders/table.phtml',$data);
        echo $html;
    }
    //put your code here
}
