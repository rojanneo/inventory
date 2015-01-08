<?php
class StockController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		loadHelper('url');
	}

	public function indexAction()
	{
		$data['products'] = getModel('product')->getCollection(array('type'=>'AND','product_type'=>'in'));
		$stockfrtoday=getModel('stock')->getstockdatafortoday();
		if($stockfrtoday){$data['stockfrtoday']=$stockfrtoday;}
		$this->view->renderAdmin('stock/addstock.phtml',$data);
	}

	public function addPostAction()
	{
		loadHelper('inputs');
		$post_data = getPost(); 
		extract($post_data);
		if(getModel('stock')->addpost($post_data))
		{
		redirect('admin/stock');
		}
		else
		{
			$data['error']="Something went Wrong please try again";
			$this->view->renderAdmin('admin/stock',$data);
		}
	}

}