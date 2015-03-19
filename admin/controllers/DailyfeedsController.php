<?php

class DailyfeedsController extends Controller {

    public function __construct() {
        parent::__construct();
        loadHelper('url');
    }

    public function indexAction() {
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

        $data['feeding_groups'] = getModel('feedinggroup')->getCollection();
        $data['weight_groups'] = getModel('weightgroup')->getCollection();
        $data['litter_days'] = getModel('feedinggroup')->getLitterDays();
        $data['products'] = getModel('purchaseproduct')->getDailyFeeds();

        $this->view->renderAdmin('dailyfeeds/form.phtml', $data);
    }

    public function dailyfeedpostAction() {
        loadHelper('inputs');
        $post_data = getPost();
        echo '<pre>';
        getModel('dailyfeed')->saveDailyFeeds($post_data);
        redirect('dailyfeeds');
    }

    public function useFeedAction() {
        error_reporting(0);

        $rabbits = getModel('rabbit')->getCollection();
        $adult_male_count = 0;
        $adult_female_count = 0;
        $pregnant_lactating_count = 0;
        $parent_to_be_count = 0;
        $product_to_be_count = 0;
        $litters = array();
        foreach ($rabbits as $r) {
            $rabbit = getModel('rabbit')->load($r['product_id']);
            $feeding_group = strtolower($rabbit['rabbit_feeding_group']);
            if ($feeding_group == 'litters') {
                $dow = new DateTime($rabbit['rabbit_latest_weaning_date']);
                $today = new DateTime(date('Y-m-d'));
                $diff = $today->diff($dow)->format('%a');


                $lid = getModel('dailyfeed')->getLitterDaysId($diff);
                if ($litters[$lid['id']])
                    $litters[$lid['id']] ++;
                else
                    $litters[$lid['id']] = 1;
            }
            else if ($feeding_group == 'adult male') {
                $adult_male_count++;
            } else if ($feeding_group == 'adult female') {
                $adult_female_count++;
            } else if ($feeding_group == 'pregnant/lactating') {
                $pregnant_lactating_count++;
            } else if ($feeding_group == 'parent to be') {
                $parent_to_be_count++;
            } else if ($feeding_group == 'product to be') {
                $product_to_be_count++;
            }
        }

        $products = getModel('purchaseproduct')->getDailyFeeds();
        $feedinggroups = getModel('feedinggroup')->getCollection();
        $adult_male_qty = 0;
        $adult_female_qty = 0;
        $pregnant_qty = 0;
        $parenttobe_qty = 0;
        $producttobe_qty = 0;
        $litter_qty = 0;
        foreach ($products as $product) {
            foreach ($feedinggroups as $fg) {
                if ($fg['feeding_group'] == 'male') {
                    $qty = getModel('dailyfeed')->getDailyFeedQuantity($product['product_id'], $fg['id'], 'NULL');
                    $adult_male_qty = $qty * $adult_male_count;
                } else if ($fg['feeding_group'] == 'female') {
                    $qty = getModel('dailyfeed')->getDailyFeedQuantity($product['product_id'], $fg['id'], 'NULL');
                    $adult_female_qty = $qty * $adult_female_count;
                } else if ($fg['feeding_group'] == 'preg_lact') {
                    $qty = getModel('dailyfeed')->getDailyFeedQuantity($product['product_id'], $fg['id'], 'NULL');
                    $pregnant_qty = $qty * $pregnant_lactating_count;
                } else if ($fg['feeding_group'] == 'parent_to_be') {
                    $qty = getModel('dailyfeed')->getDailyFeedQuantity($product['product_id'], $fg['id'], 'NULL');
                    $parenttobe_qty = $qty * $parent_to_be_count;
                } else if ($fg['feeding_group'] == 'product_to_be') {
                    $qty = getModel('dailyfeed')->getDailyFeedQuantity($product['product_id'], $fg['id'], 'NULL');
                    $producttobe_qty = $qty * $product_to_be_count;
                } else if ($fg['feeding_group'] == 'litter') {
                    foreach ($litters as $id => $litter) {
                        $qty = getModel('dailyfeed')->getDailyFeedQuantity($product['product_id'], $fg['id'], $id);
                        $litter_qty += $qty * $litter;
                    }
                }
            }
            $total_usage = $adult_male_qty + $adult_female_qty + $pregnant_qty + $parenttobe_qty + $producttobe_qty + $litter_qty;
            echo 'Total Usage: ' . $total_usage;
            getModel('dailyfeed')->useFeed($product['product_id'], $total_usage, $unit);
        }




//		$rabbits = getModel('rabbit')->getCollection();
//		echo '<pre>';
//
//		foreach($rabbits as $r)
//		{
//			$rabbit = getModel('rabbit')->load($r['product_id']);
//			
//			echo $rabbit['product_id'].' -> '.$rabbit['rabbit_feeding_group'].'<br>';
//		}
//		die;
//
//		$adult_male_count = 0;
//		$adult_female_count = 0;
//		$pregnant_lactating_count = 0;
//		$weaned_litters = 0;
//		$count = array();
//		$products = getModel('purchaseproduct')->getDailyFeeds();
//		foreach($products as $product)
//		{
//			foreach($rabbits as $rabbit)
//			{
//				$r = getModel('rabbit')->load($rabbit['product_id']);
//				if($r['weight'] == 0) $w = 1;
//				else $w = $r['weight'];
//				$wg = getModel('weightgroup')->getWeightGroupFromWeight($w);
//				if((!isset($r['rabbit_latest_weaning_date']) or !$r['rabbit_latest_weaning_date']) or ($r['rabbit_latest_weaning_date'] and (isset($r['rabbit_latest_culling_date']) and $r['rabbit_latest_culling_date'])))
//				{
//					if($r['rabbit_gender'] == 'Male')
//					{
//						$count[$product['product_id']][1][$wg['id']]++;
//						$adult_male_count++;
//					}
//					else if($r['rabbit_gender'] == 'Female')
//					{
//						if(isset($r['rabbit_feeding_group']) and $r['rabbit_feeding_group'] == 'Pregnant/Lactating')
//						{
//							$count[$product['product_id']][3][$wg['id']]++;
//							$pregnant_lactating_count++;
//						}
//						else
//						{
//							$count[$product['product_id']][2][$wg['id']]++;
//							$adult_female_count++;
//						}
//
//					}
//				}
//
//
//			}
//
//			$weaned_litters = (getModel('litter')->getWeanedLitters());
//			$weaned_litters_count = count($weaned_litters);
//			$unweaned_litters = (getModel('litter')->getUnweanedLitters());
//			$unweaned_litters_count = count($unweaned_litters);
//			// echo 'Male: '.$adult_male_count.'<br>';
//			// echo 'Adult Female: '.$adult_female_count.'<br>';
//			// echo 'Pregnant/Lactating Female: '.$pregnant_lactating_count.'<br>';
//			// echo 'Weaned Litters: '.$weaned_litters_count.'<br>';
//			// echo 'Unweaned Litters: '.$unweaned_litters_count.'<br>';
//
//			foreach($weaned_litters as $l)
//			{
//				$wl = getModel('rabbit')->load($l['rabbit_id']);
//				if($wl['weight'] == 0) $w = 0.64;
//				else $w = $wl['weight'];
//				$wg = getModel('weightgroup')->getWeightGroupFromWeight($w); 
//				if($wg['id'])
//					$count[$product['product_id']][4][$wg['id']]++;
//
//			}
//
//			foreach($unweaned_litters as $l)
//			{
//				if($l['litter_weight'] == NULL or $l['litter_weight'] == 0) $w = 0.66;
//				else $w = $l['litter_weight'];
//				$wg = getModel('weightgroup')->getWeightGroupFromWeight($w);
//				if($wg['id'])
//					$count[$product['product_id']][4][$wg['id']]++;
//			}
//
//
//		}
//
//		foreach($count as $product_id => $fg)
//		{
//			if(!getModel('dailyfeed')->stockUsedOnDate(date('Y-m-d'), $product_id))
//			{
//				$product = getModel('purchaseproduct')->load($product_id);
//				$unit = $product['product_weight_unit'];
//				$total_usage = 0;
//				foreach($fg as $fg_id => $wg)
//				{
//					foreach($wg as $wg_id => $c)
//					{
//						$quantity = getModel('dailyfeed')->getDailyFeedQuantity($product_id,$wg_id,$fg_id);
//						$usage_quantity = $quantity * $c;
//						$total_usage += $usage_quantity;
//					}
//				}
//                                //var_dump($total_usage);
//				getModel('dailyfeed')->useFeed($product_id, 125,$unit);
//
//			}
//		}





        die;
    }

