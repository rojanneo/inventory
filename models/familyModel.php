<?php
class FamilyModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCollection()
	{
		$attribute = getModel('attribute')->load(array('AND','attribute_code'=>'rabbit_family_id'));
		$sql = "SELECT value FROM products_inventory AS p JOIN product_attribute_value_number ON p.product_id = product_attribute_value_number.product_id WHERE attribute_id = ".$attribute['attribute_id']." group BY value";
		$families = $this->connection->Query($sql);
		$family_array = array();
		foreach($families as $family)
		{
			$id = $family['value'];
			array_push($family_array,$id);
		}
		return $family_array;
	}
}