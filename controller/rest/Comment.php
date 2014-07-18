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
			'saveComment'				=>'comment',
			'deleteComment'				=>'delete'
		);

		public function comment()
		{
			$userId		=$this->plugin->Session->userId;
			$comment	=$this->request->get('comment');
			$nodeId		=$this->request->get('nodeId');
//			$userRole	=$this->plugin->Mvc->model->User->getRoles($userId)[0]['ref'];
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
					$allComments[$i]['user']=$this->plugin->Mvc->model->User->read(['id'=>$allComments[$i]['user_id']],['id'])[0];
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
		
		public function delete()
		{
			$commentId			=$this->request->get('comment');
			
			if($this->plugin->Auth->hasRoleByRef(['ADMIN']) && isset($commentId) && is_numeric($commentId))
			{
				$this->plugin->Mvc->model->NodeComment->delete
				(
					[
						'id'		=>$commentId
					]
				);
				
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					true
				);
			}
			else
			{
				$this->setResponseCode(401);
				$this->respond
				(
					false,
					'ERROR',
					false
				);
			}
		}
	}
}
?>