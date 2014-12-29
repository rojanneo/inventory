<?php
class LitterModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function insert($data = false)
	{
		if($data != false)
		{
			extract($data);
			$updated_date = date('Y-m-d');
			$sql = "INSERT INTO `rabbit_litters`
			(`parent_id`,
			`family_id`,
			`litters_dob`,
			`updated_date`)
			VALUES 
			(
			'".mysql_escape_string($parent_rabbit_id)."',
			'".mysql_escape_string($rabbit_family_id)."',
			'".date('Y-m-d')."',
			'".$updated_date."'
			)";
			$this->connection->Query($sql);
			return $this->connection->GetInsertID();
		}
		else
		{
			return false;
		}
	}

	public function getCollection($parent_id)
	{
		$sql = "SELECT * FROM rabbit_litters WHERE parent_id = $parent_id";
		
		$litters = $this->connection->Query($sql);
		return $litters;
	}

	public function wean($rabbit_id)
	{
		$today = date('Y-m-d');
		$sql = "UPDATE rabbit_litters SET litters_weaning_date = '".$today."' WHERE parent_id = $rabbit_id";
		$this->connection->UpdateQuery($sql);
		return true;
	}
}