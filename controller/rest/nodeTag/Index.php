<?php
namespace application\nutsNBolts\controller\rest\nodeTag
{
	use application\nutsNBolts\base\RestController;

	class Index extends RestController
	{
		private $map=array
		(
			''					=>'getAll',
			'{int}'				=>'getById'
		);
		
		/*
		 * sample request: $.getJSON('/rest/node/.json');
		 */
		public function getAll()
		{
			$this->respond
			(
				true,
				'OK',
				$this->model->NodeTag->read([])
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/node/1.json');
		 */
		public function getById()
		{
			$nodeId=$this->getFullRequestPart(2);
			if(isset($nodeId) && is_numeric($nodeId))
			{
				$node=$this->model->NodeTag->read(['node_id'=>$nodeId]);
				if(count($node))
				{
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$node[0]
					);					
				}
				else
				{
					// no Node by that id
					$this->setResponseCode(404);
					$this->respond
					(
						true,
						'Not Found',
						'Node not found'
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting node id to be an integer.'
				);
			}
		}	
	}
}
?>