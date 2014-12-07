<?php
class RabbitsController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction()
	{
		$families = getModel('family')->getCollection();
		foreach($families as $family)
		{
			echo '<h3>Family '.$family.'</h3>';
			$rabbits = (getModel('rabbit')->getFamilyRabbits($family));
			foreach($rabbits as $rabbit_id)
			{
				$rabbit = getModel('rabbit')->load($rabbit_id);
				echo $rabbit['product_name'].' ';
				$dob = new DateTime($rabbit['rabbit_dob']);
				$today = new DateTime(date('Y-m-d'));
				$diff = $today->diff($dob)->format("%a");
				echo $diff.' days';
				if($diff >= 4) echo 'Ready to Mate';
			}
		}
	}



}