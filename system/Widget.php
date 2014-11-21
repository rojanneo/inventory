<?php
	
	require_once('system/connection.php');
	require_once 'system/view.php';
	class Widget
	{

	private static $instance = NULL;
	public static $connection;
	protected static $view;
		

	private function __construct()
	{
		$this->connection = Connection::GetInstance();
		$this->view = new View();
	}

	public function GetInstance()
	{

		if(!self::$instance)
		{
			
			self::$instance = new Widget();
		}
		return self::$instance;
	}

	public function getWidgetFromIdentifier($identifier)
	{
		$sql = "SELECT * FROM widgets WHERE widget_identifier = '$identifier'";
		return self::GetInstance()->connection->Query($sql)[0];
	}

	public function getTitle($identifier)
	{
		$widget = self::getWidgetFromIdentifier($identifier);
		return $widget['widget_title'];
	}

	public function render($identifier)
	{
		$widget = self::getWidgetFromIdentifier($identifier);
		//$data['content'] = $widget['widget_content'];
		$value= get_content($widget['widget_content']);
		$data['content'] = $value;
		self::GetInstance()->view->render('widgets/block.phtml',$data, false, false, false);
	}



}
?>