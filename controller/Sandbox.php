<?php
namespace application\nutsNBolts\controller
{
	use nutshell\plugin\mvc\Controller;

	class Sandbox extends Controller
	{
		public function index()
		{
			$search=$this->plugin->Search('Announcements');
			$search->clearCache();
			$search->setContentType(2);
			$search->addFilter
			(
				[
//					'id'=>'>10'
					'body'=>'LIKE "%Par Value%"'
				]
			);
			$results=$search->execute();
			
			var_dump($results);
		}
	}
}
?>