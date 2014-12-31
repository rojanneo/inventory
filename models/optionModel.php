<?php
class OptionModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function load($option_id)
	{
		$sql = "SELECT * FROM attribute_values WHERE id = ".$option_id." LIMIT 1";
		$option = $this->connection->Query($sql);
		if($option) return $option[0];
		else return false;
	}

	public function insert($data = false)
	{
		if($data != false)
		{
			extract($data);
			$sql = "INSERT INTO `attribute_values`(
				`attribute_id`,
				`value`, 
				`sort_order`) 
				VALUES (
				".$attribute_id.",
				'".mysql_escape_string($value)."',
				".mysql_escape_string($sort_order).")";
			$this->connection->InsertQuery($sql);
			return $this->connection->GetInsertID();
		}
		else
		{
			return false;
		}
	}

	public function update($data = false)
	{
		if($data != false)
		{
			extract($data);
			$sql = "UPDATE `attribute_values` SET 
			`attribute_id`='".mysql_escape_string($attribute_id)."',
			`value`='".mysql_escape_string($value)."',
			`sort_order`='".mysql_escape_string($sort_order)."' 
			WHERE `id` = ".$id;

			$this->connection->UpdateQuery($sql);
			return $id;
		}
		else
		{
			return false;
		}
	}

	public function getCollection($condition = false)
	{
		if($condition and is_array($condition))
		{
			$where = $this->generateWhereCondition($condition);

			$sql = "SELECT * FROM attribute_values WHERE ".$where." ORDER BY sort_order";
			$options = $this->connection->Query($sql);
			if($options)
				return $options;
			else return false;
		}
		else return false;
	}

	public function exists($id)
	{
		$sql = "SELECT * FROM attribute_values WHERE id = ".$id;
		$option = $this->connection->Query($sql);
		if($option) return true;
		else return false;
	}

	public function deleteExtraOptions($ids)
	{
		$where = '';
		foreach($ids as $id)
		{
			$where .= '`id` = '.$id.' OR ';
		}
		$where = rtrim($where, ' OR ');
		$sql = "DELETE FROM attribute_values WHERE NOT(".$where.")";
		$this->connection->DeleteQuery($sql);
	}

	
}