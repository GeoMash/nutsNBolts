<?php
namespace application\nutsNBolts\controller\rest
{
	use application\plugin\rest\RestController;
	use nutshell\Nutshell;
	use nutshell\plugin\session; 
	use \DateTime;

	class Comment extends RestController
	{
		private $map=array
		(
			'saveComment'				=>'comment'
		);

		public function comment()
		{
			$userId		=$this->plugin->Session->userId;
			$comment	=$this->request->get('comment');
			$nodeId		=$this->request->get('nodeId');
			
			$record=
			[
				'node_id'		=>$nodeId,
				'user_id'		=>$userId,
				'name'			=>'',
				'body'			=>$comment
			];
			
			if($thisComment=$this->model->NodeComment->insertAssoc($record))
			{
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					$comments=$this->plugin->Comment->getCommentsWithUserDetails($nodeId,50)
				);
			}
			else
			{
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					[
						$userId,
						$comment,
						$nodeId
					]
				);
			}
		}	
	}
}
?>