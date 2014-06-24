<?php
namespace application\nutsNBolts\controller\rest
{
	use application\plugin\rest\RestController;
	use nutshell\Nutshell;
	use nutshell\plugin\session; 
	use \DateTime;

	class Login extends RestController
	{
		private $map=array
		(
			'{int}'						=>'checkById',
			'loginByEmail'				=>'loginByEmail'
		);
		
		/*
		 * sample request: $.getJSON('/rest/login/1.json');
		 */
		public function checkById()
		{
			$userId=$this->getFullRequestPart(2);
			if(isset($userId) && is_numeric($userId))
			{

				if(Nutshell::getInstance()->plugin->Session->authenticated)
				{
					// logged in
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
		
		/*
		 * sample request: $.getJSON('/rest/login/loginByEmail/xxx/***.json');
		 */
		public function loginByEmail()
		{
			
			$user=$this->plugin->UserAuth->authenticate
			(
				[
					'email'=>$this->request->get('email')
				],
					$this->request->get('password')
			);
			if ($user)
			{
				$dateTime=new DateTime();
				if (isset($this->request->get['email']))
				{
					$this->plugin->Mvc->model->User->update(array('date_lastlogin'=> $dateTime->format('Y-m-d H:i:s')),array('email'=>$user['email']));
				}
				else if (isset($this->request->get['phone']))
				{
					$this->plugin->Mvc->model->User->update(array('date_lastlogin'=> $dateTime->format('Y-m-d H:i:s')),array('phone'=>$user['phone']));
				}
				$session=Nutshell::getInstance()->plugin->Session;
				$session->email=$user['email'];
				$session->phone=$user['phone'];
				$session->userId=$user['id'];
				$session->authenticated=true;
				
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
				$this->setResponseCode(200);
				$this->respond
				(
					false,
					'OK',
					false
				);
			}
		}
	}
}
?>