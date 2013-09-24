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

		/*
			parameters:
			$id = blog id
			returns the blog by passing in the blog id
		*/
		public function getBlogArticle($id)
		{
			return $record=$this->plugin->Mvc->model->Node->getBlog($id);
		}

		/*
			parameters:
			$id = blog id
			returns the next CREATED blog by the SAME blogger
		*/
		public function getNextBlogArticle($id)
		{
			// get the current blog
			$thisBlog=$this->getBlogArticle($id);
			// extract the blogger id
			$userId=$thisBlog[0]['user_id'];
			// get the date created of the blog (to get the previous blog)
			$thisBlogDate=$thisBlog[0]['date_created'];
			return $record=$this->plugin->Mvc->model->Node->getNextBlogArticle($userId, $thisBlogDate, $id, 'DESC');
		}	

		/*
			parameters:
			$id = blog id
			returns the previous CREATED blog by the SAME blogger
		*/
		public function getPreviousBlogArticle($id)
		{
			// get the current blog
			$thisBlog=$this->getBlogArticle($id);
			// extract the blogger id
			$userId=$thisBlog[0]['user_id'];			
			// get the date created of the blog (to get the next blog)
			$thisBlogDate=$thisBlog[0]['date_created'];
			return $record=$this->plugin->Mvc->model->Node->getNextBlogArticle($userId, $thisBlogDate, $id, 'ASC');	
		}

		/*
			parameters:
			$id = blog id
			returns the blogger details by passing in the blog id
		*/
		public function getBlogger($id)
		{
			$blog=$this->getBlogArticle($id);
			$bloggerId=$blog[0]['user_id'];
			return $this->plugin->Mvc->model->Node->getBlogger($id);
		}

		/*
			parameters:
			$id = blogger id
			returns all of the blogs associated to the specific blogger
		*/
		public function getBlogsByBlogger($bloggerId, $category, $min, $max)
		{
			return $this->plugin->Mvc->model->Node->getBlogsByBlogger($bloggerId, $category, $min, $max);
		}

		/*
			parameters:
			$id = blogger id
			returns all of the blog categories of the blogger
		*/
		public function getBloggerCategories($id)
		{
			return $this->plugin->Mvc->model->Node->getBloggerCategories($id);
		}

		/*
			parameters:
			$id = blogger id
			$limit = number of recent items
			returns a number of recent blog entries by the blogger
		*/
		public function getRecent($id, $limit)
		{
			return $this->plugin->Mvc->model->Node->getRecent($id, $limit);
		}		

		/*
			parameters:
			$id = blogger id		
			returns an array full of dates of blog entries
		*/
		public function getAllDates($id)
		{
			return $this->plugin->Mvc->model->Node->getAllDates($id);
		}
	}
}