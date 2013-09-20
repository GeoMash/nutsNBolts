<?php
namespace application\nutsNBolts\plugin\blog
{
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;
	class Blog extends Plugin implements Singleton
	{
		public function init()
		{

		}

		public function getBlogArticle($id)
		{
			return $record=$this->plugin->Mvc->model->Node->getBlog($id);
		}

		public function getNextBlogArticle($id)
		{
			
		}	

		public function getPreviousBlogArticle($id)
		{
			
		}

		public function getBlogger($id)
		{

		}
	}
}