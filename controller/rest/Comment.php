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
				$allComments=$this->plugin->Comment->getCommentsWithUserDetails($nodeId,50);
				for($i=0,$j=count($allComments);$i<$j;$i++)
				{
					$allComments[$i]['user']=$this->plugin->Mvc->model->User->read(['id'=>$allComments[$i]['user_id']])[0];
				}
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					$allComments
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