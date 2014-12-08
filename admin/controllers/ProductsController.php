<?php
class ProductsController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		loadHelper('url');
	}

	public function indexAction()
	{
		$data['products'] = getModel('product')->getCollection();
		$this->view->renderAdmin('products/list.phtml',$data);
	}

	public function addAction()
	{
		loadHelper('inputs');
		$type = getParam('type');
		$set = getParam('set');

		if(!$type and !$set)
		{
			$data['product_types'] = getModel('producttype')->getCollection();
			$data['attribute_sets'] = getModel('attributeset')->getCollection();
			$this->view->renderAdmin('products/selectType.phtml',$data);
		}
		else
		{
			$data['product_type'] = $type;
			$data['attribute_set'] = $set;
			$data['attributes'] = getModel('attributeset')->getAttributes(array('AND','attribute_set_id'=>$set));
			$data['categories'] = getModel('category')->getCollection();
			$this->view->renderAdmin('products/new.phtml',$data);
		}
	}

	public function addPostAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		$attributes = $post_data['attributes'];
		unset($post_data['attributes']);
		$categories = $post_data['categories'];
		unset($post_data['categories']);
		$product_id = getModel('product')->insert($post_data);
		$attributes['product_id'] = $product_id;
		$categories['product_id'] = $product_id;
		getModel('product')->insertAttributes($attributes);
		getModel('product')->insertCategories($categories);
		redirect('admin/products/add');
	}

	public function editAction($product_id)
	{
		$product = getModel('product')->load($product_id);
		$data['product'] = $product;
		$data['attributes'] = getModel('attributeset')->getAttributes(array('AND','attribute_set_id'=>$product['attribute_set_id']));
		$data['categories'] = getModel('category')->getCollection();
		$this->view->renderAdmin('products/edit.phtml',$data);
	}

	public function editPostAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		$product_id = $post_data['product_id'];
		$attributes = $post_data['attributes'];
		$categories = $post_data['categories'];
		unset($post_data['attributes']);
		unset($post_data['cateogries']);
		getModel('product')->update($post_data);
		getModel('product')->updateAttributes($attributes,$product_id);
		getModel('product')->updateCategories($categories,$product_id);
		redirect('admin/products');
	}

	public function deleteAction($product_id)
	{
		getModel('product')->delete(array('type'=>'AND','product_id'=>$product_id));
		redirect('admin/products');
	}
}