<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use nutshell\helper\ArrayHelper;
	use nutshell\core\exception\NutshellException;
	// use application\nutsNBolts\controller\admin\ConfigureContent;
	// use application\nutsNBolts\controller\admin\Content;
	// use application\nutsNBolts\controller\admin\Dashboard;
	
	class Index extends AdminController
	{
		const USER_STATUS_DISABLED	=0;
		const USER_STATUS_ENABLED	=1;
		
		private $routedController	=null;
		
		public function index()
		{
			$this->route();
		}
		
		private function route()
		{
			$control=$this->request->node(1);
			if (!$this->isAuthenticated() && $control!='login')
			{
				//TODO: Return address.
				$this->plugin->Session->returnURL='/'.implode('/',$this->request->getNodes());
				$this->redirect('/admin/login/');
			}
			elseif ($this->isAuthenticated() && (int)$this->getUser()['status']===self::USER_STATUS_DISABLED)
			{
				$control='logout';
			}
			
			$this->MVC=$this->plugin->Mvc;
			switch ($control)
			{
				case 'script':
				{
					$this->routedController=new Script($this->MVC);
					break;
				}
				case 'template':
				{
					$this->template();
					return;
				}
				case 'content':
				{
					$this->routedController=new Content($this->MVC);
					break;
				}
				case 'configurePages':
				case 'configurepages':
				{
					$this->routedController=new ConfigurePages($this->MVC);
					break;
				}
				case 'configureContent':
				case 'configurecontent':
				{
					$this->routedController=new ConfigureContent($this->MVC);
					break;
				}
				case 'fileManager':
				case 'filemanager':
				{
					$this->routedController=new FileManager($this->MVC);
					break;
				}
				case 'settings':
				{
					$this->routedController=new Settings($this->MVC);
					break;
				}
				case '':
				case 'dashboard':
				{
					$this->routedController=new Dashboard($this->MVC);
					break;
				}
				case 'login':
				{
					$this->handleLogin();
					$this->view->render();
					exit();
				}
				case 'logout':
				{
					$this->logout();
					$this->view->render();
					exit();
				}
				case 'profile':
				{	
					$this->routedController=new Profile($this->MVC);
					break;
				}		
				case 'messages':
				{	
					$this->routedController=new Messages($this->MVC);
					break;
				}								
				default:
				{
					$this->view->render();
					exit();
				}
			}
			//Check for action.
			$action	=$this->request->node(2);
			$args	=array();
			$node	=3;
			//Check for args.
			if (!is_null($action))
			{
				while (true)
				{
					//grab the next node
					$arg = $this->request->node($node++);
					
					if(is_null($arg))
					{
						break;
					}
					//append to the args array
					$args[] = $arg;
				}
			}
			else
			{
				$action='index';
			}
			call_user_func_array
			(
				array($this->routedController,$action),
				$args
			);
		}
		
		public function template()
		{
			$nodes=$this->request->getNodes();
			ArrayHelper::without
			(
				$nodes,
				array
				(
					$this->request->node(0),
					$this->request->node(1)
				)
			);
			$templatePath	='admin/'.implode('/',$nodes);
			// $template		=$this->plugin->Template();
			// $template->setTemplate($this->view->buildViewPath($templatePath));
			
			switch ($templatePath)
			{
				case 'admin/configureContent/addWidgetSelection':
				{
					$html=$this->buildWidgetHTML
					(
						$this->application->NutsNBolts->getWidgetList(),
						$this->request->get('index')
					);
					break;
				}
				case 'admin/fileManager/collections':
				{
					$template=$this->plugin->Template();
					$template->setTemplate($this->view->buildViewPath('admin/fileManager/collections'));

					$template->setKeyVal('collections',$this->plugin->Collection->getCollections());
					$html=$template->compile();
					break;
				}
				case 'admin/fileManager/files':
				{
					$template=$this->plugin->Template();
					$template->setTemplate($this->view->buildViewPath('admin/fileManager/files'));
					
					$collection=$this->model->Collection->read($this->request->get('id'))[0];
					$template->setKeyVal('collectionName',$collection['name']);
					
					try
					{
						$fileList=$this->plugin->FileSystem->getFileListFromCollection($this->request->get('id'));
						$template->setKeyVal('files',$fileList);
					}
					catch (NutshellException $exception)
					{
						$template->setKeyVal('files',array());
					}
					

					
					$html=$template->compile();
					break;
				}
			}
			$this->plugin	->Responder('html')
							->setData($html)
							->send();
		}
		
		public function handleLogin()
		{
			if (!$this->request->get('username'))
			{
				$this->view->setTemplate('login');
			}
			else
			{
				$result=$this->model->User->authenticate
				(
					$this->request->get('username'),
					$this->request->get('password')
				);
				if ($result)
				{
					$this->plugin->Session->authenticated=true;
					$this->plugin->Session->userId=$result['id'];
					if (!empty($this->plugin->Session->returnURL))
					{
						$this->redirect($this->plugin->Session->returnURL);
					}
					else
					{
						$this->redirect('/admin/dashboard');
					}
				}
				else
				{
					$this->view->setTemplate('login');
				}
			}
		}
		
		public function logout()
		{
			unset($this->plugin->Session->authenticated);
			unset($this->plugin->Session->userId);
			$this->redirect('/admin/login/');
		}
	}
}
?>