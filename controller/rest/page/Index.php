<?php
namespace application\nutsNBolts\controller\rest\page
{
	use application\nutsNBolts\base\RestController;

	class Index extends RestController
	{
		public function init()
		{
			$this->bindPaths
			(
				array
				(
					''						=>'getAll',
					'{string}'				=>'getByRef',
					'{int}'					=>'getById',
				)
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/page/.json');
		 */
		public function getAll()
		{
			$this->respond
			(
				true,
				'OK',
				$this->model->Page->read([])
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/page/home.json');
		 */
		public function getByRef()
		{
			$pageRef=$this->getFullRequestPart(2);
			$pageRef= '/'.$pageRef.'/';
			if(isset($pageRef))
			{
				$page=$this->model->Page->read(['url'=>$pageRef]);
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
					// no page by that Ref
					$this->setResponseCode(404);
					$this->respond
					(
						true,
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
					'Expecting page ref to be a string.'
				);
			}
		}	
		/*
		 * sample request: $.getJSON('/rest/page/1.json');
		 */
		public function getById()
		{
			$pageId=$this->getFullRequestPart(2);
			if(isset($pageId) && is_numeric($pageId))
			{
				$page=$this->model->Page->read(['id'=>$pageId]);
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
					// no page by that id
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