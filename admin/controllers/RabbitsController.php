<?php
class RabbitsController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		loadHelper('url');
	}

	public function indexAction()
	{
		$data['families'] = getModel('family')->getCollection();

		$this->view->renderAdmin('rabbits/list.phtml',$data);
	}

	public function performMatingAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		getModel('rabbit')->performMating($post_data['family_Male'], $post_data['family_Female']);

		redirect('admin/rabbits');
	}

	public function listActionsAction($rabbit_id)
	{
		$data['rabbit_id'] = $rabbit_id;
		$rabbit = getModel('rabbit')->load($rabbit_id);
		$this->view->renderAdmin('rabbits/actions.phtml',$data,false,false,false);
	}

	public function pregnantAction($rabbit_id)
	{
		getModel('rabbit')->makePregnant($rabbit_id);
		getModel('genealogy')->changeStatus($rabbit_id,0);
		redirect('admin/rabbits');
	}

	public function notpregnantAction($rabbit_id)
	{
		getModel('rabbit')->notPregnant($rabbit_id);
		redirect('admin/rabbits');
	}

	public function addLittersAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		$litter_group_id = getModel('genealogy')->addLitterGroup($post_data);
		$litters_count = $post_data['litters_count'];
		unset($post_data['litters_count']);
		for($i = 0; $i<$litters_count; $i++)
		{
			getModel('litter')->insert($post_data,$litter_group_id);
		}
		getModel('rabbit')->giveBirth($post_data['parent_rabbit_id']);
		redirect('admin/rabbits');
	}

	public function weanAction($rabbit_id)
	{

		$parent = getModel('rabbit')->load($rabbit_id);

		$data['product_type_id'] = 1;
		$data['attribute_set_id'] = 4;
		$data['product_name'] = 'Litters';
		$data['product_sku'] = 'litters_'.$rabbit_id;
		$data['daily_use_status'] = 0;
		$data['daily_use_quantity'] = 0;
		$data['product_quantity'] = 1;
		$data['in_stock'] = 1;
		$data['unit_price'] = 100;
		$data['status'] = 1;
		$data['is_variation'] = 0;
		$data['sort_order'] = 0;
		$data['created_date'] = date('Y-m-d');
		$data['updated_date'] = date('Y-m-d');
		$data['product_type'] = 'out';

		$product_id = getModel('product')->insert($data);
		$product['product_id']= $product_id;
		$product['rabbit_family_id'] = $parent['rabbit_family_id'];
		$product['rabbit_gender'] = 13;
		$product['rabbit_dob'] = date('Y-m-d');
		$product['rabbit_latest_mate_date'] = "";
		$product['rabbit_latest_pregnant_date'] = "";
		$product['rabbit_latest_birth_date'] ="";
		$product['rabbit_latest_weaning_date'] ="";
		$product['rabbit_latest_culling_date'] ="";
		$product['parent_id'] = $rabbit_id;

		getModel('product')->insertAttributes($product);

		$category['product_id'] = $product_id;
		$category['category_id'] = '4';
		getModel('product')->insertCategories($category);

		getModel('rabbit')->wean($rabbit_id);
		getModel('litter')->wean($rabbit_id);
		getModel('rabbit')->resetDates($rabbit_id);
		redirect('admin/rabbits');

	}

	public function cullAction($litter_id)
	{
		loadHelper('inputs');
		$post_data = getPost();
		$option = getModel('option')->load($post_data['group']);
		$group_name = $option['value'];
		$litter = getModel('litter')->load($litter_id);
		$data = array();	
		$data['product_type_id'] = 1;
		$data['attribute_set_id'] = 4;	
		$data['product_name'] = $group_name.'_Litter_'.$litter_id;
		$data['product_sku'] = strtolower($group_name).'_litter_'.$litter_id;
		$data['product_quantity'] = 1;
		$data['in_stock'] = 1;
		$data['unit_price'] = 100;
		$data['status'] = 1;
		$data['is_variation'] = 0;
		$data['created_date'] = date('Y-m-d');
		$data['updated_date'] = date('Y-m-d');
		$data['product_type'] = "out";
		$data['daily_use_status'] = 0;
		$attributes['parent_buck_id'] = $litter['parent_buck_id'];
		$attributes['parent_doe_id'] = $litter['parent_id'];
		$attributes['rabbit_gender'] = $post_data['gender'];
		$attributes['rabbit_family_id'] = $litter['family_id'];
		$attributes['rabbit_dob'] = $litter['litters_dob'];
		$attributes['rabbit_latest_culling_date'] = date('Y-m-d');
		$attributes['rabbit_group'] = $post_data['group'];
		$attributes['litter_id'] = $litter['litter_group_id'];
		getModel('litter')->delete($litter_id);
		$litter_rabbit = getModel('rabbit')->load($post_data['rabbit_id']);
		$remaining_litters = $litters = getModel('litter')->getCollection($litter_rabbit['parent_id']);
		if(count($remaining_litters) <= 0)
		{
			getModel('product')->delete(array('AND','product_id'=>$post_data['rabbit_id']));
		}

		$categories = array();
		array_push($categories,4);
		$categories['product_id'] =getModel('product')->insert($data);

		$attributes['product_id'] = $categories['product_id'];
		getModel('product')->insertAttributes($attributes);
		getModel('product')->insertCategories($categories);
		if($post_data['group'] == 19)
		{
			$genealogy_rabbit_data = array();
			$genealogy_rabbit_data['r_id'] = $categories['product_id'];
			$genealogy_rabbit_data['type'] = ($post_data['gender'] == '11')?'B':'D';
			$genealogy_rabbit_data['l_id'] = $litter['litter_group_id'];
			$genealogy_rabbit_data['f_id'] = 'NULL';
			$genealogy_rabbit_data['does_id'] = $litter['parent_id'];
			$genealogy_rabbit_data['buck_id'] = $litter['parent_buck_id'];
			$genealogy_rabbit_data['last_given_birth'] = 'NULL';
			$genealogy_rabbit_data['status'] = 0;
			$genealogy_rabbit_data['rabbit_slug'] = $data['product_sku'];

			getModel('genealogy')->insertRabbit($genealogy_rabbit_data);
		}

		redirect('admin/rabbits');

	}
	public function addToNewFamilyAction($rabbit_id)
	{
		$max_family = getModel('family')->getCollection();
		arsort($max_family);
		$max_family = (array_values($max_family)[0]);
		$newFamily = $max_family + 1;
		$rabbit = getModel('product')->load($rabbit_id);
		getModel('product')->updateAttribute($rabbit_id,'rabbit_family_id',$newFamily);
		getModel('product')->updateAttribute($rabbit_id, 'rabbit_group',18);
		getModel('product')->updateDefaultAttribute($rabbit_id, 'product_name','F'.$newFamily.'R'.$rabbit_id);
		getModel('product')->updateDefaultAttribute($rabbit_id, 'product_sku','f'.$newFamily.'_r'.$rabbit_id);
		redirect('admin/rabbits');
	}

	public function addToFamilyAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		$newFamily = $post_data['family_id'];
		$rabbit_id = $post_data['rabbit_id'];
		$rabbit = getModel('product')->load($rabbit_id);
		getModel('product')->updateAttribute($rabbit_id,'rabbit_family_id',$newFamily);
		getModel('product')->updateAttribute($rabbit_id, 'rabbit_group',18);
		getModel('product')->updateDefaultAttribute($rabbit_id, 'product_name','F'.$newFamily.'R'.$rabbit_id);
		getModel('product')->updateDefaultAttribute($rabbit_id, 'product_sku','f'.$newFamily.'_r'.$rabbit_id);
		redirect('admin/rabbits');

	}

	public function moveToProductsAction($rabbit_id)
	{
		$rabbit = getModel('product')->load($rabbit_id);
		getModel('product')->updateAttribute($rabbit_id,'rabbit_family_id',$newFamily);
		getModel('product')->updateAttribute($rabbit_id, 'rabbit_group',20);
		getModel('product')->updateDefaultAttribute($rabbit_id, 'product_name','Product Rabbit '.$rabbit_id);
		getModel('product')->updateDefaultAttribute($rabbit_id, 'product_sku','product_r_'.$rabbit_id);
		redirect('admin/rabbits');		
	}


}