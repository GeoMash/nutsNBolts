<?php
namespace application\nutsNBolts\controller\rest\contentType
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
					'{int}/{string}'		=>'getByRef',
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
				$this->model->ContentType->read([])
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/contentType/home.json');
		 */
		public function getByRef()
		{
			$contentRef=$this->getFullRequestPart(3);
			if(isset($contentRef))
			{
				$content=$this->model->ContentType->read(['ref'=>$contentRef]);
				if(count($content))
				{
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$content[0]
					);					
				}
				else
				{
					// no page by that Ref
					$this->setResponseCode(404);
					$this->respond
					(
						true,
						'content type not found'
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting content ref to be a string.'
				);
			}
		}	
		/*
		 * sample request: $.getJSON('/rest/contentType/1.json');
		 */
		public function getById()
		{
			$contentId=$this->getFullRequestPart(2);
			if(isset($contentId) && is_numeric($contentId))
			{
				$content=$this->model->ContentType->read(['id'=>$contentId]);
				if(count($content))
				{
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$content[0]
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
						'Content not found'
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting content id to be an integer.'
				);
			}
		}
	}
}
?>