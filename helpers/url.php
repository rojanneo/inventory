<?php

if(!function_exists('redirect'))
{
	function redirect($urlpath = '')
	{
		header("Location: ".URL.$urlpath);
	}
}


if(!function_exists('CurrentURL'))
{
	function CurrentURL() {
	return 'http://'.SERVER_NAME.$_SERVER['REQUEST_URI'];
	}
}