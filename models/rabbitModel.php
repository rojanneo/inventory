<?php
class RabbitModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getFamilyRabbits($family_id)
	{
		$attribute = getModel('attribute')->load(array('AND','attribute_code'=>'rabbit_family_id'));
		$sql = "SELECT p.product_id FROM products_inventory AS p JOIN product_attribute_value_number ON p.product_id = product_attribute_value_number.product_id WHERE attribute_id = '".$attribute['attribute_id']."' AND value=".$family_id;
		$rabbits = $this->connection->Query($sql);
		$rabbit_array = array();
		foreach($rabbits as $rabbit)
		{
			array_push($rabbit_array, $rabbit['product_id']);
		}
		return $rabbit_array;
	}

	public function load($rabbit_id)
	{
		$sql = "SELECT * FROM `products_inventory` WHERE product_id = ".$rabbit_id.' LIMIT 1';
		$rabbit = $this->connection->Query($sql);
		$ra = array();
		foreach($rabbit[0] as $attribute_code => $value)
		{
			$ra[$attribute_code] = $value;
		}

		$attributes = getModel('product')->getAttributes($rabbit_id);
		foreach($attributes as $attribute)
		{
			$ra[$attribute['attribute_code']] = $attribute['value'];
			if($attribute['value'] == '0000-00-00') $ra[$attribute['attribute_code']] = null;
		}

		return $ra;
	}

	public function performMating($male, $female)
	{
		$date = date('Y-m-d');
		getModel('product')->updateAttribute($male, 'rabbit_latest_mate_date', $date);
		getModel('product')->updateAttribute($female, 'rabbit_latest_mate_date', $date);

		return true;
	}

	public function makePregnant($rabbit_id)
	{
		$date = date('Y-m-d');
		getModel('product')->updateAttribute($rabbit_id, 'rabbit_latest_pregnant_date', $date);
		getModel('product')->updateAttribute($rabbit_id, 'is_pregnant', '19');
	}

	public function notPregnant($rabbit_id)
	{
		getModel('product')->deleteAttribute($rabbit_id, 'is_pregnant');
		getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_pregnant_date');
		getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_mate_date');
	}

	public function giveBirth($rabbit_id)
	{
		getModel('product')->updateAttribute($rabbit_id,'rabbit_latest_birth_date',date('Y-m-d'));
	}

	public function wean($rabbit_id)
	{
		getModel('product')->updateAttribute($rabbit_id,'rabbit_latest_weaning_date',date('Y-m-d'));
	}

	public function resetDates($rabbit_id)
	{
		getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_mate_date');
		getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_pregnant_date');
		getModel('product')->deleteAttribute($rabbit_id, 'is_pregnant');
		getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_birth_date');
		getModel('product')->deleteAttribute($rabbit_id, 'rabbit_latest_weaning_date');
	}
}