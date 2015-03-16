<?php

class RabbitModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getFamilyRabbits($family_id) {
        $attribute = getModel('attribute')->load(array('AND', 'attribute_code' => 'rabbit_family_id'));
        $sql = "SELECT p.product_id FROM products_inventory AS p JOIN product_attribute_value_number ON p.product_id = product_attribute_value_number.product_id WHERE attribute_id = '" . $attribute['attribute_id'] . "' AND value=" . $family_id;
        $rabbits = $this->connection->Query($sql);
        $rabbit_array = array();
        foreach ($rabbits as $rabbit) {
            array_push($rabbit_array, $rabbit['product_id']);
        }
        return $rabbit_array;
    }

    public function load($rabbit_id) {
        $sql = "SELECT * FROM `products_inventory` WHERE product_id = " . $rabbit_id . ' LIMIT 1';
        $rabbit = $this->connection->Query($sql);
        if ($rabbit) {
            $ra = array();
            foreach ($rabbit[0] as $attribute_code => $value) {
                $ra[$attribute_code] = $value;
            }

            $attributes = getModel('product')->getAttributes($rabbit_id);
            foreach ($attributes as $attribute) {
                $ra[$attribute['attribute_code']] = $attribute['value'];
                if ($attribute['value'] == '0000-00-00')
                    $ra[$attribute['attribute_code']] = null;
            }

            return $ra;
        } else
            return false;
    }

    public function performMating($male, $female) {
        $date = date('Y-m-d');
        getModel('product')->updateAttribute($male, 'rabbit_latest_mate_date', $date);
        getModel('product')->updateAttribute($female, 'rabbit_latest_mate_date', $date);
        getModel('product')->updateAttribute($female, 'recently_mated_buck', $male);


        return true;
    }

    public function makePregnant($rabbit_id) {
        $date = date('Y-m-d');
        getModel('product')->updateAttribute($rabbit_id, 'rabbit_latest_pregnant_date', $date);
        getModel('product')->updateAttribute($rabbit_id, 'is_pregnant', '15');
    }

    public function notpregnantduringweaning($rabbit_id) {
        getModel('product')->deleteAttribute($rabbit_id, 'is_pregnant');
        getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_pregnant_date');
        //getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_mate_date');
    }

    public function notPregnant($rabbit_id) {
        getModel('product')->deleteAttribute($rabbit_id, 'is_pregnant');
        getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_pregnant_date');
        getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_mate_date');
    }

    public function giveBirth($rabbit_id) {
        getModel('product')->updateAttribute($rabbit_id, 'rabbit_latest_birth_date', date('Y-m-d'));
    }

    public function wean($rabbit_id) {
        getModel('product')->updateAttribute($rabbit_id, 'rabbit_latest_weaning_date', date('Y-m-d'));
        getModel('product')->updateAttribute($rabbit_id, 'rabbit_feeding_group', '25');
    }

    public function resetDates($rabbit_id) {
        getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_mate_date');
        getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_pregnant_date');
        getModel('product')->deleteAttribute($rabbit_id, 'is_pregnant');
        getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_birth_date');
        getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_weaning_date');
    }

     public function newresetDates($rabbit_id) {
        //getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_mate_date');
        getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_pregnant_date');
        getModel('product')->deleteAttribute($rabbit_id, 'is_pregnant');
        getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_birth_date');
        getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_weaning_date');
    }

    public function getMaxID() {
        $sql = "select MAX(product_id) from products_inventory";
        $max = $this->connection->Query($sql);
        if ($max)
            return $max[0]["MAX(product_id)"];
        else
            return false;
    }

    public function getCollection() {
        $sql = "SELECT * FROM products_inventory r1 WHERE attribute_set_id = 4 AND NOT EXISTS(SELECT * 
                  FROM   aa_death r2
                  WHERE  r1.product_id = r2.rid 
                 )";
        $rabbits = $this->connection->Query($sql);
        if ($rabbits)
            return $rabbits;
        else
            return false;
    }

    public function deathentry($post_data) { // For Death entry with update in rabbit genology
        extract($post_data);
        if (isset($rabbit_id)) {
            $sql = 'INSERT INTO `aa_death`(`rid`, `death_reason`) VALUES (' . $rabbit_id . ',"' . $death_reason . '")';
        }
        if (isset($litter_id)) {
            $sql = 'INSERT INTO `aa_death`(`lid`, `death_reason`) VALUES (' . $litter_id . ',"' . $death_reason . '")';
        }
        $insertsql = $this->connection->InsertQuery($sql);
        if ($insertsql && isset($rabbit_id)) {
            $delete = "DELETE FROM `aa_rabbits` WHERE `r_id`=$rabbit_id";
            $this->connection->DeleteQuery($delete);
            return true;
        }
        if ($insertsql && isset($litter_id)) {
            return true;
        } else
            return false;
    }
    
    public function getSickRabbitCount()
    {
        $sick_rabbits = $this->getSickRabbits();
        if($sick_rabbits)
	        return count($sick_rabbits);
	    else
	    	return 0;
    }
    
    public function getDeadRabbitCount()
    {
        $dead_rabbits = $this->getDeadRabbits();
        if($dead_rabbits)
	        return count($dead_rabbits);
	    else
	    	return 0;
    }

    public function getShiftedRabbitCount()
    {
        $shifted_rabbits = $this->getShiftedRabbits();
        if($shifted_rabbits)
	        return count($shifted_rabbits);
	    else return 0;
    }

    public function getSickRabbits() {
        $sql = "SELECT sick_rabbit_id, rabbit_id, sick_date, reason FROM `sick_rabbits` JOIN sick_reasons ON sick_rabbits.sick_reason = sick_reasons.id";
        $sick_rabbits = $this->connection->Query($sql);
        if ($sick_rabbits) {
            return $sick_rabbits;
        } else {
            return false;
        }
    }
    
    public function getDeadRabbits() {
        $sql = "SELECT death_id, rabbit_id, litter_id, death_date, reason FROM `dead_rabbits` JOIN death_reasons ON dead_rabbits.death_reason = death_reasons.id GROUP by dead_rabbits.rabbit_id";
        $dead_rabbits = $this->connection->Query($sql);
        if ($dead_rabbits) {
            return $dead_rabbits;
        } else {
            return false;
        }
    }

     public function getShiftedRabbits() {
        $sql = "SELECT shift_id, rabbit_id, shifted_from, shifted_to, shifted_date, reason FROM `shifted_rabbits` JOIN shifting_reasons ON shifted_rabbits.shifting_reason = shifting_reasons.id";
        $shifted_rabbits = $this->connection->Query($sql);
        if ($shifted_rabbits) {
            return $shifted_rabbits;
        } else {
            return false;
        }
    }
    
    public function getSickReasons()
    {
        $sql = "SELECT * FROM sick_reasons";
        $reasons = $this->connection->Query($sql);
        if($reasons) return $reasons;
        else return false;
    }
    
    public function getDeathReasons()
    {
        $sql = "SELECT * FROM death_reasons";
        $reasons = $this->connection->Query($sql);
        if($reasons) return $reasons;
        else return false;
    }
    
     public function getShiftingReasons()
    {
        $sql = "SELECT * FROM shifting_reasons";
        $reasons = $this->connection->Query($sql);
        if($reasons) return $reasons;
        else return false;
    }
    
    public function sick($rabbit_id,$sick_from,$sick_reason_id)
    {
        $sql = "INSERT INTO `sick_rabbits`(`rabbit_id`, `sick_date`, `sick_reason`) VALUES ("
                .mysql_escape_string($rabbit_id).",'".mysql_escape_string($sick_from)."',".mysql_escape_string($sick_reason_id).")";
        $this->connection->InsertQuery($sql);
        return $this->connection->GetInsertID();
    }
    
    public function shift($rabbit_id, $shifted_date, $shifted_to, $shifting_reason_id)
    {
        $rabbit = getModel('rabbit')->load($rabbit_id);
        $shifted_from = $rabbit['rabbit_group'];
        
        getModel('product')->updateAttribute($rabbit_id, 'rabbit_group', $shifted_to);
        $rabbit = getModel('rabbit')->load($rabbit_id);
        $shifted_to = $rabbit['rabbit_group'];
        $sql = "INSERT INTO `shifted_rabbits`(`rabbit_id`, `shifted_from`, `shifted_to`, `shifted_date`, `shifting_reason`) VALUES (".mysql_escape_string($rabbit_id).",'".$shifted_from."','".$shifted_to."','".mysql_escape_string($shifted_date)."','".mysql_escape_string($shifting_reason_id)."')";
        $this->connection->InsertQuery($sql);
        return $this->connection->GetInsertID();
    }
    
    public function dead($rabbit_id,$death_on,$death_reason_id)
    {
        $sql = "INSERT INTO `dead_rabbits`(`rabbit_id`, `death_date`, `death_reason`) VALUES ("
                .mysql_escape_string($rabbit_id).",'".mysql_escape_string($death_on)."',".mysql_escape_string($death_reason_id).")";
        $this->connection->InsertQuery($sql);
        $reason_id = $this->connection->GetInsertID();
        $sql = "INSERT INTO aa_death (`rid`,`death_reason`) VALUES(".$rabbit_id.", '".$death_reason_id."')";
        $this->connection->InsertQuery($sql);
        $delete = "DELETE FROM `aa_rabbits` WHERE `r_id`=".$rabbit_id;
        $this->connection->DeleteQuery($delete);
        return $reason_id;
    }
    
    public function addSickReason($reason, $reason_description)
    {
        $sql = "INSERT INTO `sick_reasons`(`reason`, `reason_description`) VALUES ('".mysql_escape_string($reason)."','".mysql_escape_string($reason_description)."')";
        $this->connection->InsertQuery($sql);
        return $this->connection->GetInsertID();
             
    }
    
    public function addDeathReason($reason, $reason_description)
    {
        $sql = "INSERT INTO `death_reasons`(`reason`, `reason_description`) VALUES ('".mysql_escape_string($reason)."','".mysql_escape_string($reason_description)."')";
        $this->connection->InsertQuery($sql);
        return $this->connection->GetInsertID();
             
    }
    
    public function addShiftingReason($reason, $reason_description)
    {
        $sql = "INSERT INTO `shifting_reasons`(`reason`, `reason_description`) VALUES ('".mysql_escape_string($reason)."','".mysql_escape_string($reason_description)."')";
        $this->connection->InsertQuery($sql);
        return $this->connection->GetInsertID();
             
    }

}
