<?php
class DailyfeedsController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function indexAction()
	{
		getModel('dailyfeed')->deductDailyFeeds();
	}
}