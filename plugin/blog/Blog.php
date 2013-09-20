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

		public function getNextBlogArticle($blogId)
		{
			$thisBlog=$this->getBlogArticle($blogId);
			$userId=$thisBlog[0]['user_id'];
			$thisBlogDate=$thisBlog[0]['date_created']->getTimestamp();
			return $record=$this->plugin->Mvc->model->Node->getNextBlogArticle($userId, $thisBlogDate, $blogId, 'DESC');
		}	

		public function getPreviousBlogArticle($id)
		{
			$thisBlog=$this->getBlogArticle($blogId,$userId);
			$userId=$thisBlog[0]['user_id'];			
			$thisBlogDate=$thisBlog[0]['date_created']->getTimestamp();
			return $record=$this->plugin->Mvc->model->Node->getNextBlogArticle($userId, $thisBlogDate, $blogId, 'ASC');	
		}

		public function getBlogger($id)
		{
			$blog=$this->getBlogArticle($id);
			$bloggerId=$blog[0]['user_id'];
			return $this->plugin->Mvc->model->Node->getBlogger($id);
		}
	}
}