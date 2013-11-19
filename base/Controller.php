<?php
namespace application\nutsNBolts\base
{
	use nutshell\plugin\mvc\Mvc;
	use nutshell\plugin\mvc\Controller as MvcController;
	
	class Controller extends MvcController
	{
		private $site=null;
		private $user=null;
		
		public function __construct(Mvc $MVC)
		{
			parent::__construct($MVC);
			$this->MVC=$MVC;
			$this->plugin->Session();
			$result=$this->model->Site->read(array('domain'=>$_SERVER['HTTP_HOST']));
			if (isset($result[0]))
			{
				$this->site=$result[0];
			}
			else
			{
				die('No site registered for this domain!');
			}
			if (!$this->application->NutsNBolts->getSiteBinding($this->getSiteRef()))
			{
				die('No site bound for this domain!');
			}
			if ($this->plugin->UserAuth->isAuthenticated())
			{
				$this->user=$this->plugin->UserAuth->getUser();
				$this->view->setVar('user',$this->user);
			}
			$this->view->getContext()
				->registerCallback
				(
					'getAllNotifications',
					function()
					{
						return $this->plugin->Notification->getAll();
					}
				)->registerCallback
				(
					'challangeRole',
					function($allowedRoles)
					{
						return $this->challengeRole($allowedRoles);
					}
				)->registerCallback
				 (
					'isAuthenticated',
					function()
					{
						return $this->plugin->UserAuth->isAuthenticated();
					}
				)->registerCallback
				(
				 	'logout',
				 	function()
				 	{
				 		return $this->plugin->UserAuth->logout();
				 	}
				 );
			if (method_exists($this,'init'))
			{
				$this->init();
			}
		}
		
		public function getSite()
		{
			return $this->site;
		}
		
		public function getSiteId()
		{
			return $this->site['id'];
		}
		
		public function getSiteRef()
		{
			return $this->site['ref'];
		}
		
		public function redirect($path)
		{
			header('location:'.$path);
			exit();
		}
		
		private $hookContainers=array();
		
		public function loadHooks($ref=null,$subFolder=null)
		{
			$ref=ucfirst($ref);
			foreach ($this->application->getLoaded() as $applicationRef=>$application)
			{
				$this->loadGlobalHooks($applicationRef);
				$this->loadFormHooks($applicationRef);
				if (!$ref)continue;
				if (!is_string($subFolder))
				{
					$className	=$this->application->getNamespace($applicationRef).'\hook\\'.$ref;
					$path		=APP_HOME.lcfirst($applicationRef)._DS_.'hook'._DS_.$ref.'.php';
				}
				else
				{
					$className	=$this->application->getNamespace($applicationRef).'\hook\\'.$subFolder.'\\'.$ref;
					$path		=APP_HOME.lcfirst($applicationRef)._DS_.'hook'._DS_.$subFolder._DS_.$ref.'.php';
				}
				if (is_file($path))
				{
					require_once($path);
					if (class_exists($className))
					{
						if(count($this->hookContainers))
						{
							if (!is_array($this->hookContainers[$applicationRef]))
							{
								$this->hookContainers[$applicationRef]=array();
							}
						}
						$this->hookContainers[$applicationRef][$ref]=new $className($this->model,$this->view);
					}
					else
					{
						//TODO: Throw exception re bad hook class name
					}
				}
			}
		}	
		
		private function loadGlobalHooks($applicationRef)
		{
			$className=$this->application->getNamespace($applicationRef).'\hook\_Global';
			$path=APP_HOME.lcfirst($applicationRef)._DS_.'hook'._DS_.'_Global.php';
			if (is_file($path))
			{
				require_once($path);
				if (class_exists($className))
				{
					if ( isset($this->hookContainers[$applicationRef]) && !is_array($this->hookContainers[$applicationRef]))
					{
						$this->hookContainers[$applicationRef]=array();
					}
					$this->hookContainers[$applicationRef]['_Global']=new $className($this->model,$this->view);
				}
				else
				{
					//TODO: Throw exception re bad hook class name
				}
			}
		}
				
