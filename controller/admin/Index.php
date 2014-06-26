<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
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
			elseif ($this->isAuthenticated()
			&& (!$this->plugin->Auth->isImpersonating() && (int)$this->getUser()['status']===self::USER_STATUS_DISABLED))
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
					if ($this->request->node(2))
					{
						switch (strtolower($this->request->node(2)))
						{
							default:
							{
								$this->routedController=new ConfigureContent($this->MVC);
							}
						}
					}
					else
					{
						$this->routedController=new ConfigureContent($this->MVC);
					}
					break;
				}
				case 'subscriptions':
				{
					switch (strtolower($this->request->node(2)))
					{
						case 'packages':
						{
							$this->routedController=new subscriptions\Packages($this->MVC);
							$this->routeAction(3);
							break;
						}
						case 'subscribers':
						{
							$this->routedController=new subscriptions\Subscribers($this->MVC);
							$this->routeAction(3);
							break;
						}
					}
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
					if ($this->request->node(2))
					{
						switch (strtolower($this->request->node(2)))
						{
							case 'users':
							{
								$this->routedController=new settings\Users($this->MVC);
								$this->routeAction(3);
								break;
							}
							case 'permissions':
							{
								$this->routedController=new settings\Permissions($this->MVC);
								$this->routeAction(3);
								return;
							}
							case 'roles':
							{
								$this->routedController=new settings\Roles($this->MVC);
								$this->routeAction(3);
								return;
							}
							case 'policies':
							{
								$this->routedController=new settings\Policies($this->MVC);
								$this->routeAction(3);
								return;
							}
							default:
							{
								$this->routedController=new Settings($this->MVC);
							}
						}
					}
					else
					{
						$this->routedController=new Settings($this->MVC);
					}
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
				case 'widget':
				{
					$this->widget();
					break;
				}
				default:
				{
					$this->view->render();
					exit();
				}
			}
			$this->routeAction(2);
		}
		
		private function routeAction($fromNode)
		{
			//Check for action.
			$action	=$this->request->node($fromNode);
			$args	=array();
			$node	=$fromNode+1;
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
			$html='';
			try
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
						$this->plugin->Auth->can('admin.collection.read');
						$template=$this->plugin->Template();
						$template->setTemplate($this->view->buildViewPath('admin/fileManager/collections'));
	
						$template->setKeyVal('collections',$this->plugin->Collection->getCollections());
						$html=$template->compile();
						break;
					}
					case 'admin/fileManager/files':
					{
						$this->plugin->Auth->can('admin.collection.read');
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
			}
			catch(AuthException $exception)
			{
				$template=$this->plugin->Template();
				$template->setTemplate($this->view->buildViewPath('admin/noPermission'));
				$html=$template->compile();
			}
			$this->plugin	->Responder('html')
							->setData($html)
							->send();
		}
		
		public function widget()
		{
			$nodes	=$this->request->getNodes();
			if (isset($nodes[2]))
			{
				$widget	=urldecode($nodes[2]);
				$parts	=explode('\\',$widget);
				$widget	.='\\'.ucfirst(end($parts));
			}
			else
			{
				throw new NutshellException('Widget not specified.');
			}
			if (isset($nodes[3]))
			{
				$action=$nodes[3];
			}
			else
			{
				throw new NutshellException('Action not specified.');
			}
			$widgetInstance=new $widget();
			call_user_func_array(array($widgetInstance,$action),array_slice($nodes,4));
		}
		
		public function handleLogin()
		{
			if (!$this->request->get('username'))
			{
				$this->view->setTemplate('login');
			}
			else try
			{
				$this->plugin->Auth->authenticate
				(
					$this->request->get('username'),
					$this->request->get('password')
				);
				$this->plugin->Auth	->can('login')
									->can('admin.access');
				if (!empty($this->plugin->Session->returnURL))
				{
					$this->redirect($this->plugin->Session->returnURL);
				}
				else
				{
					$this->redirect('/admin/dashboard');
				}
			}
			catch (AuthException $exception)
			{
				$code=$exception->getCode();
				if ($code==AuthException::PERMISSION_DENIED)
				{
					$this->plugin->Notification->setError('['.$code.'] You are not allowed to login here.');
				}
				else
				{
					$this->plugin->Notification->setError('['.$code.'] Your login details were incorrect.');
				}
				$this->plugin->Auth->unauthenticate();
				$this->view->setTemplate('login');
			}
		}
		
		public function logout()
		{
			$this->plugin->Auth->unauthenticate();
			$this->redirect('/admin/login/');
		}
	}
}
?>