<?php
	require_once 'system/view.php';
	class Controller
	{
		protected $view;
		protected $model;
		function __construct()
		{
			$this->view = new View();
		}

		public function pagenotfoundAction()
		{
			$this->view->render('default/404.phtml');
		}
	}
