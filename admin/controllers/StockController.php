<?php
class StockController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		loadHelper('url');
	}

	public function indexAction()
	{
		echo'hye';
		$list=getModel('stock')->stocklist();
	}

}