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
		$po = getModel('purchaseorder')->getCollection();
		$data['purchase_orders'] = $po;
		$this->view->renderAdmin('purchase_orders/list.phtml', $data);
	}

	public function addAction()
	{
		$data['products'] = getModel('product')->getCollection(array('type'=>'AND','product_type'=>'in'));
		$this->view->renderAdmin('purchase_orders/new.phtml',$data);
	}

	public function addPostAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		extract($post_data['product']);
		$purchase_order_id = getModel('purchaseorder')->insert();
		foreach($product_id as $key => $id)
		{
			$product = getModel('product')->load($id);
			$po_product = array('purchase_order_id'=>$purchase_order_id,'product_id'=>$id, 'product_name'=>$product['product_name'], 'product_sku'=>$product['product_sku'], 'unit_price'=>$unit_price[$key], 'quantity'=>$quantity[$key], 'total_price'=>$total_price[$key]);
			getModel('purchaseorderproduct')->insert($po_product);

			$product = getModel('product')->load($id);
			$current_quantity = $product['product_quantity'];
			$new_quantity = $current_quantity + $quantity[$key];
			getModel('product')->updateDefaultAttribute($id, 'product_quantity', $new_quantity);
			if($product['in_stock'] == 0)
				getModel('product')->updateDefaultAttribute($id, 'in_stock', 1);
		}
		redirect('admin/products');
	}

	public function viewAction($po_id)
	{
		$data['purchase_order'] = getModel('purchaseorder')->load($po_id);
		$this->view->renderAdmin('purchase_orders/view.phtml',$data);
	}

}