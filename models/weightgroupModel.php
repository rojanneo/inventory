<?php 
class WeightgroupModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCollection()
	{
		$sql = "SELECT * FROM weight_groups";
		$groups = $this->connection->Query($sql);
		if($groups) return $groups;
		else return false;
	}

	public function getWeightGroupFromWeight($weight)
	{
		$sql = "SELECT * FROM weight_groups WHERE min_weight<=$weight and max_weight > $weight";
		$weight_group = $this->connection->Query($sql);
		if($weight_group) return $weight_group[0];
		else return false;
	}
}