<?php
class DailyfeedsController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		loadHelper('url');
	}

	public function indexAction()
	{
		//getModel('dailyfeed')->deductDailyFeeds();

//		$feeding_groups = getModel('attribute')->load(array('attribute_code'=>'rabbit_feeding_group'));
//		$feeding_groups = getModel('attribute')->getOptions($feeding_groups['attribute_id']);
//		//echo '<pre>';
//		//var_dump($feeding_groups);
//
//		$dailyfeeds = getModel('dailyfeed')->getCollection();
//
//		if($dailyfeeds)
//		{
//			$data['dailyfeeds'] = $dailyfeeds;
//			$data['feeding_groups'] = $feeding_groups;
//			$this->view->renderAdmin('dailyfeeds/table.phtml',$data);
//		}
//		else
//		{
//
//			$data['feeding_groups'] = $feeding_groups;
//
//			$this->view->renderAdmin('dailyfeeds/table.phtml',$data);
//		}

		$data['feeding_groups']= getModel('feedinggroup')->getCollection();
		$data['weight_groups'] = getModel('weightgroup')->getCollection();

		$data['products'] = getModel('purchaseproduct')->getDailyFeeds();

		$this->view->renderAdmin('dailyfeeds/form.phtml',$data);
	}

	public function dailyfeedpostAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		echo '<pre>';
		getModel('dailyfeed')->saveDailyFeeds($post_data);
		redirect('dailyfeeds');

	}

	public function useFeedAction()
	{
		error_reporting(0);
		$rabbits = getModel('rabbit')->getCollection();
		echo '<pre>';
		$adult_male_count = 0;
		$adult_female_count = 0;
		$pregnant_lactating_count = 0;
		$weaned_litters = 0;
		$count = array();
		$products = getModel('purchaseproduct')->getDailyFeeds();
		foreach($products as $product)
		{
			foreach($rabbits as $rabbit)
			{
				$r = getModel('rabbit')->load($rabbit['product_id']);
				if($r['weight'] == 0) $w = 1;
				else $w = $r['weight'];
				$wg = getModel('weightgroup')->getWeightGroupFromWeight($w);
				if((!isset($r['rabbit_latest_weaning_date']) or !$r['rabbit_latest_weaning_date']) or ($r['rabbit_latest_weaning_date'] and (isset($r['rabbit_latest_culling_date']) and $r['rabbit_latest_culling_date'])))
				{
					if($r['rabbit_gender'] == 'Male')
					{
						$count[$product['product_id']][1][$wg['id']]++;
						$adult_male_count++;
					}
					else if($r['rabbit_gender'] == 'Female')
					{
						if(isset($r['rabbit_feeding_group']) and $r['rabbit_feeding_group'] == 'Pregnant/Lactating')
						{
							$count[$product['product_id']][3][$wg['id']]++;
							$pregnant_lactating_count++;
						}
						else
						{
							$count[$product['product_id']][2][$wg['id']]++;
							$adult_female_count++;
						}

					}
				}


			}

			$weaned_litters = (getModel('litter')->getWeanedLitters());
			$weaned_litters_count = count($weaned_litters);
			$unweaned_litters = (getModel('litter')->getUnweanedLitters());
			$unweaned_litters_count = count($unweaned_litters);
			// echo 'Male: '.$adult_male_count.'<br>';
			// echo 'Adult Female: '.$adult_female_count.'<br>';
			// echo 'Pregnant/Lactating Female: '.$pregnant_lactating_count.'<br>';
			// echo 'Weaned Litters: '.$weaned_litters_count.'<br>';
			// echo 'Unweaned Litters: '.$unweaned_litters_count.'<br>';

			foreach($weaned_litters as $l)
			{
				$wl = getModel('rabbit')->load($l['rabbit_id']);
				if($wl['weight'] == 0) $w = 0.64;
				else $w = $wl['weight'];
				$wg = getModel('weightgroup')->getWeightGroupFromWeight($w); 
				if($wg['id'])
					$count[$product['product_id']][4][$wg['id']]++;

			}

			foreach($unweaned_litters as $l)
			{
				if($l['litter_weight'] == NULL or $l['litter_weight'] == 0) $w = 0.66;
				else $w = $l['litter_weight'];
				$wg = getModel('weightgroup')->getWeightGroupFromWeight($w);
				if($wg['id'])
					$count[$product['product_id']][4][$wg['id']]++;
			}


		}

		foreach($count as $product_id => $fg)
		{
			if(!getModel('dailyfeed')->stockUsedOnDate(date('Y-m-d'), $product_id))
			{
				$product = getModel('purchaseproduct')->load($product_id);
				$unit = $product['product_weight_unit'];
				$total_usage = 0;
				foreach($fg as $fg_id => $wg)
				{
					foreach($wg as $wg_id => $c)
					{
						$quantity = getModel('dailyfeed')->getDailyFeedQuantity($product_id,$wg_id,$fg_id);
						$usage_quantity = $quantity * $c;
						$total_usage += $usage_quantity;
					}
				}
				getModel('dailyfeed')->useFeed($product_id, $total_usage,$unit);

			}
		}
		die;



	}

	public function updateDailyFeedAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		if(isset($post_data['update']) and $post_data['update'] == 'update')
		{
			extract($post_data);
			echo '<pre>';
			foreach($feeding_group as $fg)
			{
				foreach($weight_group[$fg] as $wg_id => $quantity)
				{
					$data = array('daily_feed_id'=>$daily_feed_id[$fg][$wg_id],'product_id'=>$product_id, 'feeding_group_id'=>$fg,'weight_group_id'=>$wg_id,'quantity'=>$quantity);
					getModel('dailyfeed')->update($data);
				}
			}
		}
		else
		{
			extract($post_data);
			foreach($feeding_group as $fg)
			{
				foreach($weight_group[$fg] as $wg_id => $quantity)
				{
					$data = array('product_id'=>$product_id, 'feeding_group_id'=>$fg,'weight_group_id'=>$wg_id,'quantity'=>$quantity);					
					$feed_id = getModel('dailyfeed')->insert($data);
				}
			}
		}

		redirect('admin/dailyfeeds');
	}

	public function useDailyFeedAction()
	{
		$products = getModel('product')->getCollection(array('product_type' => 'in'));
		$rabbits = getModel('rabbit')->getCollection();
		$litters = getModel('litter')->getCollection();
		foreach($products as $product)
		{
			$p = getModel('product')->load($product['product_id']);
			foreach($rabbits as $rabbit)
			{
				$r = getModel('rabbit')->load($rabbit['product_id']);
				$weight = $r['weight'];
				$weight_group = getModel('weightgroup')->getWeightGroupFromWeight($weight);
				if($r['rabbit_gender'] == 'Male')
				{
					if(strtolower($r['rabbit_feeding_group']) == 'adult male')
					{
						$feeding_group_id = 24;
						$weight_group_id = $weight_group['id'];
						$product_id = $p['product_id'];
						$quantity = getModel('dailyfeed')->getQuantity($feeding_group_id, $weight_group_id, $product_id);
						$new_quantity = $p['product_quantity'] - $quantity;
						getModel('product')->updateDefaultAttribute($product['product_id'], 'product_quantity', $new_quantity);
						$p = getModel('product')->load($product_id);
					}
				}
				elseif($r['rabbit_gender'] == 'Female')
				{
					if(strtolower($r['rabbit_feeding_group']) == 'adult female')
					{
						$feeding_group_id = 25;
						$weight_group_id = $weight_group['id'];
						$product_id = $p['product_id'];
						$quantity = getModel('dailyfeed')->getQuantity($feeding_group_id, $weight_group_id, $product_id);
						$new_quantity = $p['product_quantity'] - $quantity;
						getModel('product')->updateDefaultAttribute($product['product_id'], 'product_quantity', $new_quantity);
						$p = getModel('product')->load($product_id);
					}

					elseif(strtolower($r['rabbit_feeding_group']) == 'pregnant/lactating')
					{
						$feeding_group_id = 26;
						$weight_group_id = $weight_group['id'];
						$product_id = $p['product_id'];
						$quantity = getModel('dailyfeed')->getQuantity($feeding_group_id, $weight_group_id, $product_id);
						$new_quantity = $p['product_quantity'] - $quantity;
						getModel('product')->updateDefaultAttribute($product['product_id'], 'product_quantity', $new_quantity);
						$p = getModel('product')->load($product_id);
					}
				}
			}

			if(isset($litters) and $litters)
			{
				foreach($litters as $litter)
				{
					$feeding_group_id = 27;
					if($litter['litter_weight'])
					{
						$weight_group = getModel('weightgroup')->getWeightGroupFromWeight($litter['litter_weight']);
						$weight_group_id = $weight_group['id'];
						$product_id = $p['product_id'];
						$quantity = getModel('dailyfeed')->getQuantity($feeding_group_id, $weight_group_id, $product_id);
						$new_quantity = $p['product_quantity'] - $quantity;
						getModel('product')->updateDefaultAttribute($product['product_id'], 'product_quantity', $new_quantity);
						$p = getModel('product')->load($product_id);
					}
				}
			}
		}
	}

}