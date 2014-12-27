<?php
class RabbitsController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		loadHelper('url');
	}

	public function indexAction()
	{
		$data['families'] = getModel('family')->getCollection();

		$this->view->renderAdmin('rabbits/list.phtml',$data);
	}

	public function performMatingAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		getModel('rabbit')->performMating($post_data['family_Male'], $post_data['family_Female']);

		redirect('admin/rabbits');
	}

	public function listActionsAction($rabbit_id)
	{
		$data['rabbit_id'] = $rabbit_id;
		$this->view->renderAdmin('rabbits/actions.phtml',$data,false,false,false);
	}

	public function pregnantAction($rabbit_id)
	{
		$date = date('Y-m-d');
		getModel('product')->updateAttribute($rabbit_id, 'rabbit_latest_pregnant_date', $date);
		getModel('product')->updateAttribute($rabbit_id, 'is_pregnant', '19');
		redirect('admin/rabbits');
	}

	public function notpregnantAction($rabbit_id)
	{}
}