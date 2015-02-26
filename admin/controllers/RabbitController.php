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
                                $data['death_reasons'] = getModel('rabbit')->getDeathReasons();
				$this->view->renderAdmin('rabbit/rabbit.phtml', $data, false, false,false);
			}
			else echo 'No Rabbit with that ID exists';
		}
	}
        
        public function sickAction()
        {
            loadHelper('inputs');
            $data = getPost();
            if(!isset($data['sick_reason_id']))
            {
                echo '<p>Select a Reason</p>';
            }
            else
            {
                $rabbit_id = $data['rabbit_id'];
                $sick_reason_id = $data['sick_reason_id'];
                if($sick_reason_id == -1)
                {
                    $reason = $data['sick_reason'];
                    $reason_description = $data['sick_reason_desc'];
                    $sick_reason_id = getModel('rabbit')->addSickReason($reason,$reason_description);
                }
                getModel('product')->updateDefaultAttribute($rabbit_id,'is_sick','1');
                getModel('rabbit')->sick($rabbit_id,$sick_reason_id);
                $arr = array();
                $arr['identifier'] = '#sick_rabbit_count';
                $arr['html'] = getModel('rabbit')->getSickRabbitCount();;
                echo json_encode($arr);
            }
        }
        
        
        public function deathAction()
        {
            loadHelper('inputs');
            $data = getPost();
            if(!isset($data['death_reason_id']))
            {
                echo '<p>Select a Reason</p>';
            }
            else
            {
                $rabbit_id = $data['rabbit_id'];
                $death_reason_id = $data['death_reason_id'];
                if($death_reason_id == -1)
                {
                    $reason = $data['death_reason'];
                    $reason_description = $data['death_reason_desc'];
                    $death_reason_id = getModel('rabbit')->addDeathReason($reason,$reason_description);
                }
                getModel('product')->updateDefaultAttribute($rabbit_id,'is_dead','1');
                getModel('rabbit')->dead($rabbit_id,$death_reason_id);
                $arr = array();
                $arr['identifier'] = '#dead_rabbit_count';
                $arr['html'] = getModel('rabbit')->getDeadRabbitCount();;
                echo json_encode($arr);
            }
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