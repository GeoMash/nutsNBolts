<?php
namespace application\nutsNBolts\base
{
	use application\nutsNBolts\NutsNBolts;
	use application\nutsNBolts\base\Controller as BaseController;
	use nutshell\plugin\mvc\Mvc;
	use nutshell\helper\ObjectHelper;
	
	class AdminController extends BaseController
	{
		public $websiteTitle		="Nuts n' Bolts";
		public $brandTitle			="Nuts n' Bolts";
		
		private $breadcrumbs		=array();
		
		private $jsScriptsToLoad	=array();
		private $jsClassesToExecute	=array();
		
		private $user				=null;
		public $JSLoader			=null;
		public $config				=null;
		public $returnToAction		=false;
		
		public function __construct(Mvc $MVC)
		{
			parent::__construct($MVC);

			$this->plugin->UserAuth();

			$this->JSLoader	=$this->plugin->JsLoader();
			$this->config	=$this->application->NutsNBolts->config;
			
			if ($this->plugin->UserAuth->isAuthenticated())
			{
				$this->view->setTemplate('admin');
				$mainNav=($this->request->node(1))?$this->request->node(1):'dashboard';
				$this->view->setVar('nav_active_main',$mainNav);
				$this->view->setVar('nav_active_sub',$this->request->node(2));
				
				$this->addBreadcrumb('Dashboard','icon-dashboard','dashboard');
				
				$this->user=$this->plugin->UserAuth->getUser();
				$this->view->setVar('user',$this->user);
				$this->show404();
				
				if ($this->request->get('returnToAction'))
				{
					$this->returnToAction=$this->request->get('returnToAction');
					$this->plugin->Session->returnToAction=$this->returnToAction;
					$this->redirect($this->plugin->Url->getCurrentURL());
				}
				elseif (!empty($this->plugin->Session->returnToAction))
				{
					$this->returnToAction=$this->plugin->Session->returnToAction;
				}
				$this->view->setVar('returnToAction',$this->returnToAction);
			}
			
			$this->view->setVar('NS_ENV',		NS_ENV);
			$this->view->setVar('websiteTitle',	$this->websiteTitle);
			$this->view->setVar('brandTitle',	$this->brandTitle);
			
			$this->view->getContext()
				->registerCallback
				(
					'breadcrumbs',
					function()
					{
						print $this->generateBreadcrumbs();
					}
				)->registerCallback
				(
					'navContentTypes',
					function()
					{
						print $this->generateContentTypesforNav();
					}
				)->registerCallback
				(
					'getNotifications',
					function()
					{
						print $this->getNotifications();
					}
				)->registerCallback
				(
					'challangeRole',
					function($allowedRoles)
					{
						return $this->challangeRole($allowedRoles);
					}
				);
			if (method_exists($this,'init'))
			{
				$this->init();
			}
		}
		
		public function show404()
		{
			$this->view->setVar('contentView','404');
			return $this;
		}
		
		public function setContentView($view)
		{
			$this->view->setVar('contentView',$view);
			return $this;
		}
		
		public function addBreadcrumb($label,$icon,$urlNode)
		{
			$this->breadcrumbs[]=array
			(
				'label'		=>$label,
				'icon'		=>$icon,
				'urlNode'	=>$urlNode
			);
			return $this;
		}
		
		public function generateBreadcrumbs()
		{
			$html			=array('<div id="breadcrumbs">');
			$itemTemplate	=<<<HTML
<div class="breadcrumb-button {first}">
	<a href="{href}">
		<span class="breadcrumb-label">
			<i class="{icon}"></i> {label}
		</span>
	</a>
	<span class="breadcrumb-arrow"><span></span></span>
</div>

HTML;
			for ($i=0,$j=count($this->breadcrumbs); $i<$j; $i++)
			{
				$first='';
				if ($i===0)
				{
					$first='blue';
					$href='/admin/dashboard/';
				}
				elseif ($i===1)
				{
					$href='/admin/'.$this->breadcrumbs[$i]['urlNode'].'/';
				}
				else
				{
					$href=$this->breadcrumbs[$i]['urlNode'].'/';
				}
				$html[]=str_replace
				(
					array('{label}','{icon}','{first}','{href}'),
					array
					(
						$this->breadcrumbs[$i]['label'],
						$this->breadcrumbs[$i]['icon'],
						$first,
						$href
					),
					$itemTemplate
				);
			}
			$html[]='</div>';
			return implode('',$html);
		}
		
