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
        
        public function saveDailyFeeds($data = false)
        {
            if($data)
            {
                extract($data);
                $product_id = $product_id;
                $sql = "DELETE FROM rabbit_daily_feeds WHERE product_id = $product_id";
                $this->connection->DeleteQuery($sql);
                foreach($weight_group as $fg => $wgroup)
                {
                    foreach($wgroup as $wg => $qty)
                    {
                        $sql = "INSERT INTO `rabbit_daily_feeds`(`feeding_group_id`, `product_id`, `weight_group_id`, `quantity`) VALUES "
                                . "('".mysql_escape_string($fg)."','".mysql_escape_string($product_id)."','".mysql_escape_string($wg)."','".mysql_escape_string($qty)."')";
                        $this->connection->InsertQuery($sql);
                    }
                }
            }
            else return false;
        }
        
        public function getDailyFeedQuantity($product_id, $weightgroup_id, $feed_group_id)
        {
            $sql = "SELECT * FROM rabbit_daily_feeds WHERE product_id = $product_id AND weight_group_id = $weightgroup_id AND feeding_group_id = $feed_group_id LIMIT 1";
            $feed = $this->connection->Query($sql);
            if($feed) return $feed[0];
            else return false;
        }

	public function insert($data = false)
	{
		if($data != false)
		{
			$sql = "INSERT INTO `rabbit_daily_feeds`(`feeding_group_id`, `product_id`, `weight_group_id`,`quantity`) 
			VALUES (".$data['feeding_group_id'].",
			'".$data['product_id']."',
			'".$data['weight_group_id']."',
			'".mysql_escape_string($data['quantity'])."'
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
			`product_id` = ".$data['product_id'].",
			`weight_group_id` = ".$data['weight_group_id'].",
			`quantity` = ".mysql_escape_string($data['quantity'])."
			WHERE daily_feed_id = '".mysql_escape_string($data['daily_feed_id'])."'";
			$this->connection->UpdateQuery($sql);
		}
		else
			return false;
	}

	public function getCollection()
	{
		$sql = "SELECT * FROM rabbit_daily_feeds";
		$dailyfeeds = $this->connection->Query($sql);
		$arr = array();
		foreach($dailyfeeds as $df)
		{
			$arr[$df['product_id']][] = $df;
		}
		if($arr)
			return $arr;
		else return false;
	}

	public function getQuantity($feeding_group_id, $weight_group_id, $product_id)
	{
		$sql = "SELECT quantity FROM `rabbit_daily_feeds` WHERE feeding_group_id = $feeding_group_id AND product_id = $product_id AND weight_group_id = $weight_group_id";
		$quantity = $this->connection->Query($sql);
		if($quantity) return $quantity[0]['quantity'];
		else return 0;
	}
}