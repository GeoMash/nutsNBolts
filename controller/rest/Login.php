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
			'loginByEmail'				=>'loginByEmail',
			'forgotPassword'			=>'forgotPassword'
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
						false
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
					false
				);
			}
		}
		
		public function forgotPassword()
		{
			$emailAddress		=$this->request->get('email');
			$user				=$this->plugin->Mvc->model->User->read(['email'=>$emailAddress]);
			if($user[0])
			{
				// user found
				$hash=$_SERVER['HTTP_HOST'].'/forgot/'.sha1($user[0]['name_first'].$user[0]['name_last']);
				// prep email
				$emailBody=<<<EMAIL
Hi,

You have requested for a password change<br/>

To reset your password please click on the link below:</br/>

<a href="{$hash}">CLICK HERE TO RESET</a><br/><br/>

Regards,<br/>
 - EFTI Memberships
EMAIL;
				$email=$this->plugin->Email->smtp;
				$email->From='membership@efti.com';
				$email->Subject='EFTI Forgot Password';
				$email->AltBody=$emailBody;
				$email->MsgHTML($emailBody);
				$email->AddAddress($emailAddress);
				$email->send();
				
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
				// error, no user found
				$this->setResponseCode(200);
			
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