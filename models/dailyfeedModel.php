<?php
class DailyfeedModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function deductDailyFeeds()
	{
		$dailyFeeds = getModel('product')->getCollection(array('AND', 'daily_use_status'=>'1'));
		foreach($dailyFeeds as $dailyFeed)
		{
			$product_id = $dailyFeed['product_id'];
			$dq = $dailyFeed['daily_use_quantity'];
			$quantity = $dailyFeed['product_quantity'];
			if($quantity > $dq)
			{
				$newQuantity = $quantity - $dq;
			}
			else
			{
				$newQuantity = 0;
			}
			getModel('product')->updateDefaultAttribute($product_id,'product_quantity',$newQuantity);
		}
	}
}