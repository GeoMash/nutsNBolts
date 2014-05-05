<?php
namespace application\nutsNBolts\controller\rest\pageType
{
	use application\plugin\rest\RestController;

	class Index extends RestController
	{
		private $map=array
		(
			''						=>'getAll',
			'{string}'				=>'getByRef',
			'{int}'					=>'getById'
		);
		
		/*
		 * sample request: $.getJSON('/rest/pageType/.json');
		 */
		public function getAll()
		{
			$this->respond
			(
				true,
				'OK',
				$this->model->PageType->read([])
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/pageType/home.json');
		 */
		public function getByRef()
		{
			$pageRef=$this->getFullRequestPart(2);
			if(isset($pageRef))
			{
				$page=$this->model->PageType->read(['ref'=>$pageRef]);
				if(count($page))
				{
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$page[0]
					);					
				}
				else
				{
					// no page type by that Ref
					$this->setResponseCode(404);
					$this->respond
					(
						true,
						'***Page not found'
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting page ref to be a string.'
				);
			}
		}	
		/*
		 * sample request: $.getJSON('/rest/pageType/1.json');
		 */
		public function getById()
		{
			$pageId=$this->getFullRequestPart(2);
			if(isset($pageId) && is_numeric($pageId))
			{
				$page=$this->model->PageType->read(['id'=>$pageId]);
				if(count($page))
				{
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$page[0]
					);					
				}
				else
				{
					// no page type by that id
					$this->setResponseCode(404);
					$this->respond
					(
						true,
						'Not Found',
						'Page not found'
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting page id to be an integer.'
				);
			}
		}
	}
}
?>