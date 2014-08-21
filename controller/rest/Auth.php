<?php
namespace application\nutsNBolts\controller\rest
{
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use application\plugin\rest\RestController;
	use nutshell\plugin\session; 

	class Auth extends RestController
	{
		private $map=array
		(
			'authenticate'				=>'authenticate',
			'validateSession'			=>'validateSession',
			'unauthenticate'			=>'unauthenticate',
			'getUser'					=>'getUser'
		);
		
		public function authenticate()
		{
			$user=null;
			try
			{
				$user=$this->plugin->Auth->authenticate
				(
					$this->request->get('email'),
					$this->request->get('password')
				);
			}
			catch (AuthException $exception)
			{
				$this->setResponseCode(200);
				$this->respond
				(
					false,
					$exception->getMessage(),
					null
				);
			}
			if ($user)
			{
				$data=true;
				if (!empty($this->plugin->Session->returnURL))
				{
					$data=$this->plugin->Session->returnURL;
				}
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					$data
				);
			}
			else
			{
				$this->setResponseCode(401);
				$this->respond
				(
					false,
					'Authentication failed.',
					null
				);
			}
		}
		
		public function unauthenticate()
		{
			$this->plugin->Auth->unauthenticate();
			$this->respond
			(
				true,
				'OK',
				null
			);
		}
		
		public function validateSession()
		{
			$authenticated=$this->plugin->Auth->isAuthenticated();
			if ($authenticated)
			{
				$this->setResponseCode(200);
				$this->respond(true,'OK');
			}
			else
			{
				$this->setResponseCode(401);
				$this->respond(false,'Session No Longer Valid');
			}
		}
		
		public function getUser()
		{
			if ($this->plugin->Auth->isAuthenticated())
			{
				$userId=$this->plugin->Auth->getUserId();
				$user=$this->model->User->read($userId);
				if(isset($user[0]))
				{
					unset($user[0]['password']);
					unset($user[0]['salt']);
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$user[0]
					);
					exit();
				}
			}
			$this->setResponseCode(401);
			$this->respond(false,'Not Authenticated');
		}
	}
}
?>