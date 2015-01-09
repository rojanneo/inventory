<?php 
class WeightgroupModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCollection()
	{
		$sql = "SELECT * FROM rabbit_weight_group";
		$groups = $this->connection->Query($sql);
		if($groups) return $groups;
		else return false;
	}

	public function getWeightGroupFromWeight($weight)
	{
		if($weight > 5)
		{
			$sql = "SELECT * FROM rabbit_weight_group WHERE id = 6 LIMIT 1";
		}
		else if($weight <1)
		{
			$sql = "SELECT * FROM rabbit_weight_group WHERE min_weight <= $weight AND max_weight >= $weight LIMIT 1";
		}
		else
		{
			$sql = "SELECT * FROM rabbit_weight_group WHERE min_weight <= $weight AND max_weight > $weight";
		}

		$weight_group = $this->connection->Query($sql);
		if($weight_group) return $weight_group[0];
		else return false;
	}
}