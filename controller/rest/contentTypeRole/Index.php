<?php
namespace application\nutsNBolts\controller\rest\contentTypeRole
{
	use application\nutsNBolts\base\RestController;

	class Index extends RestController
	{
		private $map=array
		(
			'{int}'				=>'getByContentId',
		);
		
		/*
		 * sample request: $.getJSON('/rest/contentType/1.json');
		 */
		public function getByContentId()
		{
			$contentTypeId=$this->getFullRequestPart(2);
			if(isset($contentTypeId) && is_numeric($contentTypeId))
			{
				$content=$this->model->ContentTypeRole->read(['content_type_id'=>$contentTypeId]);
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
					// no content by that id
					$this->setResponseCode(404);
					$this->respond
					(
						true,
						'Not Found',
						'Content type role not found'
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting content type id to be an integer.'
				);
			}
		}
	}
}
?>