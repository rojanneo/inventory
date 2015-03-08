<?php
class FamilyModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCollection()
	{
		$attribute = getModel('attribute')->load(array('attribute_code'=>'rabbit_family_id'));
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

	public function getAvailableFamilies($gender,$rabbit_id)
	{
		$families = $this->getCollection();
		$available_families = array();
		foreach($families as $family)
		{
			$rabbits = getModel('rabbit')->getFamilyRabbits($family);
			$male_count = 0;
			$female_count = 0;
			$is_available = true;
			if($gender == 'Female')
			{
				foreach($rabbits as $rabbit)
				{
					$r = getModel('rabbit')->load($rabbit);
					if($r['rabbit_gender'] == 'Male')
					{
						$status = getModel('genealogyRabbit')->rabbitmate($rabbit_id, $r['product_id']);
						if($status != 'Yes They Can Mate' and $status != 'Yes This Rabbit Can Be Mated') $is_available = false;
					}
					if($r['rabbit_gender'] == 'Male' and isset($r['rabbit_group']) and $r['rabbit_group'] == 'Parents')
					{
						$male_count++;
					}
				}
				if($male_count <= 0 and $male_count < 1) $is_available = false;

				if($is_available) array_push($available_families,$family);
			}
			elseif($gender == 'Male')
			{
				foreach($rabbits as $rabbit)
				{
					$r = getModel('rabbit')->load($rabbit);
					if($r['rabbit_gender'] == 'Female')
					{
						$status = getModel('genealogyRabbit')->rabbitmate($rabbit_id, $r['product_id']);
						if($status != 'Yes They Can Mate' and $status != 'Yes This Rabbit Can Be Mated') $is_available = false;
					}
					if($r['rabbit_gender'] == 'Female' and isset($r['rabbit_group']) and $r['rabbit_group'] == 'Parents')
					{
						$female_count++;
					}
				}
				if($female_count <= 0 and $female_count < 3) $is_available = false;

				if($is_available) array_push($available_families,$family);

			}


			// foreach($rabbits as $rabbit)
			// {
			// 	$r = getModel('rabbit')->load($rabbit);
			// 	if($r['rabbit_gender'] == 'Male' and isset($r['rabbit_group']) and $r['rabbit_group'] == 'Parents')
			// 	{
			// 		$male_count++;
			// 	}
			// 	elseif($r['rabbit_gender'] = 'Female' and isset($r['rabbit_group']) and  $r['rabbit_group'] == 'Parents')
			// 	{
			// 		$female_count++;
			// 	}
			// }
			// if($gender == 'Male')
			// {
			// 	if($male_count >=0 and $male_count < 1)
			// 	{
			// 		array_push($available_families, $family);
			// 	}
			// }
			// elseif($gender == 'Female')
			// {
			// 	if($female_count >=0 and $female_count < 3)
			// 	{	
			// 		array_push($available_families, $family);
			// 	}
			// }
		}
		return $available_families;
	}
}