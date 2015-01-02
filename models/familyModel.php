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

	public function getAvailableFamilies($gender)
	{
		$families = $this->getCollection();
		$available_families = array();
		foreach($families as $family)
		{
			$rabbits = getModel('rabbit')->getFamilyRabbits($family);
			$male_count = 0;
			$female_count = 0;
			foreach($rabbits as $rabbit)
			{
				$r = getModel('rabbit')->load($rabbit);
				if($r['rabbit_gender'] == 'Male' and isset($r['rabbit_group']) and $r['rabbit_group'] == 'Parents')
				{
					$male_count++;
				}
				elseif($r['rabbit_gender'] = 'Female' and isset($r['rabbit_group']) and  $r['rabbit_group'] == 'Parents')
				{
					$female_count++;
				}
			}
			if($gender == 'Male')
			{
				if($male_count >=0 and $male_count < 1)
				{
					array_push($available_families, $family);
				}
			}
			elseif($gender == 'Female')
			{
				if($female_count >=0 and $female_count < 3)
				{	
					array_push($available_families, $family);
				}
			}
		}
		return $available_families;
	}
}