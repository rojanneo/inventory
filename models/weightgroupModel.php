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
}