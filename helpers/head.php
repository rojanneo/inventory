<?php

if(!function_exists('addCss'))
{
	function addCss($filename)
	{
		echo '<link rel="stylesheet" type="text/css" href="'.ASSET_URL.'css/'.$filename.'">';
	}
}
if(!function_exists('addJS'))
{
	function addJS($filename)
	{
		echo '<script src="'.ASSET_URL.'js/'.$filename.'"></script>';
	}
}