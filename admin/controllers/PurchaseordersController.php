<?php
class PurchaseordersController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		loadHelper('url');
	}

	public function indexAction()
	{

	}

	public function addAction()
	{
		$this->view->renderAdmin('purchase_orders/new.phtml');
	}

	public function addPostAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		extract($post_data['product']);
		$purchase_order_id = getModel('purchaseorder')->insert();
		foreach($product_id as $key => $id)
		{
			$po_product = array('purchase_order_id'=>$purchase_order_id,'product_id'=>$id, 'unit_price'=>$unit_price[$key], 'quantity'=>$quantity[$key], 'total_price'=>$total_price[$key]);
			getModel('purchaseorderproduct')->insert($po_product);
		}
		redirect('admin/products');
	}
}