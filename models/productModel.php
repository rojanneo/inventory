<?php 
class ProductModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCollection($condition = false)
	{
		if($condition and is_array($condition))
		{
			$where = $this->generateWhereCondition($condition);
		}
		else
		{
			$where = 1;
		}
		$sql = "SELECT * FROM `products_inventory` WHERE ".$where." ORDER BY sort_order";
		$products= $this->connection->Query($sql);
		if($products)
			return $products;
		else return false;
	}

	public function insert($data = false)
	{
		if($data != false)
		{
			extract($data);
			if(!isset($sort_order)) $sort_order = 0;
			$sql = "INSERT INTO `products_inventory`(
				`product_type_id`, 
				`attribute_set_id`, 
				`product_name`, 
				`product_sku`, 
				`product_quantity`, 
				`in_stock`, 
				`unit_price`, 
				`status`, 
				`is_variation`, 
				`sort_order`, 
				`created_date`, 
				`updated_date`, 
				`product_type`) 
				VALUES (
				'".mysql_escape_string($product_type_id)."',
				'".mysql_escape_string($attribute_set_id)."',
				'".mysql_escape_string($product_name)."',
				'".mysql_escape_string($product_sku)."',
				'".mysql_escape_string($product_quantity)."',
				'".mysql_escape_string($in_stock)."',
				'".mysql_escape_string($unit_price)."',
				'".mysql_escape_string($status)."',
				'".mysql_escape_string($is_variation)."',
				'".mysql_escape_string($sort_order)."',
				'".mysql_escape_string($created_date)."',
				'".mysql_escape_string($updated_date)."',
				'".mysql_escape_string($product_type)."')";
			$this->connection->InsertQuery($sql);
			return $this->connection->GetInsertID();
		}
		else
			return false;
	}

	public function insertAttributes($data = false)
	{
		if($data != false)
		{
			$product_id = $data['product_id'];
			unset($data['product_id']);
			foreach($data as $attr_code => $attr_value)
			{
				$attribute = getModel('attribute')->load(array('AND', 'attribute_code'=>$attr_code));
				extract($attribute);
				if($attribute_type == 'select' or $attribute_type == 'multiselect') $suffix = 'option';
				else $suffix = $attribute_type;

				$table_name = 'product_attribute_value_'.$suffix;
				$updated_date = date('Y-m-d');
				$sql = "INSERT INTO `".$table_name."`(
					`attribute_id`, 
					`product_id`, 
					`value`,
					`updated_date`) 
					VALUES (
					'".mysql_escape_string($attribute_id)."',
					'".mysql_escape_string($product_id)."',
					'".mysql_escape_string($attr_value)."',
					'".$updated_date."')";
				$this->connection->InsertQuery($sql);
			}
			return true;
		}
		else return false;
	}

	public function insertCategories($data = false)
	{
		if($data != false)
		{
			$product_id = $data['product_id'];
			unset($data['product_id']);
			foreach($data as $category_id)
			{
				$sql = "INSERT INTO `product_category`(
					`product_id`, 
					`category_id`, 
					`sort_order`) 
					VALUES (
					'".mysql_escape_string($product_id)."',
					'".mysql_escape_string($category_id)."',
					'0')";
				$this->connection->InsertQuery($sql);
			}
			return true;
		}
		else return false;
	}

	public function getAttributes($product_id)
	{
		$sql = "SELECT attribute_code,value FROM `product_attribute_value_date` JOIN `attributes` ON product_attribute_value_date.attribute_id = attributes.attribute_id WHERE product_id = ".$product_id;
		$attributes1 = $this->connection->Query($sql);
		$sql = "SELECT attribute_code,value  FROM `product_attribute_value_number` JOIN `attributes` ON product_attribute_value_number.attribute_id = attributes.attribute_id WHERE product_id = ".$product_id;
		$attributes2 = $this->connection->Query($sql);
		$sql = "SELECT attribute_code,value  FROM `product_attribute_value_text` JOIN `attributes` ON product_attribute_value_text.attribute_id = attributes.attribute_id WHERE product_id = ".$product_id;
		$attributes3 = $this->connection->Query($sql);
		$sql = "SELECT attribute_code, attribute_values.value FROM (SELECT attributes.attribute_id,product_id,attribute_code,updated_date,attribute_admin_label,attribute_frontend_label,value FROM `product_attribute_value_option` JOIN `attributes` ON product_attribute_value_option.attribute_id = attributes.attribute_id WHERE 1) AS p_a JOIN attribute_values ON attribute_values.id = p_a.value WHERE product_id = ".$product_id;
		$attributes4 = $this->connection->Query($sql);

		$array = array_merge($attributes1,$attributes2,$attributes3,$attributes4);

		return $array;

	}

	public function getDateAttributes($product_id)
	{
		$sql = "SELECT attribute_code,value FROM `product_attribute_value_date` JOIN `attributes` ON product_attribute_value_date.attribute_id = attributes.attribute_id WHERE product_id = ".$product_id;
		$attributes = $this->connection->Query($sql);
		if($attributes) return $attributes;
		else return false;
	}

	public function getNumberAttributes($product_id)
	{
		$sql = "SELECT attribute_code,value  FROM `product_attribute_value_number` JOIN `attributes` ON product_attribute_value_number.attribute_id = attributes.attribute_id WHERE product_id = ".$product_id;
		$attributes = $this->connection->Query($sql);
		if($attributes) return $attributes;
		else return false;
	}

	public function getTextAttributes($product_id)
	{
		$sql = "SELECT attribute_code,value  FROM `product_attribute_value_text` JOIN `attributes` ON product_attribute_value_text.attribute_id = attributes.attribute_id WHERE product_id = ".$product_id;
		$attributes = $this->connection->Query($sql);
		if($attributes) return $attributes;
		else return array();
	}

	public function getOptionAttributes($product_id)
	{
		$sql = "SELECT attribute_code, attribute_values.value FROM (SELECT attributes.attribute_id,product_id,attribute_code,updated_date,attribute_admin_label,attribute_frontend_label,value FROM `product_attribute_value_option` JOIN `attributes` ON product_attribute_value_option.attribute_id = attributes.attribute_id WHERE 1) AS p_a JOIN attribute_values ON attribute_values.id = p_a.value WHERE product_id = ".$product_id;
		$attributes = $this->connection->Query($sql);
		if($attributes) return $attributes;
		else return false;
	}
}