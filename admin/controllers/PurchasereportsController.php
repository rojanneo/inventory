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
class PurchasereportsController extends Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function indexAction()
    {
        $data['suppliers'] = getModel('supplier')->getActiveCollection();
        $data['categories'] = getModel('purchasecategory')->getActiveCollection();
        $this->view->renderAdmin('reports/purchase/form.phtml',$data);
    }
    
    public function generateReportAction()
    {
        loadHelper('inputs');
        $post_data = getPost();
        if($post_data)
        {
            
        }
    }
    
    
    //put your code here
}
