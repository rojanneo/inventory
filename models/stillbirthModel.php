<?php
class StillbirthModel extends Model
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
			$sql = "INSERT INTO `rabbit_still_birth`
			(`rabbit_id`,
			`still_birth_date`,
			`still_birth_reason`
			)
			VALUES 
			(
			'".mysql_escape_string($rabbit_id)."',
			'".mysql_escape_string($still_birth_date)."',
			'".mysql_escape_string($still_birth_reason)."'
			)";
			$this->connection->Query($sql);
			return $this->connection->GetInsertID();
		}
		else
		{
			return false;
		}
	}

	public function getCollection()
	{
		$sql = "SELECT * FROM rabbit_still_birth";
		$still_births = $this->connection->Query($sql);
		return $still_births;
	}


	public function load($still_birth_id)
	{
		$sql = "SELECT * FROM rabbit_still_birth WHERE still_birth_id = ".$still_birth_id." Limit 1";
		$still_birth = $this->connection->Query($sql);
		if($still_birth) return $still_birth[0];
		else $still_birth;
	}

	public function loadByRabbitId($rabbit_id)
	{
		$sql = "SELECT * FROM rabbit_still_birth WHERE rabbit_id = ".$rabbit_id." Limit 1";
		$still_birth = $this->connection->Query($sql);
		if($still_birth) return $litter;
		else $still_birth;
	}

	public function delete($still_birth_id)
	{
		$sql = "DELETE FROM rabbit_still_birth WHERE still_birth_id = ".$still_birth_id;
		$this->connection->DeleteQuery($sql);
		return true;
	}

	
}