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

				$sql = "INSERT INTO `".$table_name."`(
					`attribute_id`, 
					`product_id`, 
					`value`) 
					VALUES (
					'".mysql_escape_string($attribute_id)."',
					'".mysql_escape_string($product_id)."',
					'".mysql_escape_string($attr_value)."')";
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
}