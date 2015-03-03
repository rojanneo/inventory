<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseproductsController
 *
 * @author Neo
 */
class PurchaseproductsController extends Controller{
    public function __construct() {
        parent::__construct();
        loadHelper('url');
    }
    
    public function indexAction()
    {
        
    }
    
    public function newAction()
    {
        $data['units'] = getModel('uom')->getUnits();
        $data['suppliers'] = getModel('supplier')->getcollection();
        $data['categories'] = getModel('purchasecategory')->getCollection();
        $this->view->renderAdmin('purchaseproducts/form.phtml',$data);
    }
    
    public function newpostAction()
    {
        loadHelper('inputs');
        $post_data = getPost();
        $product_id = getModel('purchaseproduct')->insert($post_data);
        if($product_id)
        {
            getModel('purchaseproduct')->insertCategories($post_data['categories'], $product_id);
            getModel('purchaseproduct')->insertSuppliers($post_data['suppliers'],$product_id);
            AdminSession::addSuccessMessage('Product Added');
        }
        else
            AdminSession::addErrorMessage ('Failet to add Product');
        
        redirect('admin/purchaseproducts');
    }
    
    public function editAction($id)
    {
        $data['product'] = getModel('purchaseproduct')->load($id);
        $data['product_categories'] = getModel('purchaseproduct')->getCategories($id);
        $data['product_suppliers'] = getModel('purchaseproduct')->getSuppliers($id);
        $data['units'] = getModel('uom')->getUnits();
        $data['suppliers'] = getModel('supplier')->getcollection();
        $data['categories'] = getModel('purchasecategory')->getCollection();
        
        $this->view->renderAdmin('purchaseproducts/form.phtml',$data);
    }
    
    public function editpostAction()
    {
        loadHelper('inputs');
        $post_data = getPost();
        if($post_data['product_id'])
        {
            if(getModel('purchaseproduct')->update($post_data))
            {
                getModel('purchaseproduct')->updateCategories($post_data['categories'],$post_data['product_id']);
                getModel('purchaseproduct')->updateSuppliers($post_data['suppliers'],$post_data['product_id']);
                AdminSession::addSuccessMessage('Product Added');
            }
            else
            AdminSession::addErrorMessage ('Failet to add Product');
        }
        
        redirect('admin/purchaseproducts');
    }
}
