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
		getModel('genealogy')->addLitterGroup($post_data);
		$litters_count = $post_data['litters_count'];
		unset($post_data['litters_count']);
		for($i = 0; $i<$litters_count; $i++)
		{
			getModel('litter')->insert($post_data);
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
		$product['litters_born'] ="";
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


}