		public function generateContentTypesforNav()
		{
			$html=array();
			$contentTypes=$this->model->ContentType->read();
			for ($i=0,$j=count($contentTypes); $i<$j; $i++)
			{
				if ($this->userCanAccessContentType($contentTypes[$i]))
				{
					$active=($this->request->node(3)==$contentTypes[$i]['id'])?'active':'';
					$html[]	=<<<HTML
<li class="{$active}">
	<a href="/admin/content/view/{$contentTypes[$i]['id']}">
		<i class="{$contentTypes[$i]['icon']}"></i> {$contentTypes[$i]['name']}
	</a>
</li>
HTML;
				}
			}
			return implode('',$html);
		}
		
		public function __call($action,$args)
		{
			$this->view->render();
		}
		
		public function getUser()
		{
			return $this->user;
		}
		
		public function getUserId()
		{
			return $this->user['id'];
		}
		
		public function getWidgetInstance($classPath)
		{
			$className=ObjectHelper::getBaseClassName($classPath);
			$classPath.='\\'.ucwords($className);
			$classPath;
			return new $classPath;
		}
		
		public function buildWidgetHTML($contentWidgets,$widgetIndex='',$part=null)
		{
		
			$selectBoxOptions	=array();
			$getConfigFor		=$contentWidgets['NutsNBolts'][0]['namespace'];
			$template			=$this->plugin->Template();
			$template->setTemplate($this->view->buildViewPath('admin/configureContent/addWidgetSelection'));
			if ($part)
			{
				$template->setKeyValArray($part);
			}
			foreach( $contentWidgets AS $key=>$widget)
			{	
				// print_r( $key);
				// echo "<br/>";
				$selectBoxOptions[]='<optgroup label="'.$key.'">';
				for ($k=0,$l=count($widget); $k<$l; $k++)
				{
					$selected='';
					if ($part['widget']==$widget[$k]['namespace'])
					{
						$selected='selected';
						$getConfigFor=$part['widget'];
					}
					$selectBoxOptions[]='<option '.$selected.' value="'.$contentWidgets[$key][$k]['namespace'].'">'.$contentWidgets[$key][$k]['name'].'</option>';
				}
				$selectBoxOptions[]='</optgroup>';
			}
			
			$template->setKeyVal('widgetIndex',$widgetIndex);
			//TODO: Load existing options.
			$template->setKeyVal('optionIndex','0');
			
			$template->setKeyVal('widgetTypes',implode('',$selectBoxOptions));
			// echo $getConfigFor;
			// die();
			$widgetConfig=$this->getWidgetInstance($getConfigFor)->getConfigHTML($widgetIndex,$part['config']);
			
			if ($widgetConfig)
			{
				$className=ucwords(ObjectHelper::getBaseClassName($getConfigFor));
				$exec=strtolower(str_replace
				(
					array('application\\','\\'),
					array('','.'),
					$getConfigFor
				)).'.Config';
				
				$this->JSLoader->loadScript('/admin/script/widget/config/'.$getConfigFor,$exec);
			}
			else
			{
				$widgetConfig='None';
			}
			$template->setKeyVal('options',$widgetConfig);
			$html=$template->compile();
			$html.=$this->JSLoader->getLoaderHTML();
			return $html;
		}
		
		public function getNotifications()
		{
			$ret=$this->plugin->Notification->getErrorsHTML()
					.$this->plugin->Notification->getWarningsHTML()
					.$this->plugin->Notification->getInfosHTML()
					.$this->plugin->Notification->getSucessesHTML();
			$this->plugin->Notification->clearAll();
			return $ret;
		}
		
		public function isAuthenticated()
		{
			return (bool)($this->plugin->Session->authenticated);
		}
		
		public function challangeRole($allowedRoles)
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
		
		public function isSuper()
		{
			return $this->plugin->UserAuth->isSuper();
		}

		public function userCanAccessContentType($contentType)
		{
			if ($this->isSuper())return true;
			if ($this->challangeRole($contentType['roles']))
			{
				if (!count($contentType['users']))
				{
					return true;
				}
				else
				{
					$user=$this->getUser();
					for ($i=0,$j=count($contentType['users']); $i<$j; $i++)
					{
						if ($contentType['users'][$i]['id']==$user['id'])
						{
							return true;
						}
					}
				}
			}
			return false;
		}
	}
}
?>