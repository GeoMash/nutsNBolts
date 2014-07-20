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
			'validateSession'			=>'validateSession'
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
				$this->setResponseCode(200);
				$this->respond
				(
					false,
					'OK',
					null
				);
			}
		}
		
		public function validateSession()
		{
			$this->setResponseCode(200);
			$this->respond
			(
				$this->plugin->Auth->isAuthenticated(),
				'OK'
			);
		}
	}
}
?>