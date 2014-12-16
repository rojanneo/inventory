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
}