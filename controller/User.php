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
			if($request['user_id'])
			{
				$fieldValue=preg_replace('/[^0-9]/', '',$request['user_id']);
				$fieldName='phone';
				$params=array($fieldName=>$fieldValue);
				$user=$this->plugin->UserAuth->authenticate($params,$request['password']);
				if($user)
				{
					$dt = new DateTime();
					Nutshell::getInstance()->plugin->Session->email=$user['email'];
					Nutshell::getInstance()->plugin->Session->phone=$user['phone'];
					Nutshell::getInstance()->plugin->Session->userId=$user['id'];
					Nutshell::getInstance()->plugin->Session->authenticated=true;
					
					$this->plugin->Mvc->model->User->update(array('date_lastlogin'=> $dt->format('Y-m-d H:i:s')),array('phone'=>$user['phone']));
					$this->filterRedirect($user['roles'][0]['name']);
				}
				else
				{
					$this->plugin->Notification->setError("Username and/or password error");
					header('location:/login');
					exit();					
				}
			}
		}
		
		public function logout()
		{
			unset($this->plugin->Session->authenticated);
			unset($this->plugin->Session->userId);
			
			header('location:/');
			exit();			
		}
		public function filterRedirect($role)
		{
			if(isset($role))
			{
				switch (ucFirst($role))
				{
					case 'Super':
							header('location:/admin');
							exit();
						break;

					case 'User':
							header('location:/dashboard');
							exit();
						break;
						
					case 'Bartender':
							header('location:/bartender');
							exit();
						break;

					case 'Manager':
							header('location:/manager');
							exit();
						break;

					case 'Admin':
							header('location:/admin');
							exit();
						break;

					default:
							header('location:/login');
							exit();
						break;
				}
			}
		}		
			
	}
}
?>