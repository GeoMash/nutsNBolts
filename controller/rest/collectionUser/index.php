<?php
namespace application\nutsNBolts\controller\rest\collectionUser
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
		 * sample request: $.getJSON('/rest/CollectionUser/.json');
		 */
		public function getAll()
		{
			$this->respond
			(
				true,
				'OK',
				$this->model->CollectionUser->read([])
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/CollectionUser/1.json');
		 */
		public function getById()
		{
			$collectionId=$this->getFullRequestPart(2);
			if(isset($collectionId) && is_numeric($collectionId))
			{
				$collection=$this->model->CollectionUser->read(['id'=>$collectionId]);
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
					// Collection user not found
					$this->setResponseCode(404);
					$this->respond
					(
						true,
						'Not Found',
						'Collection user not found'
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting collection user id to be an integer.'
				);
			}
		}	
	}
}
?>