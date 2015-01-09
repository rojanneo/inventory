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

		$feeding_groups = getModel('attribute')->load(array('attribute_code'=>'rabbit_feeding_group'));
		$feeding_groups = getModel('attribute')->getOptions($feeding_groups['attribute_id']);
		//echo '<pre>';
		//var_dump($feeding_groups);

		$dailyfeeds = getModel('dailyfeed')->getCollection();

		if($dailyfeeds)
		{
			$data['dailyfeeds'] = $dailyfeeds;
			$data['feeding_groups'] = $feeding_groups;
			$this->view->renderAdmin('dailyfeeds/table.phtml',$data);
		}
		else
		{

			$data['feeding_groups'] = $feeding_groups;

			$this->view->renderAdmin('dailyfeeds/table.phtml',$data);
		}
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

			foreach($litters as $litter)
			{
				$feeding_group_id = 27;
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