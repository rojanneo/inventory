<?php
class DailyfeedsController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		loadHelper('url');
	}

	public function indexAction()
	{
		//getModel('dailyfeed')->deductDailyFeeds();

		$feeding_groups = getModel('attribute')->load(array('attribute_code'=>'rabbit_feeding_group'));
		$feeding_groups = getModel('attribute')->getOptions($feeding_groups['attribute_id']);
		//echo '<pre>';
		//var_dump($feeding_groups);

		$dailyfeeds = getModel('dailyfeed')->getCollection();

		if($dailyfeeds)
		{
			$data['dailyfeeds'] = $dailyfeeds;
			$data['feeding_groups'] = $feeding_groups;
			$this->view->renderAdmin('dailyfeeds/table.phtml',$data);
		}
		else
		{

			$data['feeding_groups'] = $feeding_groups;

			$this->view->renderAdmin('dailyfeeds/table.phtml',$data);
		}
	}

	public function updateDailyFeedAction()
	{
		loadHelper('inputs');
		$post_data = getPost();
		if(isset($post_data['update']) and $post_data['update'] == 'update')
		{
			extract($post_data);
			foreach($feeding_group as $key => $feeding_group)
			{
				$group = array('daily_feed_id'=>$daily_feed_id[$key],'feeding_group_id'=>$feeding_group, '06_07kg'=>$weight_1[$key], '1_2kg'=>$weight_2[$key], '2_3kg'=>$weight_3[$key], '3_4kg'=>$weight_4[$key], '4_5kg'=>$weight_5[$key], '5_kg'=>$weight_6[$key]);
				$feed_id = getModel('dailyfeed')->update($group);
				var_dump($group);
				
			}
		}
		else
		{
			extract($post_data);
			foreach($feeding_group as $key => $feeding_group)
			{
				$group = array('feeding_group_id'=>$feeding_group, '06_07kg'=>$weight_1[$key], '1_2kg'=>$weight_2[$key], '2_3kg'=>$weight_3[$key], '3_4kg'=>$weight_4[$key], '4_5kg'=>$weight_5[$key], '5_kg'=>$weight_6[$key]);
				$feed_id = getModel('dailyfeed')->insert($group);
			}
		}

		redirect('admin/dailyfeeds');
	}

}