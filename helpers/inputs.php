<?php
if(!function_exists('getPost'))
{
	function getPost($key = null)
	{
		if($key)
			if(isset($_POST[$key]))
			$post = $_POST[$key];
			else
			$post = false;
		else
			$post = $_POST;
		return $post;
	}
}

if(!function_exists('getParam'))
{
	function getParam($key = null)
	{
		if($key)
			if(isset($_GET[$key]))
			$get = $_GET[$key];
			else
			$get = false;
		else
			$get = $_GET;
		return $get;
	}
}

if(!function_exists('getFiles'))
{
	function getFiles($key)
	{
		if($key)
			if(isset($_FILES[$key]))
			$get = $_FILES[$key];
			else
			$get = false;
		return $get;
	}
}