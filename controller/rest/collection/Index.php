<?php
namespace application\nutsNBolts\controller\rest\collection
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
					''					=>'getAll',
					'{int}'				=>'getById'
				)
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/collection/.json');
		 */
		public function getAll()
		{
			$this->respond
			(
				true,
				'OK',
				$this->model->Collection->read([])
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/collection/1.json');
		 */
		public function getById()
		{
			$collectionId=$this->getFullRequestPart(2);
			if(isset($collectionId) && is_numeric($collectionId))
			{
				$collection=$this->model->Collection->read(['id'=>$collectionId]);
				if(count($collection))
				{
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$collection[0]
					);					
				}
				else
				{
					// no collection by that id
					$this->setResponseCode(404);
					$this->respond
					(
						true,
						'Not Found',
						'Collection not found'
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting collection id to be an integer.'
				);
			}
		}	
	}
}
?>