<?php
	//require_once 'config/config.php';

	
	require_once 'system/controller.php';
	require_once 'system/model.php';
	require_once 'helpers/Autoload.php';
	require_once 'system/widget.php';
	foreach (glob("config/*.php") as $configs)
	{
		include $configs;
	}
	
	
	foreach (glob("controllers/*.php") as $controllers)
	{
		include $controllers;
	}

	foreach (glob("admin/controllers/*.php") as $adminControllers)
	{
		include $adminControllers;
	}
	

	session_start();
	//var_dump(isset($_GET['url']) ?$_GET['url']:null);die;
	$default = DEFAULT_CONTROLLER.'/'.DEFAULT_ACTION;
	$url = isset($_GET['url']) ? $_GET['url'] : $default;
	$url = rtrim($url, '/');
	$url = explode('/', $url);
	if($url[0] == 'admin')
	{
			require_once 'system/adminsession.php';
			if(isset($url[3]))
			{
				$control = $url[1].'AdminController';
				if(!class_exists($control))
				{
				include('views/default/404.phtml');
				}
				else
				{
					$controller = new $control();
					$function = $url[2].'Action';
					if(!method_exists($controller, $function))
					{
						include('views/default/404.phtml');
					}
					else
					{
						$controller->$function($url[3]);
					}
				}
				
			}
			else
			{

				if(isset($url[2]))
				{
					$control = $url[1].'AdminController';
					//echo $control;die;
					if(!class_exists($control))
					{
					include('views/default/404.phtml');
					}
					else
					{
						$controller = new $control();
						$function = $url[2].'Action';
						if(!method_exists($controller, $function))
						{
							include('views/default/404.phtml');
						}
						else
						{
							$controller->$function();
						}
					}
					
				}
				else 
				{
				
					if(isset($url[1]))
					{
						$control = $url[1].'AdminController';
						if(!class_exists($control))
						{
							include('views/default/404.phtml');
						}
						else
						{
							$controller = new $control();
							$default_act = 'indexAction';
							if(!method_exists($controller, $default_act))
							{
								include('views/default/404.phtml');
							}
							else
							{
								$controller->$default_act();
							}
						}
						
					}
					else
					{

						$default_cont = DEFAULT_ADMIN_CONTROLLER.'AdminController';
						$controller = new $default_cont();
						$default_act = DEFAULT_ADMIN_ACTION.'Action';
						$controller->$default_act();
					}
				}
				
			}
	}
	else
	{
		require_once 'system/session.php';
		if(isset($url[2]))
		{
			$control = $url[0].'Controller';
			if(!class_exists($control))
			{
			include('views/default/404.phtml');
			}
			else
			{
				$controller = new $control();
				$function = $url[1].'Action';
				if(!method_exists($controller, $function))
				{
					include('views/default/404.phtml');
				}
				else
				{
					$controller->$function($url[2]);
				}
			}
			
		}
		else
		{
			if(isset($url[1]))
			{
				$control = $url[0].'Controller';
				if(!class_exists($control))
				{
				include('views/default/404.phtml');
				}
				else
				{
					$controller = new $control();
					$function = $url[1].'Action';
					if(!method_exists($controller, $function))
					{
						include('views/default/404.phtml');
					}
					else
					{
						$controller->$function();
					}
				}
				
			}
			else 
			{
			
				if(isset($url[0]))
				{
					$control = $url[0].'Controller';
					if(!class_exists($control))
					{
						include('views/default/404.phtml');
					}
					else
					{
						$controller = new $control();
						$default_act = 'indexAction';
						if(!method_exists($controller, $default_act))
						{
							include('views/default/404.phtml');
						}
						else
						{
							$controller->$default_act();
						}
					}
					
				}
				else
				{
					$default_cont = DEFAULT_CONTORLLER.'Controller';
					$controller = new default_cont();
					$default_act = DEFAULT_ACTION.'Action';
					$controller->$default_act();
				}
			}
			
		}
	}

	

?>
