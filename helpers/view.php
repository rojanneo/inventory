<?php

if(!function_exists('convertStatusIdToText'))
{
	function convertStatusIdToText($status_id)
	{
		if($status_id == 0) return 'Disabled';
		else if($status_id == 1) return 'Enabled';
		else return 'Invalid Status ID';
	}
}


if(!function_exists('convertStockIdToText'))
{
	function convertStockIdToText($stock_id)
	{
		if($stock_id == 0) return 'Out of Stock';
		else if($stock_id == 1) return 'In Stock';
		else return 'Invalid Stock ID';
	}
}

if(!function_exists('convertRequiredIdToText'))
{
	function convertRequiredIdToText($id)
	{
		if($id == 0) return 'No';
		else if($id == 1) return 'Yes';
		else return 'Invalid Required ID';
	}
}

if(!function_exists('convertStoreStatusToText'))
{
	function convertStoreStatusToText($id)
	{
		if($id == 0) return 'Not Selling';
		else if($id == 1) return 'Selling';
		else return 'Invalid Status';
	}
}