    public function updateDailyFeedAction() {
        loadHelper('inputs');
        $post_data = getPost();
        if (isset($post_data['update']) and $post_data['update'] == 'update') {
            extract($post_data);
            echo '<pre>';
            foreach ($feeding_group as $fg) {
                foreach ($weight_group[$fg] as $wg_id => $quantity) {
                    $data = array('daily_feed_id' => $daily_feed_id[$fg][$wg_id], 'product_id' => $product_id, 'feeding_group_id' => $fg, 'weight_group_id' => $wg_id, 'quantity' => $quantity);
                    getModel('dailyfeed')->update($data);
                }
            }
        } else {
            extract($post_data);
            foreach ($feeding_group as $fg) {
                foreach ($weight_group[$fg] as $wg_id => $quantity) {
                    $data = array('product_id' => $product_id, 'feeding_group_id' => $fg, 'weight_group_id' => $wg_id, 'quantity' => $quantity);
                    $feed_id = getModel('dailyfeed')->insert($data);
                }
            }
        }

        redirect('admin/dailyfeeds');
    }

    public function useDailyFeedAction() {
        $products = getModel('product')->getCollection(array('product_type' => 'in'));
        $rabbits = getModel('rabbit')->getCollection();
        $litters = getModel('litter')->getCollection();
        foreach ($products as $product) {
            $p = getModel('product')->load($product['product_id']);
            foreach ($rabbits as $rabbit) {
                $r = getModel('rabbit')->load($rabbit['product_id']);
                $weight = $r['weight'];
                $weight_group = getModel('weightgroup')->getWeightGroupFromWeight($weight);
                if ($r['rabbit_gender'] == 'Male') {
                    if (strtolower($r['rabbit_feeding_group']) == 'adult male') {
                        $feeding_group_id = 24;
                        $weight_group_id = $weight_group['id'];
                        $product_id = $p['product_id'];
                        $quantity = getModel('dailyfeed')->getQuantity($feeding_group_id, $weight_group_id, $product_id);
                        $new_quantity = $p['product_quantity'] - $quantity;
                        getModel('product')->updateDefaultAttribute($product['product_id'], 'product_quantity', $new_quantity);
                        $p = getModel('product')->load($product_id);
                    }
                } elseif ($r['rabbit_gender'] == 'Female') {
                    if (strtolower($r['rabbit_feeding_group']) == 'adult female') {
                        $feeding_group_id = 25;
                        $weight_group_id = $weight_group['id'];
                        $product_id = $p['product_id'];
                        $quantity = getModel('dailyfeed')->getQuantity($feeding_group_id, $weight_group_id, $product_id);
                        $new_quantity = $p['product_quantity'] - $quantity;
                        getModel('product')->updateDefaultAttribute($product['product_id'], 'product_quantity', $new_quantity);
                        $p = getModel('product')->load($product_id);
                    } elseif (strtolower($r['rabbit_feeding_group']) == 'pregnant/lactating') {
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

            if (isset($litters) and $litters) {
                foreach ($litters as $litter) {
                    $feeding_group_id = 27;
                    if ($litter['litter_weight']) {
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
