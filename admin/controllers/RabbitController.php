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
                                $data['sick_reasons'] = getModel('rabbit')->getSickReasons();
				$this->view->renderAdmin('rabbit/rabbit.phtml', $data, false, false,false);
			}
			else echo 'No Rabbit with that ID exists';
		}
	}
        
        public function sickAction()
        {
            loadHelper('inputs');
            var_dump(getPost());
        }
        
        public function sicklistAction()
        {
            $sick_rabbits = getModel('rabbit')->getSickRabbits();
            $data['sick_rabbits'] = $sick_rabbits;
            $this->view->renderAdmin('rabbit/sick_list.phtml',$data);
        }
        
        public function deathlistAction()
        {
            $dead_rabbits = getModel('rabbit')->getDeadRabbits();
            $data['dead_rabbits'] = $dead_rabbits;
            $this->view->renderAdmin('rabbit/death_list.phtml',$data);
        }

        public function shiftedlistAction()
        {
        	$shifted_rabbits = getModel('rabbit')->getShiftedRabbits();
            $data['shifted_rabbits'] = $shifted_rabbits;
            $this->view->renderAdmin('rabbit/shifted_list.phtml',$data);
        }
}