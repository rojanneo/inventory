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

	public function notpregnantduringweaningAction($rabbit_id)
	{
		getModel('rabbit')->notpregnantduringweaning($rabbit_id);
		redirect('admin/rabbits');
	}

	public function pregnantAction($rabbit_id)
	{
		getModel('rabbit')->makePregnant($rabbit_id);
		getModel('genealogy')->changeStatus($rabbit_id,0);
		getModel('product')->updateAttribute($rabbit_id,'rabbit_feeding_group', '26');
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

	public function individualweanAction($rabbit_id)
	{
		loadHelper('inputs');
		$litter_id = getPost('litter_id');
		$litter = getModel('litter')->load($litter_id);
		$gender = getPost('gender');
		$parent = getModel('rabbit')->load($rabbit_id);

		$litters = getModel('litter')->getCollection($rabbit_id);
		$litter_count = getPost('litters_count');
		$max = (getModel('rabbit')->getMaxID()) + 1;
		$product_id = null;
		$data['product_type_id'] = 1;
		$data['attribute_set_id'] = 4;
		$data['product_name'] = $max;
		$data['product_sku'] = $max;

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
		$product['rabbit_gender'] = $gender;
		if($gender == '11') $product['rabbit_feeding_group'] = '24';
		else $product['rabbit_feeding_group'] = '25';

		$product['is_litter'] = 23;
		$product['weight'] = ($litter['litter_weight'] == 'NULL')?0:$litter['litter_weight'];
		$product['rabbit_dob'] = $litter['litters_dob'];
		$product['rabbit_latest_mate_date'] = "";
		$product['rabbit_latest_pregnant_date'] = "";
		$product['rabbit_latest_birth_date'] ="";
		$product['rabbit_latest_weaning_date'] =date('Y-m-d');
		$product['rabbit_latest_culling_date'] ="";
		$product['litter_id'] = $litter['litter_group_id'];
		$product['parent_doe_id'] = $rabbit_id;
		$product['parent_buck_id'] = $litter['parent_buck_id'];

		getModel('product')->insertAttributes($product);
		$category['product_id'] = $product_id;
		$category['category_id'] = '4';
		getModel('product')->insertCategories($category);
		getModel('litter')->wean($litter_id,$product_id);		if($litter_count <= 1)
		{
			$rb = getModel('rabbit')->load($rabbit_id);
		    $mate_d = new DateTime($rb['rabbit_latest_mate_date']); 
		    $birth_d = new DateTime($rb['rabbit_latest_birth_date']);

			getModel('rabbit')->wean($rabbit_id);
			if($mate_d > $birth_d)
				getModel('rabbit')->newresetDates($rabbit_id);
			else
				getModel('rabbit')->resetDates($rabbit_id);
		}
		redirect('admin/rabbits');


	}

	public function newindividualweanAction($rabbit_id)
	{
		loadHelper('inputs');
		$litter_id = getPost('litter_id');
		$litter = getModel('litter')->load($litter_id);
		$gender = getPost('gender');
		$parent = getModel('rabbit')->load($rabbit_id);

		$litters = getModel('litter')->getCollection($rabbit_id);
		$litter_count = getPost('litters_count');
		$max = (getModel('rabbit')->getMaxID()) + 1;
		$product_id = null;
		$data['product_type_id'] = 1;
		$data['attribute_set_id'] = 4;
		$data['product_name'] = $max;
		$data['product_sku'] = $max;

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
		$product['rabbit_gender'] = $gender;
		if($gender == '11') $product['rabbit_feeding_group'] = '24';
		else $product['rabbit_feeding_group'] = '25';

		$product['is_litter'] = 23;
		$product['weight'] = ($litter['litter_weight'] == 'NULL')?0:$litter['litter_weight'];
		$product['rabbit_dob'] = $litter['litters_dob'];
		$product['rabbit_latest_mate_date'] = "";
		$product['rabbit_latest_pregnant_date'] = "";
		$product['rabbit_latest_birth_date'] ="";
		$product['rabbit_latest_weaning_date'] =date('Y-m-d');
		$product['rabbit_latest_culling_date'] ="";
		$product['litter_id'] = $litter['litter_group_id'];
		$product['parent_doe_id'] = $rabbit_id;
		$product['parent_buck_id'] = $litter['parent_buck_id'];

		getModel('product')->insertAttributes($product);
		$category['product_id'] = $product_id;
		$category['category_id'] = '4';
		getModel('product')->insertCategories($category);
		getModel('litter')->wean($litter_id,$product_id);		if($litter_count <= 1)
		{
			getModel('rabbit')->wean($rabbit_id);
			getModel('rabbit')->newresetDates($rabbit_id);
		}
		redirect('admin/rabbits');


	}

	public function weanAction($rabbit_id)
	{
		loadHelper('inputs');
		$bucks = getPost('no_of_bucks');
		$parent = getModel('rabbit')->load($rabbit_id);

		$litters = getModel('litter')->getCollection($rabbit_id);
		$litter_count = count($litters);
		$max = (getModel('rabbit')->getMaxID());
		$product_id = null;
		foreach($litters as $litter)
		{
			$data['product_type_id'] = 1;
			$data['attribute_set_id'] = 4;
			$data['product_name'] = 'RB_'.$max;
			$data['product_sku'] = 'rb_'.$max;
			$max++;
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
			//option
			if($bucks > 0)
			{
				$product['rabbit_gender'] = 11;
				$bucks--;
			}
			else
			{
				$product['rabbit_gender'] = 12;
			}
			//option
			$product['is_litter'] = 23;
			$product['rabbit_dob'] = $l['litters_dob'];
			$product['rabbit_latest_mate_date'] = "";
			$product['rabbit_latest_pregnant_date'] = "";
			$product['rabbit_latest_birth_date'] ="";
			$product['rabbit_latest_weaning_date'] =date('Y-m-d');
			$product['rabbit_latest_culling_date'] ="";
			$product['litter_id'] = $litter['litter_group_id'];
			$product['parent_doe_id'] = $rabbit_id;
			$product['parent_buck_id'] = $litter['parent_buck_id'];

			getModel('product')->insertAttributes($product);

			$category['product_id'] = $product_id;
			$category['category_id'] = '4';
			getModel('product')->insertCategories($category);
			getModel('litter')->wean($litter['litter_id'],$product_id);
		}


		// $data['product_type_id'] = 1;
		// $data['attribute_set_id'] = 4;
		// $data['product_name'] = 'Litters';
		// $data['product_sku'] = 'litters_'.$rabbit_id;
		// $data['daily_use_status'] = 0;
		// $data['daily_use_quantity'] = 0;
		// $data['product_quantity'] = 1;
		// $data['in_stock'] = 1;
		// $data['unit_price'] = 100;
		// $data['status'] = 1;
		// $data['is_variation'] = 0;
		// $data['sort_order'] = 0;
		// $data['created_date'] = date('Y-m-d');
		// $data['updated_date'] = date('Y-m-d');
		// $data['product_type'] = 'out';

		// $product_id = getModel('product')->insert($data);
		// $product['product_id']= $product_id;
		// $product['rabbit_family_id'] = $parent['rabbit_family_id'];
		// $product['rabbit_gender'] = 13;
		// $product['rabbit_dob'] = date('Y-m-d');
		// $product['rabbit_latest_mate_date'] = "";
		// $product['rabbit_latest_pregnant_date'] = "";
		// $product['rabbit_latest_birth_date'] ="";
		// $product['rabbit_latest_weaning_date'] ="";
		// $product['rabbit_latest_culling_date'] ="";
		// $product['parent_id'] = $rabbit_id;

		// getModel('product')->insertAttributes($product);

		// $category['product_id'] = $product_id;
		// $category['category_id'] = '4';
		// getModel('product')->insertCategories($category);

		getModel('rabbit')->wean($rabbit_id);

		getModel('rabbit')->resetDates($rabbit_id);
		redirect('admin/rabbits');

	}

	public function cullAction($litter_id)
	{
		loadHelper('inputs');
		$post_data = getPost();
		$option = getModel('option')->load($post_data['group']);
		$group_name = $option['value'];
		$litter = getModel('litter')->loadByRabbitId($post_data['rabbit_id']);
		$rabbit = getModel('rabbit')->load($post_data['rabbit_id']);
		getModel('product')->updateAttribute($rabbit['product_id'],'rabbit_group',$post_data['group']);
		getModel('product')->updateAttribute($rabbit['product_id'],'is_litter','22');
		getModel('product')->updateAttribute($rabbit['product_id'],'rabbit_latest_culling_date',date('Y-m-d'));
		if($post_data['group'] == 19)
		{

			$genealogy_rabbit_data = array();
			$genealogy_rabbit_data['r_id'] = $post_data['rabbit_id'];
			$genealogy_rabbit_data['type'] = ($rabbit['rabbit_gender'] == 'Male')?'B':'D';
			$genealogy_rabbit_data['l_id'] = $litter['litter_group_id'];
			$genealogy_rabbit_data['f_id'] = 'NULL';
			$genealogy_rabbit_data['does_id'] = $litter['parent_id'];
			$genealogy_rabbit_data['buck_id'] = $litter['parent_buck_id'];
			$genealogy_rabbit_data['last_given_birth'] = 'NULL';
			$genealogy_rabbit_data['status'] = 0;
			$genealogy_rabbit_data['rabbit_slug'] = $rabbit['product_sku'];
			getModel('genealogy')->insertRabbit($genealogy_rabbit_data);
		}
		getModel('litter')->delete($litter['litter_id']);
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
		// getModel('product')->updateDefaultAttribute($rabbit_id, 'product_name','F'.$newFamily.'R'.$rabbit_id);
		// getModel('product')->updateDefaultAttribute($rabbit_id, 'product_sku','f'.$newFamily.'_r'.$rabbit_id);
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
		// getModel('product')->updateDefaultAttribute($rabbit_id, 'product_name','F'.$newFamily.'R'.$rabbit_id);
		// getModel('product')->updateDefaultAttribute($rabbit_id, 'product_sku','f'.$newFamily.'_r'.$rabbit_id);
		redirect('admin/rabbits');

	}

	public function moveToProductsAction($rabbit_id)
	{
		$rabbit = getModel('product')->load($rabbit_id);
		getModel('product')->updateAttribute($rabbit_id,'rabbit_family_id',$newFamily);
		getModel('product')->updateAttribute($rabbit_id, 'rabbit_group',20);
		// getModel('product')->updateDefaultAttribute($rabbit_id, 'product_name','Product Rabbit '.$rabbit_id);
		// getModel('product')->updateDefaultAttribute($rabbit_id, 'product_sku','product_r_'.$rabbit_id);
		redirect('admin/rabbits');		
	}

	public function deathActionsAction($rabbit_id = false) // Ajax request to a page
	{
            if($rabbit_id)
		$data['rabbit_id'] = $rabbit_id;
            else
            {
                loadHelper('inputs');
                $data['rabbit_id'] = getParam('id');
            }

		$this->view->renderAdmin('rabbits/death.phtml',$data,false,false,false);
	}
	public function deathlitterActionsAction($litter_id) // Ajax request to a page
	{
		$data['litter_id'] = $litter_id;
		$this->view->renderAdmin('rabbits/death.phtml',$data,false,false,false);
	}

	public function stillbirthAction($rabbit_id)
	{
		$data['rabbit_id'] = $rabbit_id;
		$this->view->renderAdmin('rabbits/stillbirth.phtml',$data,false,false,false);
	}

	public function deathprocessAction() // Death process
	{
		loadHelper('inputs');
		$post_data = getPost(); 
		if($post_data)
		{
			$do=getModel('rabbit')->deathentry($post_data);
			if($do)
			{
				redirect('admin/rabbits');
			}
		}
	}

	public function stillbirthProcessAction() // Death process
	{
		loadHelper('inputs');
		$post_data = getPost(); 
		if($post_data)
		{
			$data['rabbit_id'] = $post_data['rabbit_id'];
			$data['still_birth_date'] = date('Y-m-d');
			$data['still_birth_reason'] = $post_data['still_birth_reason']; 
			$do=getModel('stillbirth')->insert($data);
			if($do)
			{
				getModel('rabbit')->resetDates($post_data['rabbit_id']);
				redirect('admin/rabbits');
			}
		}
	}

	public function updateLitterWeightAction()
	{
		loadHelper('inputs');
		$post_data = getPost();

		getModel('litter')->updateWeights($post_data);
		redirect('admin/rabbits');
	}

	public function listAction($status = false)
	{
		if($status != false)
		{
			$available_to_mate_array = array();
			$mated_array = array();
			$pregnant_array = array();
			$not_available_to_wean_array = array();
			$available_to_wean_array = array();
			$not_available_to_cull_array = array();			
			$available_to_cull_array = array();		
			$parents_to_be_array = array();
			$products_to_be_array = array();	

			$rabbits = getModel('rabbit')->getcollection();
			foreach($rabbits as $r)
			{
				$rabbit = getModel('rabbit')->load($r['product_id']);
				$is_not_litter = $rabbit['is_litter'] != 'yes';
				$is_not_parents_to_be = (isset($rabbit['rabbit_group']))?$rabbit['rabbit_group'] != 'Parents to be':false;
				$is_not_products_to_be = (isset($rabbit['rabbit_group']))?$rabbit['rabbit_group'] != 'Products to be ':false; 
				if($rabbit['rabbit_gender'] == 'Male' and $is_not_litter == true and $is_not_parents_to_be == true and $is_not_products_to_be == true)
				{
					array_push($available_to_mate_array, $rabbit['product_id']);
				}
				else if($is_not_litter == false)
				{
					if(!isset($rabbit['rabbit_latest_culing_date']) or $rabbit['rabbit_latest_culing_date'])
					{
						if(isset($rabbit['rabbit_latest_weaning_date']))
						{
							$today = new DateTime(date('Y-m-d'));
							$wean_date = new DateTime($rabbit['rabbit_latest_weaning_date']);
							$cull_diff = $today->diff($wean_date)->format("%a");
							if($cull_diff <= 60)
								array_push($not_available_to_cull_array, $rabbit['product_id']);
							else
								array_push($available_to_cull_array, $rabbit['product_id']);
						}
					}
				}
				else if($is_not_parents_to_be == false)
				{
					array_push($parents_to_be_array,$rabbit['product_id']);
				}
				else if($is_not_products_to_be == false)
				{
					array_push($products_to_be_array,$rabbit['product_id']);
				}
				else if($rabbit['rabbit_gender'] == 'Female' and $is_not_litter == true and $is_not_parents_to_be == true and $is_not_products_to_be == true)
				{
					if(!isset($rabbit['rabbit_latest_mate_date']) or !$rabbit['rabbit_latest_mate_date'])
						array_push($available_to_mate_array, $rabbit['product_id']);
					else if(!isset($rabbit['rabbit_latest_pregnant_date']) or !$rabbit['rabbit_latest_pregnant_date'])
					{
						$today = new DateTime(date('Y-m-d'));
						$mate_date = new DateTime($rabbit['rabbit_latest_mate_date']);
						$mate_diff = $today->diff($mate_date)->format("%a");
						if(isset($rabbit['rabbit_latest_mate_date']))
						{
							array_push($mated_array, $rabbit['product_id']);
						}
					}
					else if(!isset($rabbit['rabbit_latest_birth_date']) or !$rabbit['rabbit_latest_birth_date'])
					{
						if(isset($rabbit['rabbit_latest_pregnant_date']))
						{
							array_push($pregnant_array, $rabbit['product_id']);
						}
					}
					else if(isset($rabbit['rabbit_latest_birth_date']))
					{
						$today = new DateTime(date('Y-m-d'));
						$wean_date = new DateTime($rabbit['rabbit_latest_birth_date']);
						$wean_diff = $today->diff($wean_date)->format("%a");
						if($wean_diff < 21)
							array_push($not_available_to_wean_array, $rabbit['product_id']);
						if($wean_diff >= 21 and $wean_diff <= 28)
							array_push($available_to_wean_array, $rabbit['product_id']);
					}
				}
			}

			if($status == 'available_to_mate')
			{
				$data['rabbits'] = $available_to_mate_array;
			}
			else if($status == 'mated')
				$data['rabbits'] = $mated_array;
			else if($status == 'pregnant')
				$data['rabbits'] = $pregnant_array;
			else if($status == 'wait_to_wean')
				$data['rabbits'] = $not_available_to_wean_array;
			else if($status == 'weaning')
				$data['rabbits'] = $available_to_wean_array;
			else if($status == 'wait_to_cull')
				$data['rabbits'] = $not_available_to_cull_array;
			else if($status == 'culling')
				$data['rabbits'] = $available_to_cull_array;
			else if($status == 'parents_to_be')
				$data['rabbits'] = $parents_to_be_array;
			else if($status == 'products_to_be')
				$data['rabbits'] = $products_to_be_array;

			$data['status'] = $status;
			$this->view->renderAdmin('rabbits/data.phtml',$data);

				// var_dump($available_to_mate_array);
				// var_dump($mated_array);
				// var_dump($pregnant_array);
				// var_dump($not_available_to_wean_array);
				// var_dump($available_to_wean_array);
				// var_dump($not_available_to_cull_array);
				// var_dump($available_to_cull_array);
		}
		else redirect('admin/rabbits');
	}


	/*Lists all the rabbit that are about to be pregnant,birth,weaning  after and before 2 days
	*/ 
	public function notificationAction()
	{

			$mateddays10=array();
			$mateddays23=array();
			$pregnantday13=array();
			$pregnantday18=array();
			$weaning2days_before=array();
			$weaning2days_after=array();
			$today = new DateTime(date('Y-m-d'));
		$rabbits = getModel('rabbit')->getcollection();
			foreach($rabbits as $r)
			{
				$rabbit = getModel('rabbit')->load($r['product_id']); //var_dump($rabbit); die();
				$is_not_litter = $rabbit['is_litter'] != 'yes';
				$is_not_parents_to_be = (isset($rabbit['rabbit_group']))?$rabbit['rabbit_group'] != 'Parents to be':false;
				$is_not_products_to_be = (isset($rabbit['rabbit_group']))?$rabbit['rabbit_group'] != 'Products to be ':false; 

				//female check
				if(($rabbit['rabbit_gender'] == 'Female')): 
				if(isset($rabbit['rabbit_latest_mate_date']) and (!isset($rabbit['rabbit_latest_pregnant_date']))): // lateest mate date				
				$mate_date = new DateTime($rabbit['rabbit_latest_mate_date']);
				$mate_diff = $today->diff($mate_date)->format("%a");
				if(11>=$mate_diff and $mate_diff>=10 ) { array_push($mateddays10, $rabbit['product_id']); }
				if(24>=$mate_diff and $mate_diff>=23) { array_push($mateddays23,$rabbit['product_id']);}
				endif; // end latesst mate date	
				//Birthcheck

				if((!isset($rabbit['rabbit_latest_birth_date']) or !$rabbit['rabbit_latest_birth_date']) and (isset($rabbit['rabbit_latest_pregnant_date']))):
				$mate_date = new DateTime($rabbit['rabbit_latest_mate_date']);
				$mate_diff = $today->diff($mate_date)->format("%a");
					if(26>=$mate_diff and $mate_diff>=25) { array_push($pregnantday13,$rabbit['product_id']); }
				if(31>=$mate_diff and $mate_diff>=30) { array_push($pregnantday18,$rabbit['product_id']); }
				endif;//End Of Birthcheck

											

				//weaning
				if(isset($rabbit['rabbit_latest_birth_date'])):
				$birth_date=new DateTime($rabbit['rabbit_latest_birth_date']);
				$birth_diff = $today->diff($birth_date)->format("%a");
				if(20>=$birth_diff and $birth_diff>=19){ array_push($weaning2days_before,$rabbit['product_id']);}
				if(27>=$birth_diff and $birth_diff>=26){ array_push($weaning2days_after,$rabbit['product_id']);}
				endif;
				//End of weaning

				endif;	//female check end	
				
			}
			if(isset($mateddays10)) { $data['mateddays10']=$mateddays10; }
			if(isset($mateddays23)) { $data['mateddays23']=$mateddays23; }
			if(isset($pregnantday13)) { $data['pregnantday13']=$pregnantday13; }
			if(isset($pregnantday18)) { $data['pregnantday18']=$pregnantday18; }
			if(isset($weaning2days_before)) { $data['weaning2days_before']=$weaning2days_before; }
			if(isset($weaning2days_after)) { $data['weaning2days_after']=$weaning2days_after; }
			$this->view->renderAdmin('rabbits/notification.phtml',$data); 
		
	}

}
