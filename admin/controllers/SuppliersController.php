<?php
class SuppliersController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction()
	{}

	public function newAction()
	{
		$this->view->renderAdmin('suppliers/form.phtml');
	}
}