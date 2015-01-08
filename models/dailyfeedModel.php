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

	public function insert($data = false)
	{
		if($data != false)
		{
			$sql = "INSERT INTO `rabbit_daily_feeds`(`feeding_group_id`, `06_07kg`, `1_2kg`, `2_3kg`, `3_4kg`, `4_5kg`, `5_kg`) 
			VALUES (".$data['feeding_group_id'].",
			'".mysql_escape_string($data['06_07kg'])."',
			'".mysql_escape_string($data['1_2kg'])."',
			'".mysql_escape_string($data['2_3kg'])."',
			'".mysql_escape_string($data['3_4kg'])."',
			'".mysql_escape_string($data['4_5kg'])."',
			'".mysql_escape_string($data['5_kg'])."'
			)";
			
			$this->connection->InsertQuery($sql);
			return $this->connection->GetInsertID();
		}
		else return false;
	}

	public function update($data = false)
	{
		if($data != false)
		{
			$sql = "UPDATE `rabbit_daily_feeds` SET 
			`feeding_group_id`=".$data['feeding_group_id'].",
			`06_07kg`='".mysql_escape_string($data['06_07kg'])."',
			`1_2kg`='".mysql_escape_string($data['1_2kg'])."',
			`2_3kg`='".mysql_escape_string($data['2_3kg'])."',
			`3_4kg`='".mysql_escape_string($data['3_4kg'])."',
			`4_5kg`='".mysql_escape_string($data['4_5kg'])."',
			`5_kg`='".mysql_escape_string($data['5_kg'])."' WHERE daily_feed_id = '".mysql_escape_string($data['daily_feed_id'])."'";
			$this->connection->UpdateQuery($sql);
		}
		else
			return false;
	}

	public function getCollection()
	{
		$sql = "SELECT * FROM rabbit_daily_feeds";
		$dailyfeeds = $this->connection->Query($sql);
		if($dailyfeeds)
			return $dailyfeeds;
		else return false;
	}
}