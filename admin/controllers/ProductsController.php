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

	public function addLittersAction()
	{
		loadHelper('inputs');
		$type = 1;
		$set = 4;

			if($set==4){ $data['ID'] = getModel('product')->getAutoIncrementID(); }	
			$data['product_type'] = $type;
			$data['attribute_set'] = $set;
			$data['attributes'] = getModel('attributeset')->getAttributes(array('AND','attribute_set_id'=>$set));
			$data['categories'] = getModel('category')->getCollection();
			$this->view->renderAdmin('products/newlitter.phtml',$data);
		
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
			if($_GET['set']==4){ $data['ID'] = getModel('product')->getAutoIncrementID(); }			
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
		$attribute_set_id = $post_data['attribute_set_id'];
		$group = $post_data['attributes']['rabbit_group'];
		$parent_buck = $post_data['parent_buck_id'];
		$parent_doe = $post_data['parent_doe_id'];
		$gender = $post_data['attributes']['rabbit_gender'];
		$attributes = $post_data['attributes'];
		$attributes['parent_buck_id'] = $parent_buck;
		$attributes['parent_doe_id'] = $parent_doe;
		unset($post_data['attributes']);
		$categories = $post_data['categories'];
		unset($post_data['categories']);
		$product_id = getModel('product')->insert($post_data);
		$attributes['product_id'] = $product_id;
		$categories['product_id'] = $product_id;
		getModel('product')->insertAttributes($attributes);
		getModel('product')->insertCategories($categories);
		if($attribute_set_id == 4 and $group == 18)
		{
			$genealogy_rabbit_data = array();
			$genealogy_rabbit_data['r_id'] = $product_id;
			$genealogy_rabbit_data['type'] = ($gender == '11')?'B':'D';
			$genealogy_rabbit_data['l_id'] = 'NULL';
			$genealogy_rabbit_data['f_id'] = $attributes['rabbit_family_id'];
			$genealogy_rabbit_data['does_id'] = $parent_doe;
			$genealogy_rabbit_data['buck_id'] = $parent_buck;
			$genealogy_rabbit_data['last_given_birth'] = 'NULL';
			$genealogy_rabbit_data['status'] = 1;
			$genealogy_rabbit_data['rabbit_slug'] = $post_data['product_sku'];

			getModel('genealogy')->insertRabbit($genealogy_rabbit_data);
		}
		redirect('admin/products/add');
	}

	public function addLitterPostAction()
	{
		loadHelper('inputs');
		$post_data = getPost();

		$attribute_set_id = $post_data['attribute_set_id'];
		$group = $post_data['attributes']['rabbit_group'];
		$parent_buck = $post_data['parent_buck_id'];
		$parent_doe = $post_data['parent_doe_id'];
		$gender = $post_data['attributes']['rabbit_gender'];
		$attributes = $post_data['attributes'];
		$attributes['parent_buck_id'] = $parent_buck;
		$attributes['parent_doe_id'] = $parent_doe;
		unset($post_data['attributes']);
		$categories = $post_data['categories'];
		unset($post_data['categories']);
		$group_data['rabbit_family_id'] = $attributes['rabbit_family_id'];
		$litter_group = getModel('genealogy')->addLitterGroup($group_data);

		$product_id = getModel('product')->insert($post_data);

		$rabbitLitterData['parent_rabbit_id'] = $parent_doe;
		$rabbitLitterData['parent_buck_id'] = $parent_buck;
		$rabbitLitterData['rabbit_family_id'] = $attributes['rabbit_family_id'];
		$rabbitLitterData['litter_dob'] = $attributes['rabbit_dob'];
		$rabbitLitterData['rabbit_id'] = $product_id;

		$rabbit_litter = getModel('litter')->insert($rabbitLitterData,$litter_group);
		$attributes['product_id'] = $product_id;
		$categories['product_id'] = $product_id;
		getModel('product')->insertAttributes($attributes);
		getModel('product')->insertCategories($categories);
		
		redirect('admin/products/addLitters');
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
		getModel('genealogy')->deleteRabbit($product_id);
		redirect('admin/products');
	}
}