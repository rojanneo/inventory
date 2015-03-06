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
    
    public function addAction()
    {
        echo '<pre>';
        loadHelper('inputs');
        $post_data = getPost();
        $suppliers = getPost('suppliers');
        $unit_prices = getPost('unit_prices');
        $total_prices = getPost('total_prices');
        
        foreach($post_data['quantities'] as $product_id => $quantity)
        {
            if($quantity != 0)
                echo $product_id.'-> '.$suppliers[$product_id].', '.$quantity.', '.$unit_prices[$product_id].', '.$total_prices[$product_id];
            
            echo '<br>';
        }
        //var_dump($post_data);die;
    }
    //put your code here
}
