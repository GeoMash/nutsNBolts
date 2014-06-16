<?php
namespace application\nutsNBolts\controller\rest
{
	use application\plugin\rest\RestController;
	use nutshell\Nutshell;
	use nutshell\plugin\session; 

	class User extends RestController
	{
		private $map=array
		(
			'edit'						=>'edit',
		);
		
		public function edit()
		{
			$userId=$this->plugin->Session->userId;
			if(isset($userId) && is_numeric($userId))
			{
				if($this->plugin->Session->authenticated)
				{
					// logged in
					$key		=$this->request->get('key');
					$value		=$this->request->get('value');
					
					$this->model->User->update
					(
						[
							$key	=>$value
						],
						[
							'id'	=>$userId
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
					// logged out
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						'asfasf'
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