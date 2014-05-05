<?php
namespace application\nutsNBolts\controller
{
	use nutshell\Nutshell;
	use application\nutsNBolts\base\Controller;
	use nutshell\helper\ObjectHelper;
	use \DateTime;
	
	class User extends Controller
	{
		public function index()
		{
			$this->route();
		}

		private function route()
		{
			$control=$this->request->node(1);			
			$this->MVC=$this->plugin->Mvc;
			$request=$this->request->getAll();
			switch ($control)
			{
				case 'login':
				{
					$this->login($request);
				}
				case 'logout':
				{
					$this->logout($request);
				}						
				default:
				{

				}
			}
		}	
					
		public function login($request)
		{
			if (isset($request['email']))
			{
				$query=['email'=>$request['email']];
			}
			else if (isset($request['phone']))
			{
				$query=['phone'=>$request['phone']];
			}
			else
			{
				$this->plugin->Notification->setError("Username and/or password error");
				header('location:/');
				exit();
			}
			$user=$this->plugin->UserAuth->authenticate($query,$request['password']);
			if ($user)
			{
				$dateTime=new DateTime();
				if (isset($request['email']))
				{
					$this->plugin->Mvc->model->User->update(array('date_lastlogin'=> $dateTime->format('Y-m-d H:i:s')),array('email'=>$user['email']));
				}
				else if (isset($request['phone']))
				{
					$this->plugin->Mvc->model->User->update(array('date_lastlogin'=> $dateTime->format('Y-m-d H:i:s')),array('phone'=>$user['phone']));
				}
				$session=Nutshell::getInstance()->plugin->Session;
				$session->email=$user['email'];
				$session->phone=$user['phone'];
				$session->userId=$user['id'];
				$session->authenticated=true;
				
				header('location:/');
				exit();
			}
			else
			{
				$this->plugin->Notification->setError("Username and/or password error");
				header('location:/');
				exit();
			}
		}
		
		public function logout()
		{
			unset($this->plugin->Session->authenticated);
			unset($this->plugin->Session->userId);
			header('location:/');
			exit();
		}
	}
}
?>