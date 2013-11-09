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

                    if(strlen($user['email']) > 3)
                    {
                        $dt = new DateTime();
                        Nutshell::getInstance()->plugin->Session->email=$user['email'];
                        Nutshell::getInstance()->plugin->Session->phone=$user['phone'];
                        Nutshell::getInstance()->plugin->Session->userId=$user['id'];
                        Nutshell::getInstance()->plugin->Session->authenticated=true;
                        $this->plugin->Mvc->model->User->update(array('date_lastlogin'=> $dt->format('Y-m-d H:i:s')),array('phone'=>$user['phone']));
                        $this->filterRedirect($user['roles'][0]['ref']);
                    }
                    else
                    {
                        // user needs to fill in form
                        Nutshell::getInstance()->plugin->Session->phone=$user['phone'];
                        Nutshell::getInstance()->plugin->Session->authenticated=false;
                        Nutshell::getInstance()->plugin->Session->password=$user['password'];
                        Nutshell::getInstance()->plugin->Session->salt=$user['salt'];
                        header('location:/login/complete');
                        $this->plugin->Notification->setError("You need to fill in the form below before you can enter the website");
                        exit();

                    }

				}
				else
				{

                    $this->plugin->Notification->setError("Username and/or password error");
                    header('location:/');
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

		
		private function filterRedirect($role)
        {
			if(isset($role))
			{
				switch (strtoupper($role))
				{
					case 'SUPER':
							header('location:/admin');
							exit();
						break;

					case 'STANDARD':
							header('location:/dashboard');
							exit();
						break;
						
					case 'BARTENDER':
							header('location:/bartender');
							exit();
						break;

					case 'MANAGER':
							header('location:/manager');
							exit();
						break;

					case 'ADMIN':
							header('location:/admin');
							exit();
						break;

					default:
                        die('here');
							header('location:/login');
							exit();
						break;
				}
			}
		}		
			
	}
}
?>