<?php
class LitterModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function insert($data = false, $litter_group_id)
	{
		if($data != false)
		{
			extract($data);
			if(!isset($rabbit_id)) $rabbit_id = NULL;
			if(!isset($litter_dob)) $litter_dob = date('Y-m-d');
			$updated_date = date('Y-m-d');
			$sql = "INSERT INTO `rabbit_litters`
			(`parent_id`,
			`parent_buck_id`,
			`family_id`,
			`litter_group_id`,
			`litters_dob`,
			`updated_date`,
			`rabbit_id`)
			VALUES 
			(
			'".mysql_escape_string($parent_rabbit_id)."',
			'".mysql_escape_string($parent_buck_id)."',
			'".mysql_escape_string($rabbit_family_id)."',
			'".$litter_group_id."',
			'".mysql_escape_string($litter_dob)."',
			'".$updated_date."',
			'".mysql_escape_string($rabbit_id)."'
			)";
			$this->connection->Query($sql);
			return $this->connection->GetInsertID();
		}
		else
		{
			return false;
		}
	}

	public function getCollection($parent_id = false)
	{
            if ($parent_id) {
            $sql = "SELECT * FROM rabbit_litters r1 WHERE parent_id = $parent_id AND NOT EXISTS(SELECT * FROM   aa_death r2 WHERE  r1.litter_id = r2.lid)";
        } else {
            $sql = "SELECT * FROM rabbit_litters r1 WHERE NOT EXISTS(SELECT * 
                  FROM   aa_death r2
                  WHERE  r1.litter_id = r2.lid 
                 )";
        }

        $litters = $this->connection->Query($sql);
		return $litters;
	}

	public function getWeaningCollection($parent_id = false)
	{
		if($parent_id)
		{
		$sql = "SELECT * FROM rabbit_litters r1 WHERE parent_id = $parent_id AND (rabbit_id IS NULL or rabbit_id = 0) AND NOT EXISTS(SELECT * 
                  FROM   aa_death r2
                  WHERE  r1.litter_id = r2.lid 
                 )";
		}
		else
			$sql = "SELECT * FROM rabbit_litters r1 WHERE rabbit_id = NULL AND NOT EXISTS(SELECT * 
                  FROM   aa_death r2
                  WHERE  r1.litter_id = r2.lid 
                 )";
		
		$litters = $this->connection->Query($sql);
		return $litters;
	}

	public function wean($rabbit_id,$product_id)
	{
		$today = date('Y-m-d');
		$sql = "UPDATE rabbit_litters SET litters_weaning_date = '".$today."', rabbit_id = '".$product_id."' WHERE litter_id = $rabbit_id";
		$this->connection->UpdateQuery($sql);
		return true;
	}

	public function load($litter_id)
	{
		$sql = "SELECT * FROM rabbit_litters WHERE litter_id = ".$litter_id." Limit 1";
		$litter = $this->connection->Query($sql);
		if($litter) return $litter[0];
		else $litter;
	}

	public function loadByRabbitId($rabbit_id)
	{
		$sql = "SELECT * FROM rabbit_litters WHERE rabbit_id = ".$rabbit_id." Limit 1";
		$litter = $this->connection->Query($sql);
		if($litter) return $litter[0];
		else $litter;
	}

	public function delete($litter_id)
	{
		$sql = "DELETE FROM rabbit_litters WHERE litter_id = ".$litter_id;
		$this->connection->DeleteQuery($sql);
		return true;
	}

	public function getSameParentRabbits($doe,$buck)
	{
		$sql = "SELECT rabbit_id FROM rabbit_litters WHERE parent_id IN( SELECT parent_id FROM rabbit_litters WHERE parent_id = '".$doe."' HAVING COUNT(litter_id)>0) AND parent_buck_id IN
		( SELECT parent_buck_id FROM rabbit_litters WHERE parent_buck_id = '".$buck."' HAVING COUNT(litter_id) > 0 AND rabbit_id != 0)";

		$rabbits = $this->connection->Query($sql);
		$rs = array();
		foreach($rabbits as $rabbit)
		{
			if(!is_null($rabbit['rabbit_id']))
			{
				array_push($rs, $rabbit);
			}
		}
		if($rs) return $rs; else return false;
	}

	public function updateWeights($data = false)
	{
		if($data != false)
		{
			$sql = "UPDATE `rabbit_litters` SET `litter_weight`= '".mysql_escape_string($data['weight'])."' WHERE `litter_id` = ".mysql_escape_string($data['litter_id']);
			$this->connection->UpdateQuery($sql);
			return true;
		}
		return false;
	}

	public function getWeanedLitters()
	{
		$sql = "SELECT * FROM rabbit_litters WHERE rabbit_id NOT IN (SELECT rabbit_id FROM `rabbit_litters` JOIN aa_death ON rabbit_litters.rabbit_id = aa_death.rid WHERE rabbit_id IS NOT NULL AND rabbit_id <> 0) AND rabbit_id IS NOT NULL and rabbit_id <> 0";
		$litters = $this->connection->Query($sql);
		if($litters) return $litters;
		else return false;
	}

	public function getUnweanedLitters()
	{
		$sql = "SELECT * FROM rabbit_litters WHERE rabbit_id NOT IN (SELECT rabbit_id FROM `rabbit_litters` JOIN aa_death ON rabbit_litters.rabbit_id = aa_death.rid WHERE rabbit_id IS NULL AND rabbit_id = 0) AND rabbit_id IS NULL or rabbit_id = 0";
		$litters = $this->connection->Query($sql);
		if($litters) return $litters;
		else return false;
	}
}