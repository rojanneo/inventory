<?php
class GenealogyModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function insertRabbit($data = false)
	{
		if($data != false)
		{
			$this->insertFamily($data['f_id']);
			$sql = "INSERT INTO `aa_rabbits`(`r_id`, `type`, `l_id`, `f_id`, `does_id`, `buck_id`, `last_given_birth`, `status`, `rabbit_slug`) 
			VALUES (
			".$data['r_id'].",
			'".$data['type']."',
			".$data['l_id'].",
			".$data['f_id'].",
			".$data['does_id'].",
			".$data['buck_id'].",
			".$data['last_given_birth'].",
			'".$data['status']."',
			'".mysql_escape_string($data['rabbit_slug'])."')";
			$this->connection->InsertQuery($sql);
			$r_id = $this->connection->GetInsertID();
			getModel('genealogyRabbit')->insertintofamilytobe($r_id,$data['type']);

			return $r_id;
		}
		else 
			return false;
	}

	public function insertFamily($family_id)
	{
		$sql = "SELECT * FROM aa_family WHERE f_id = $family_id";
		$family = $this->connection->Query($sql);
		if(!$family)
		{
			$sql = "INSERT INTO aa_family(f_id, created_DT) VALUES (".$family_id.", '".date('Y-m-d')."')";
			$this->connection->InsertQuery($sql);
		}
	}

}