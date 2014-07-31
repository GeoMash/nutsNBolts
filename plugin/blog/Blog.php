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
			// $blog=$this->getBlogArticle($id);
			// $bloggerId=$blog[0]['user_id'];
			return $this->plugin->Mvc->model->Node->getBlogger($id);
		}

		/*
			parameters:
			$id = blogger id
			$category = the blog category, can pass in any value
			$min = if set, the minimum date range
			$max = if set, the maximum date range
			returns all of the blogs associated to the specific blogger
		*/
		public function getBlogsByBlogger($bloggerId, $category, $min, $max)
		{
			return $this->plugin->Mvc->model->Node->getBlogsByBlogger($bloggerId, $category, $min, $max);
		}

		/*
			parameters:
			$id = blogger id
			returns the total number of blogs by the blogger
		*/
		public function countAllBlogs($id)
		{
			return count($this->plugin->Mvc->model->Node->countAllBlogs($id));
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
		
		public function getAllBlogs()
		{
			return $this->plugin->Mvc->model->Node->getAllBlogs();
		}
		
		public function paginateBlogs($contentTypeId,$offset,$limit)
		{
//			$blogs=$this->plugin->Mvc->model->Node->getWithParts
//			(
//				[
//					'content_type_id'			=>$contentTypeId
//				],
//				$page,
//				$limit
//			);
			$blogIdArray=[];
			$blogs=[];
			$blogIds=$this->plugin->Mvc->model->Node->read
			(
				['content_type_id'	=>$contentTypeId],
				['id'],
				 " LIMIT $offset,$limit"
			);
			
			if(count($blogIds))
			{
				for($i=0,$j=count($blogIds);$i<$j;$i++)
				{
					$blogIdArray[]=$blogIds[$i]['id'];
				}
				
				$blogs=array_reverse($this->plugin->Mvc->model->Node->getWithParts
				(
					['id'=>$blogIdArray]
				));
			}
			return $blogs;
		}
		
		public function blogsCount($contentTypeId)
		{
			$count=0;
			$query=<<<SQL
SELECT COUNT(id) AS total FROM node WHERE content_type_id={$contentTypeId} AND status=2
SQL;

			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$count=$this->plugin->Db->nutsnbolts->result('assoc')[0]['total'];
			}
			return $count;
		}
	}
}