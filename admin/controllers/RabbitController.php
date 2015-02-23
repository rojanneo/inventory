<?php

class RabbitController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction()
	{
		$this->view->renderAdmin('rabbit/landing.phtml');
	}

	public function searchAction()
	{
		loadHelper('inputs');
		$rabbit_id_query = getParam('query');
		if(is_numeric($rabbit_id_query)) 
		{
			$rabbit = getModel("rabbit")->load($rabbit_id_query);
			if($rabbit) 
			{
				$data['rabbit'] = $rabbit;
				$this->view->renderAdmin('rabbit/rabbit.phtml', $data, false, false,false);
			}
			else echo 'No Rabbit with that ID exists';
		}
	}
}