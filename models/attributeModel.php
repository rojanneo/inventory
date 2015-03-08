<?php

class AttributeModel extends Model
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
			$sql = "INSERT INTO `attributes`(
					`attribute_code`, 
					`attribute_default_value`,
					`attribute_type`,
					`attribute_requires_editor`, 
					`attribute_admin_label`,
					`attribute_frontend_label`, 
					`is_unique`, 
					`is_required`, 
					`is_used_for_variation`,
					`is_hidden`)
					VALUES (
					'".mysql_escape_string($attribute_code)."',
					'".mysql_escape_string($attribute_default_value)."',
					'".mysql_escape_string($attribute_type)."',
					'".mysql_escape_string($attribute_requires_editor)."',
					'".mysql_escape_string($attribute_admin_label)."',
					'".mysql_escape_string($attribute_frontend_label)."',
					'".mysql_escape_string($is_unique)."',
					'".mysql_escape_string($is_required)."',
					'".mysql_escape_string($is_used_for_variation)."',
					'".mysql_escape_string($is_hidden)."')";
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
			$sql = "UPDATE `attributes` SET 
			`attribute_code`='".mysql_escape_string($attribute_code)."',
			`attribute_default_value`='".mysql_escape_string($attribute_default_value)."',
			`attribute_type`='".mysql_escape_string($attribute_type)."',
			`attribute_requires_editor`='".mysql_escape_string($attribute_requires_editor)."',
			`attribute_admin_label`='".mysql_escape_string($attribute_admin_label)."',
			`attribute_frontend_label`='".mysql_escape_string($attribute_frontend_label)."',
			`is_unique`='".mysql_escape_string($is_unique)."',
			`is_required`='".mysql_escape_string($is_required)."',
			`is_used_for_variation`=".mysql_escape_string($is_used_for_variation).",
			`is_hidden`='".mysql_escape_string($is_hidden)."' 
			WHERE `attribute_id` = ".$attribute_id;
			$this->connection->UpdateQuery($sql);
			return $attribute_id;
		}
		else
			return false;
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
		$sql = "SELECT `attribute_id`, `attribute_code`, `attribute_default_value`, `attribute_type`, `attribute_admin_label`,`is_unique`, `is_required`, `is_used_for_variation` FROM `attributes` ".$where;
		$attributes = $this->connection->Query($sql);
		if($attributes)
			return $attributes;
		else return false;
	}

	public function delete($condition = false)
	{
		if($condition and is_array($condition))
		{
			$where = $this->generateWhereCondition($condition);

			$sql = "DELETE FROM attributes ".$where;
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
			$sql = "SELECT * FROM attributes ".$where;
			$attribute = $this->connection->Query($sql);
			if($attribute)
				return $attribute[0];
			else return false;
		}
		else return false;

	}

	public function getOptions($attribute_id)
	{
		$options = getModel('option')->getCollection(array('type'=>'AND','attribute_id'=>$attribute_id));
		return $options;
	}

}