<?php
class RabbitsController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction()
	{
		$data['families'] = getModel('family')->getCollection();

		$this->view->renderAdmin('rabbits/list.phtml',$data);
	}
}