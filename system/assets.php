<?php
	class Assets
	{
		public $css;
		function __construct()
		{
			$this->css = array();
		}
		
		public function addCss($filename)
		{
			array_push($this->css, $filename);
		}
	}
