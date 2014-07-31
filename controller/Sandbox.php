<?php
namespace application\nutsNBolts\controller
{
	use nutshell\plugin\mvc\Controller;
	use application\nutsNBolts\plugin\search\Search;

	class Sandbox extends Controller
	{
		public function index()
		{
//			$search=$this->plugin->Search('Announcements');
//			$search->clearCache();
//			$search->setContentType(2);
//			$search->addFilter
//			(
//				[
////					'id'=>'>10'
//					'body'=>'LIKE "%Par Value%"'
//				]
//			);
			
			$search=$this->plugin->Search('EFTI_PLAYLIST')
					->joinWithContentType('EFTI_PLAYLIST_ITEM','playlist')
					->clearCache()
					->limit(100)
					->offset(0)
//					->orderBy('id',Search::ORDER_ASCENDING)
					->orderBy
					(
						[
							'id'	=>Search::ORDER_DECENDING,
							'title'	=>Search::ORDER_DECENDING
						]
					);
			
			$results=$search->execute();
			
			var_dump($results);
		}
	}
}
?>