		private function loadFormHooks($applicationRef)
		{
			$className=$this->application->getNamespace($applicationRef).'\hook\_Forms';
			$path=APP_HOME.lcfirst($applicationRef)._DS_.'hook'._DS_.'_Forms.php';
			if (is_file($path))
			{
				require_once($path);
				if (class_exists($className))
				{
					if ( isset($this->hookContainers[$applicationRef]) && !is_array($this->hookContainers[$applicationRef]))
					{
						$this->hookContainers[$applicationRef]=array();
					}
					$this->hookContainers[$applicationRef]['_Forms']=new $className($this->model,$this->view);
				}
				else
				{
					//TODO: Throw exception re bad hook class name
				}
			}
		}

		public function execHook($hook,&$a=null,&$b=null,&$c=null,&$d=null,&$e=null,&$f=null,&$g=null,&$h=null,&$i=null,&$j=null,&$k=null)//($hook,&$args)
		{
			$table	=array_merge(array('hook'),range('a','k'));
			$xargs	=array();
			$args	=func_get_args();
			for ($ci=1,$cj=func_num_args(); $ci<$cj; $ci++)
			{
				$xargs[]=&$args[$ci];
			}
			foreach ($this->application->getLoaded() as $applicationRef=>$application)
			{
				if (!isset($this->hookContainers[$applicationRef]))
				{
					continue;
				}
				foreach ($this->hookContainers[$applicationRef] as $container)
				{
					if (method_exists($container,$hook))
					{
						call_user_func_array(array($container,$hook),$xargs);
					}
				}
			}
			for ($ci=1,$cj=func_num_args(); $ci<$cj; $ci++)
			{
				${$table[$ci]}=$xargs[$ci-1];
			}
		}		
		
		private function getPath()
		{
			$nodes=$this->request->getNodes();
			if (count($nodes)===1 && empty($nodes[0]))
			{
				return '/';
			}
			else
			{
				return '/'.implode('/',$nodes).'/';
			}
		}	
		
		private $widgetContainers=array();
		public function loadCustomWidgets($ref=null)
		{	
			
			$ref=ucfirst($ref);
			foreach ($this->application->getLoaded() as $applicationRef=>$application)
			{
				if($applicationRef != "NutsNBolts")
				{
					if (!$ref)continue;
					$className=$this->application->getNamespace($applicationRef).'\widget\\'.strtolower($ref).'\\'.$ref;
					$path=APP_HOME.lcfirst($applicationRef)._DS_.'widget'._DS_.strtolower($ref)._DS_.$ref.'.php';
					if (is_file($path))
					{
						require_once($path);	
						if (class_exists($className))
						{
							if (!is_array($this->widgetContainers))
							{
								$this->widgetContainers[$applicationRef]=array();
							}
							$this->widgetContainers[$applicationRef][$ref]=new $className($this->model,$this->view);
						}
						else
						{
							//TODO: Throw exception re bad widget class name
						}
					}					
				}

			}
		}

		public function challengeRole($allowedRoles)
		{
			if ($this->isSuper())return true;

			if (!is_array($allowedRoles))
			{
				$allowedRoles=array($allowedRoles);
			}
			$user=$this->getUser();
			for ($i=0,$j=count($allowedRoles); $i<$j; $i++)
			{
				for ($k=0,$l=count($user['roles']); $k<$l; $k++)
				{
					if (is_array($allowedRoles[$i]))
					{
						if ($allowedRoles[$i]['id']==$user['roles'][$k]['id'])
						{
							return true;
						}
					}
					else if (is_numeric($allowedRoles[$i]))
					{
						if ($allowedRoles[$i]==$user['roles'][$k]['id'])
						{
							return true;
						}
					}
					else if (is_string($allowedRoles[$i]))
					{
						if ($allowedRoles[$i]==$user['roles'][$k]['ref'])
						{
							return true;
						}
					}
				}
			}
			return false;
		}

		public function getUser()
		{
			return $this->plugin->UserAuth->getUser();
		}

		public function getUserId()
		{
			return isset($this->user['id'])?$this->user['id']:null;
		}

		public function isSuper()
		{
			if($this->plugin->UserAuth->isSuper() || $this->isAdmin())
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function isAdmin()
		{
			return $this->plugin->UserAuth->isAdmin();
		}
					
	}
}
?>