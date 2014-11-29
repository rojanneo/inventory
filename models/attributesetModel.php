<?php 
class AttributesetModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCollection($condition = false)
	{
		$where = '';
		if($condition and is_array($condition))
		{
			$where = $this->generateWhereCondition($condition);
		}
		else
		{
			$where = 1;
		}
		$sql = "SELECT `attribute_set_id`, `attribute_set_code`,  `attribute_set_name` FROM `attribute_sets` WHERE ".$where." ORDER BY sort_order";
		$sets = $this->connection->Query($sql);
		if($sets)
			return $sets;
		else return false;
	}

	public function insert($data = false)
	{
		if($data != false)
		{
			extract($data);
			$sql = "INSERT INTO `attribute_sets`(
				`attribute_set_code`, 
				`attribute_set_name`, 
				`sort_order`) 
				VALUES (
				'".mysql_escape_string($attribute_set_code)."',
				'".mysql_escape_string($attribute_set_name)."',
				'".mysql_escape_string($sort_order)."')";
			$this->connection->InsertQuery($sql);
			return $this->connection->GetInsertID();
		}
		else
		{
			return false;
		}
	}

	public function insertRelation($data = false)
	{
		if($data != false)
		{
			extract($data);
			$sql = "INSERT INTO `attribute_attributeset`(
				`attribute_id`, 
				`attribute_set_id`, 
				`sort_order`) 
			VALUES (
				'".mysql_escape_string($attribute_id)."',
				'".mysql_escape_string($attribute_set_id)."',
				'".mysql_escape_string($sort_order)."')";
			$this->connection->InsertQuery($sql);
			return $this->connection->GetInsertID();
		}
		else return false;
	}

	public function delete($condition = false)
	{
		if($condition and is_array($condition))
		{
			$where = $this->generateWhereCondition($condition);

			$sql = "DELETE FROM attribute_sets WHERE ".$where;
			$this->connection->DeleteQuery($sql);
			return true;
		}
		else return false;

	}

	public function load($condition = false)
	{

		if($condition and is_array($condition))
		{
			$where = $this->generateWhereCondition($condition);
			$sql = "SELECT * FROM attribute_sets WHERE ".$where;
			$attribute = $this->connection->Query($sql);
			if($attribute)
				return $attribute[0];
			else return false;
		}
		else return false;

	}

	public function getAttributes($condition = false)
	{
		if($condition and is_array($condition))
		{
				$where = $this->generateWhereCondition($condition);
				$sql = "SELECT attribute_id FROM attribute_attributeset WHERE ".$where;
				$attributes = $this->connection->Query($sql);
				if($attributes)
				{
					$array = array();
					foreach($attributes as $attribute)
					{
						array_push($array, $attribute['attribute_id']);
					}
					return $array;
				}
				else return false;
		}
		else return false;
	}

	public function update($data = false)
	{
		if($data != false)
		{
			extract($data);
			$sql = "UPDATE `attribute_sets` SET 
			`attribute_set_code`='".mysql_escape_string($attribute_set_code)."',
			`attribute_set_name`='".mysql_escape_string($attribute_set_name)."',
			`sort_order`='".mysql_escape_string($sort_order)."' 
			WHERE `attribute_set_id`=".mysql_escape_string($attribute_set_id);

			$this->connection->UpdateQuery($sql);
			return $attribute_set_id;
		}
		else
			return false;
	}

	public function relationExists($condition = false)
	{
		if($condition and is_array($condition))
		{
			$where = $this->generateWhereCondition($condition);
			$sql = "SELECT id FROM attribute_attributeset WHERE ".$where;
			$relations = $this->connection->Query($sql);
			if($relations)
				return $relations[0]['id'];
			else return false;
		}
		else
			return false;
	}

	public function deleteExtraRelations($ids, $attribute_set_id)
	{
		$where = '';
		foreach($ids as $id)
		{
			$where .= '`id` = '.$id.' OR ';
		}
		$where = rtrim($where, ' OR ');
		$sql = "DELETE FROM attribute_attributeset WHERE NOT(".$where.")  AND attribute_set_id = '".$attribute_set_id."'";
		$this->connection->DeleteQuery($sql);
	}
}