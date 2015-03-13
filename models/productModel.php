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
			$where = "Where 1";
		}
		//$sql = "SELECT * FROM `products_inventory` WHERE ".$where." ORDER BY sort_order";
		$sql = "SELECT * FROM `products_inventory` ".$where." ORDER BY product_id";
		$products= $this->connection->Query($sql);
		if($products)
			return $products;
		else return false;
	}

	public function getAutoIncrementID()
	{
		$sql="SELECT AUTO_INCREMENT FROM information_schema.`TABLES`WHERE `table_schema` = 'inventory' AND `table_name` = 'products_inventory'";
		$ID =$this->connection->Query($sql);
		return $ID[0]['AUTO_INCREMENT'];
	}

	public function insert($data = false)
	{
		if($data != false)
		{
			extract($data);
			if(!isset($sort_order)) $sort_order = 0;
			if(!isset($daily_use_quantity)) $daily_use_quantity = null;
			$sql = "INSERT INTO `products_inventory`(
				`product_type_id`, 
				`attribute_set_id`, 
				`product_name`, 
				`product_sku`, 
				`daily_use_status`,
				`daily_use_quantity`,
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
				'".mysql_escape_string($daily_use_status)."',
				'".mysql_escape_string($daily_use_quantity)."',
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

	public function load($product_id)
	{
		$sql = "SELECT * FROM `products_inventory` WHERE product_id = ".$product_id.' LIMIT 1';
		$product = $this->connection->Query($sql);
		$pr = array();
		foreach($product[0] as $attribute_code => $value)
		{
			$pr[$attribute_code] = $value;
		}

		$attributes = getModel('product')->getAttributes($product_id);
		foreach($attributes as $attribute)
		{
			$pr['attributes'][$attribute['attribute_code']] = $attribute['value'];
		}
		$categories = $this->getCategories($product_id);
		if($categories)
		{
			$pr['categories'] = array();
			foreach($categories as $cat)
			{
				array_push($pr['categories'],$cat);
			}
		}

		return $pr;
	}

	public function loadBySku($sku)
	{
		$sql = "SELECT * FROM `products_inventory` WHERE product_sku = ".$sku.' LIMIT 1';
		$product = $this->connection->Query($sql);
		$pr = array();
		foreach($product[0] as $attribute_code => $value)
		{
			$pr[$attribute_code] = $value;
		}

		$attributes = getModel('product')->getAttributes($product_id);
		foreach($attributes as $attribute)
		{
			$pr[$attribute['attribute_code']] = $attribute['value'];
		}

		return $pr;

	}

	public function update($data)
	{
		if($data != false)
		{
			extract($data);
			if(!isset($sort_order)) $sort_order = 0;
			if(!isset($daily_use_quantity)) $daily_use_quantity = null;
			$sql = "UPDATE `products_inventory` SET 
			`product_name`='".mysql_escape_string($product_name)."',
			`product_sku`='".mysql_escape_string($product_sku)."',
			`daily_use_status`='".mysql_escape_string($daily_use_status)."',
			`daily_use_quantity`='".mysql_escape_string($daily_use_quantity)."',
			`product_quantity`='".mysql_escape_string($product_quantity)."',
			`in_stock`='".mysql_escape_string($in_stock)."',
			`unit_price`='".mysql_escape_string($unit_price)."',
			`status`='".mysql_escape_string($status)."',
			`is_variation`='".mysql_escape_string($is_variation)."',
			`sort_order`='".mysql_escape_string($sort_order)."',
			`created_date`='".mysql_escape_string($created_date)."',
			`updated_date`='".date('Y-m-d')."',
			`product_type`='".mysql_escape_string($product_type)."' WHERE product_id = ".mysql_escape_string($product_id);

			$this->connection->UpdateQuery($sql);
			return true;

		}
		else return false;
	}

	public function updateAttributes($attributes, $product_id)
	{
		if(isset($attributes) and $attributes)
		{
			foreach($attributes as $attribute_code => $value)
			{
				$this->updateAttribute($product_id,$attribute_code,$value);
			}
		}
		else return false;
	}

	public function updateDefaultAttribute($product_id, $attribute_code, $value)
	{
		$sql = "UPDATE `products_inventory` SET `".$attribute_code."`='".$value."' WHERE `product_id` = ".$product_id;
		$this->connection->UpdateQuery($sql);
		return true;
	}

	public function updateAttribute($product_id,$attribute_code, $value)
	{
		$attribute = getModel('attribute')->load(array('AND','attribute_code'=>$attribute_code));
		$product = $this->load($product_id);
		if($attribute)
		{
			$id = $attribute['attribute_id'];
			$attribute_type = $attribute['attribute_type'];
			if($attribute_type == 'select' or $attribute_type == 'multiselect') $suffix = 'option';
				else $suffix = $attribute_type;
			$table_name = 'product_attribute_value_'.$suffix;
			$updated_date = date('Y-m-d');
			if(!isset($product['attributes'][$attribute_code]))
			{
				$sql = "INSERT INTO `".$table_name."` (`attribute_id`, `product_id`, `value`, `updated_date`)
				VALUES ('".$id."', '".$product_id."', '".$value."','".$updated_date."')";

				$this->connection->InsertQuery($sql);
			}
			else
			{
				$sql = "UPDATE `".$table_name."` SET 
				`value`='".$value."',
				`updated_date`='".$updated_date."' 
				WHERE `attribute_id` = ".$id." AND `product_id` = ".$product_id;
				$this->connection->UpdateQuery($sql);

			}
				
		}
		else return false;
	}

	public function deleteAttribute($product_id, $attribute_code)
	{
		$attribute = getModel('attribute')->load(array('AND','attribute_code'=>$attribute_code));
		$product = $this->load($product_id);
		if($attribute)
		{
			$id = $attribute['attribute_id'];
			$attribute_type = $attribute['attribute_type'];
			if($attribute_type == 'select' or $attribute_type == 'multiselect') $suffix = 'option';
				else $suffix = $attribute_type;
			$table_name = 'product_attribute_value_'.$suffix;
			$updated_date = date('Y-m-d');
				$sql = "DELETE FROM `".$table_name."` WHERE `attribute_id` = ".$id." AND `product_id` = ".$product_id;
				$this->connection->DeleteQuery($sql);
				
		}
		else return false;
	}

	public function updateCategories($categories,$product_id)
	{
		$sql = "DELETE FROM product_category WHERE product_id = ".$product_id;
		$this->connection->DeleteQuery($sql);
		$categories['product_id'] = $product_id;
		return $this->insertCategories($categories);
	}

	public function delete($condition = false)
	{
		if($condition and is_array($condition))
		{
			$where = $this->generateWhereCondition($condition);

			$sql = "DELETE FROM products_inventory WHERE ".$where;
			$this->connection->DeleteQuery($sql);
			return true;
		}
		else return false;

	}

	public function getCategories($product_id)
	{
		$sql = "SELECT category_id FROM `product_category` WHERE product_id = $product_id";
		$categories = $this->connection->Query($sql);
		if(($categories))
		{
			$cat = array();
			foreach($categories as $category)
			{
				array_push($cat,$category['category_id']);
			}
			return $cat;
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