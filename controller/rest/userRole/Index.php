<?php
namespace application\nutsNBolts\controller\rest\userRole
{
	use application\nutsNBolts\base\RestController;

	class Index extends RestController
	{
		private $map=array
		(
			'{int}'				=>'getByUserId'
		);
		
		public function getByUserId()
		{
			$userId=$this->getFullRequestPart(2);
			if(isset($userId) && is_numeric($userId))
			{
				$user=$this->model->UserRole->read(['user_id'=>$userId]);
				if(count($user))
				{
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$user[0]
					);					
				}
				else
				{
					// user not found
					$this->setResponseCode(404);
					$this->respond
					(
						true,
						'Not Found',
						'User not found'
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting user id to be an integer.'
				);
			}
		}	
	}
}
?>