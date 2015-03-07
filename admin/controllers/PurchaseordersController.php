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
        loadHelper('url');
    }
    
    public function indexAction()
    {
    	$data['purchase_orders'] = getModel('purchaseorder')->getPOGroupCollection();
    	$this->view->renderAdmin('purchaseorders/list.phtml',$data);
    }
    
    public function newAction()
    {
        $data['suppliers'] = getModel('supplier')->getActiveCollection();
        $data['categories'] = getModel('purchasecategory')->getActiveCollection();
        $data['products'] = getModel('purchaseproduct')->getActiveCollection();
        
        $this->view->renderAdmin('purchaseorders/form.phtml',$data);
    }

    public function completeAction($id)
    {
    	$po_group = getModel('purchaseorder')->loadPurchaseOrderGroup($id);

    	if($po_group)
    	{
    		$purchase_orders = getModel('purchaseorder')->loadPurchaseOrderByGroup($po_group['id']);
    		if($purchase_orders)
    		{
    			$data['po_group'] = $po_group;
    			$data['purchase_orders'] = $purchase_orders;
    			$this->view->renderAdmin('purchaseorders/complete-form.phtml',$data);
    		}
    		else die('No orders');
    	}
    	else
    		die('No such po');
    }

    public function reopenAction($id)
    {
    	getModel('purchaseorder')->reopen($id);
    	redirect('admin/purchaseorders/complete/'.$id);
    }

    public function cancelAction($id)
    {
    	getModel('purchaseorder')->cancel($id);
    	redirect('admin/purchaseorders');
    }

    public function getFilteredPOAction()
    {
    	loadHelper('inputs');
    	$status = getPost('status');
    	$data['purchase_orders'] = getModel('purchaseorder')->getPOGroupCollection($status);
    	$html = $this->view->renderWithoutAnything('purchaseorders/ajax-list.phtml',$data);
    	echo $html;
    }

    public function completePostAction()
    {
    	loadHelper('inputs');
    	$post_data = getPost();
    	if($post_data['complete'] == 0)
    	{
    		getModel('purchaseorder')->save($post_data);
    		redirect('admin/purchaseorders/complete/'.$post_data['po_group_id']);
    	}
    	elseif($post_data['complete'] == 1)
    	{
    		getModel('purchaseorder')->save($post_data);
    		getModel('purchaseorder')->complete($post_data);
    		redirect('admin/purchaseorders/complete/'.$post_data['po_group_id']);    		
    	}
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
        //var_dump($post_data);die;
        $suppliers = getPost('suppliers');
        $unit_prices = getPost('unit_prices');
        $total_prices = getPost('total_prices');
        $units = getPost('units');
        $status = 1;
        $po_date = $post_data['po_date'];
        $is_realtime = $post_data['is_realtime'];
        $employee_id = $post_data['employee_id'];
        
        if($po_group_id = getModel('purchaseorder')->insertGroup($po_date,$status,array_sum($total_prices),$is_realtime,$employee_id))
        {
            foreach($post_data['quantities'] as $product_id => $quantity)
	        {
	            if($quantity != 0)
	            {
	            	$data['po_group_id'] = $po_group_id;
	            	$data['product_id'] = $product_id;
	            	$data['quantity'] = $quantity;
	            	$data['supplier_id'] = $suppliers[$product_id];
	            	$data['unit_price'] = $unit_prices[$product_id];
	            	$data['unit'] = $units[$product_id];
	            	$data['total_price'] = $total_prices[$product_id];
	            	$data['is_complete'] = 0;

	            	getModel('purchaseorder')->insert($data);

	            }
	            
	            //echo '<br>';
	        }

        }

        redirect('admin/purchaseorders/new');

        //var_dump($post_data);die;
    }
    //put your code here
}
