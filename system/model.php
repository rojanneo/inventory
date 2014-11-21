<?php
	
	require_once 'connection.php';
	class Model
	{
		public $connection;
		
		public  function __construct()
		{
			$this->connection = Connection::GetInstance();
			
		}
	}
?>