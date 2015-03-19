<?php

class DailyfeedModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function deductDailyFeeds() {
        $dailyFeeds = getModel('product')->getCollection(array('AND', 'daily_use_status' => '1'));
        foreach ($dailyFeeds as $dailyFeed) {
            $product_id = $dailyFeed['product_id'];
            $dq = $dailyFeed['daily_use_quantity'];
            $quantity = $dailyFeed['product_quantity'];
            if ($quantity > $dq) {
                $newQuantity = $quantity - $dq;
            } else {
                $newQuantity = 0;
            }
            getModel('product')->updateDefaultAttribute($product_id, 'product_quantity', $newQuantity);
        }
    }

    public function saveDailyFeeds($data = false) {
        if ($data) {
            extract($data);
            $product_id = $product_id;
//                $sql = "DELETE FROM rabbit_daily_feeds WHERE product_id = $product_id";
//                $this->connection->DeleteQuery($sql);
//                foreach($weight_group as $fg => $wgroup)
//                {
//                    foreach($wgroup as $wg => $qty)
//                    {
//                        $sql = "INSERT INTO `rabbit_daily_feeds`(`feeding_group_id`, `product_id`, `weight_group_id`, `quantity`) VALUES "
//                                . "('".mysql_escape_string($fg)."','".mysql_escape_string($product_id)."','".mysql_escape_string($wg)."','".mysql_escape_string($qty)."')";
//                        $this->connection->InsertQuery($sql);
//                    }
//                }

            $sql = "DELETE FROM rabbit_feed_usage WHERE product_id = $product_id";
            $this->connection->DeleteQuery($sql);
            foreach ($feeding_groups as $id => $q) {
                $sql = "INSERT INTO `rabbit_feed_usage`(`product_id`, `feed_group_id`, `quantity`) "
                        . "VALUES ($product_id,$id," . mysql_escape_string($q) . ")";
                $this->connection->InsertQuery($sql);
            }

            foreach ($litter_days as $id => $q) {
                $sql = "INSERT INTO `rabbit_feed_usage`(`product_id`, `feed_group_id`,`litter_days_id`, `quantity`) "
                        . "VALUES ($product_id, 4,$id," . mysql_escape_string($q) . ")";
                $this->connection->InsertQuery($sql);
            }
        } else
            return false;
    }

    public function getDailyFeedQuantity($product_id, $feed_group_id, $litter_days_id) {
        if ($litter_days_id != 'NULL')
            $sql = "SELECT * FROM rabbit_feed_usage WHERE product_id = $product_id AND feed_group_id = $feed_group_id AND litter_days_id = '$litter_days_id'";
        else
            $sql = "SELECT * FROM rabbit_feed_usage WHERE product_id = $product_id AND feed_group_id = $feed_group_id AND litter_days_id  IS NULL";
        //$sql = "SELECT * FROM rabbit_daily_feeds WHERE product_id = $product_id AND weight_group_id = $weightgroup_id AND feeding_group_id = $feed_group_id LIMIT 1";
        //echo $sql;die;
        $feed = $this->connection->Query($sql);
        if ($feed)
            return $feed[0]['quantity'];
        else
            return false;
    }

    public function getLitterDaysId($day) {
        $sql = "SELECT * FROM litter_days WHERE min_days <= $day and max_days > $day LIMIT 1";
        $ld = $this->connection->Query($sql);
        if ($ld)
            return $ld[0];
        else
            return false;
    }

    public function useFeed($product_id, $quantity, $unit) {
        $date = date('Y-m-d');
//    	$sql = "INSERT INTO `stock_usage`(`product_id`, `quantity`, `unit`, `date`) VALUES ($product_id,$quantity,$unit,'$date')";
//    	$this->connection->InsertQuery($sql);
//    	return $this->connection->GetInsertID();

        $stock_balance_queue = getModel('stock')->getStockBalance($product_id, $date);
        foreach ($stock_balance_queue as $sb) {
            if ($quantity < $sb['quantity']) {
                $rem_quantity = $sb['quantity'] - $quantity;
                $sql = "UPDATE stock_balance SET quantity = $rem_quantity WHERE id = " . $sb['id'];
                $up = $sb['unit_price'];
                $this->connection->UpdateQuery($sql);
                $sql = "INSERT INTO `stock_usage`(`product_id`, `quantity`, `unit`, `unit_price`,`date`) VALUES ($product_id,$quantity," . $sb['unit'] . ",$up,'$date')";
                $this->connection->InsertQuery($sql);
                break;
            } else if ($quantity == $sb['quantity']) {
                $up = $sb['unit_price'];
                $unit = $sb['unit'];
                $sql = "DELETE FROM stock_balance WHERE id = " . $sb['id'];
                $this->connection->DeleteQuery($sql);
                $sql = "INSERT INTO `stock_usage`(`product_id`, `quantity`, `unit`, `unit_price`,`date`) VALUES ($product_id,$quantity," . $unit . ",$up,'$date')";
                $this->connection->InsertQuery($sql);
                break;
            } else if ($quantity > $sb['quantity']) {
                $used_quantity = $sb['quantity'];
                $up = $sb['unit_price'];
                $unit = $sb['unit'];
                $sql = "DELETE FROM stock_balance WHERE id = " . $sb['id'];
                $this->connection->DeleteQuery($sql);
                $sql = "INSERT INTO `stock_usage`(`product_id`, `quantity`, `unit`, `unit_price`,`date`) VALUES ($product_id,$used_quantity," . $unit . ",$up,'$date')";
                $this->connection->InsertQuery($sql);
                $quantity -= $sb['quantity'];
            }
        }
    }

    public function stockUsedOnDate($date, $product_id) {
        $sql = "SELECT * FROM stock_usage WHERE date = '$date' AND product_id = $product_id";
        $usage = $this->connection->Query($sql);
        if ($usage)
            return true;
        else
            return false;
    }

    public function insert($data = false) {
        if ($data != false) {
            $sql = "INSERT INTO `rabbit_daily_feeds`(`feeding_group_id`, `product_id`, `weight_group_id`,`quantity`) 
			VALUES (" . $data['feeding_group_id'] . ",
			'" . $data['product_id'] . "',
			'" . $data['weight_group_id'] . "',
			'" . mysql_escape_string($data['quantity']) . "'
			)";

            $this->connection->InsertQuery($sql);
            return $this->connection->GetInsertID();
        } else
            return false;
    }

    public function update($data = false) {
        if ($data != false) {
            $sql = "UPDATE `rabbit_daily_feeds` SET 
			`feeding_group_id`=" . $data['feeding_group_id'] . ",
			`product_id` = " . $data['product_id'] . ",
			`weight_group_id` = " . $data['weight_group_id'] . ",
			`quantity` = " . mysql_escape_string($data['quantity']) . "
			WHERE daily_feed_id = '" . mysql_escape_string($data['daily_feed_id']) . "'";
            $this->connection->UpdateQuery($sql);
        } else
            return false;
    }

    public function getCollection() {
        $sql = "SELECT * FROM rabbit_daily_feeds";
        $dailyfeeds = $this->connection->Query($sql);
        $arr = array();
        foreach ($dailyfeeds as $df) {
            $arr[$df['product_id']][] = $df;
        }
        if ($arr)
            return $arr;
        else
            return false;
    }

    public function getQuantity($feeding_group_id, $weight_group_id, $product_id) {
        $sql = "SELECT quantity FROM `rabbit_daily_feeds` WHERE feeding_group_id = $feeding_group_id AND product_id = $product_id AND weight_group_id = $weight_group_id";
        $quantity = $this->connection->Query($sql);
        if ($quantity)
            return $quantity[0]['quantity'];
        else
            return 0;
    }

}
