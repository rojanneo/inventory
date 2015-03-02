<?php
	class View
	{
		function __construct()
		{
			
		}
		
		public function render($name, $data = false, $showHeader = true, $showFooter = true)
		{
                    loadHelper('view');
			if($data)
			extract($data, EXTR_PREFIX_ALL, "view");
			if($showHeader)
			{
				include 'views/default/head.phtml';
				include 'views/default/header.phtml';
			}
			include 'views/'.$name;
			if($showFooter)
			include 'views/default/footer.phtml';
		}

		public function renderAdmin($name, $data = false,$showHead = true, $showHeader = true, $showFooter = true)
		{
			loadHelper('view');
			if($data)
			extract($data);
			if($showHead)
			{
				include 'views/admin/default/head.phtml';
			}
			if($showHeader)
			{
				include 'views/admin/default/header.phtml';
			}
			include 'views/admin/'.$name;
			if($showFooter)
			include 'views/admin/default/footer.phtml';
		}
                
                public function renderWithoutAnything($name, $data = false)
		{
                    loadHelper('view');
			if($data)
				extract($data);
			include 'views/admin/'.$name;
		}
	}
?>
