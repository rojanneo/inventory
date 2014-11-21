<?php
			require_once 'config/sqlDefinitions.php';
			
//			$db = mysql_connect(MYSQL_Server, MYSQL_User,MYSQL_Password) or die(mysql_error($db));
//			mysql_select_db(DATABASE, $db) or die(mysql_error($db));

			class Connection
			{
				private static $instance = NULL;
				public static $db = NULL;
				

				private function __construct()
				{
					
					
					self::$db = mysql_connect(MYSQL_Server, MYSQL_User, MYSQL_Password) or die(mysql_error(self::$instance));
					
					//mysql_query("CREATE DATABASE IF NOT EXISTS movies_db", self::$db);
					
					mysql_select_db(DATABASE);
				}
				
				// public function CreateDatabase()
				// {
						
					
					
				// 	mysql_query("INSERT INTO usertypes VALUES('','standard')");
				// 	mysql_query("INSERT INTO usertypes VALUES('', 'admin')");
					
				// 	mysql_query("INSERT INTO languages VALUES('','English')");

				// 	mysql_query("INSERT INTO genres VALUES('','Action')");
				// 	mysql_query("INSERT INTO genres VALUES('','Sci Fi')");
					
				// 	mysql_query("INSERT INTO users VALUES('rojan_neo', 'Rojan', 'Shrestha', '1992-02-19', 'Description', '123', 'rojan_neo@hotmail.com',2)");

					
				// }
				
				
				public static function GetInstance()
				{
					if(!self::$instance)
					{

						self::$instance = new Connection();
					}
					return self::$instance;
				}
				
				public function Query($query)
				{
					try{
						
					$result = mysql_query($query,self::$db);
					
					$results = array();
					
					while($row = mysql_fetch_assoc($result))
					{
						$results[] = $row;
					}
					return $results;
					}
					catch(Exception $e)
					{
						if(class_exists('Session'))
						Session::addErrorMessage($e->getMessage());
						else
						AdminSession::addErrorMessage($e->getMessage());

					}
					
				
				}
				
				public function InsertQuery($query)
				{
					try
					{
						$result = mysql_query($query, self::$db) or die(mysql_error(self::$db));				
						return true;
					}
					catch(Exception $e)
					{
						if(class_exists('Session'))
						Session::addErrorMessage($e->getMessage());
						else
						{
						AdminSession::addErrorMessage($e->getMessage());
						}
					}
				}

				public function UpdateQuery($query)
				{
					try
					{
						$result = mysql_query($query, self::$db) or die(mysql_error(self::$db));					
						return true;
					}
					catch(Exception $e)
					{
						if(class_exists('Session'))
						Session::addErrorMessage($e->getMessage());
						else
						AdminSession::addErrorMessage($e->getMessage());
					}
				}
				
				public function CreateQuery($query)
				{
					mysql_query($query, self::$db);
					return true;
				}
				
				public function DeleteQuery($query)
				{
					mysql_query($query, self::$db) or die(mysql_error(self::$db));
					return true;
				}
				
				public static function GetRowCount($query)
				{
					$result = mysql_query($query);
					$rows = mysql_num_rows($result);
					return $rows;
				}
				
				public static function GetRecord($result)
				{
					$row = mysql_fetch_row($result);
					return $row;
				}
				public static function GetInsertID()
				{
					return mysql_insert_id();
				}
			}

?>