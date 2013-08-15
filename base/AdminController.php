<?php
namespace application\nutsNBolts\base
{
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
		
		public function __construct(Mvc $MVC)
		{
			parent::__construct($MVC);
			
			$this->JSLoader	=$this->plugin->JsLoader();
			$this->config	=$this->application->NutsNBolts->config;
			
			if ($this->isAuthenticated())
			{
				$this->view->setTemplate('admin');
				$mainNav=($this->request->node(1))?$this->request->node(1):'dashboard';
				$this->view->setVar('nav_active_main',$mainNav);
				$this->view->setVar('nav_active_sub',$this->request->node(2));
				
				
				
				$this->addBreadcrumb('Dashboard','icon-dashboard','dashboard');
				
				$this->user=$this->model->User->read($this->plugin->Session->userId)[0];
				$this->view->setVar('user',$this->user);
				
				$this->show404();
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
				);
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
					$href.=$this->breadcrumbs[$i]['urlNode'].'/';
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
				$active=($this->request->node(3)==$contentTypes[$i]['id'])?'active':'';
				$html[]	=<<<HTML
<li class="{$active}">
	<a href="/admin/content/view/{$contentTypes[$i]['id']}">
		<i class="{$contentTypes[$i]['icon']}"></i> {$contentTypes[$i]['name']}
	</a>
</li>
HTML;
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
			return new $classPath;
		}
		
		public function buildWidgetHTML($contentWidgets,$widgetIndex='',$part=null)
		{
			$selectBoxOptions	=array();
			$getConfigFor		=$contentWidgets[0]['namespace'];
			$template			=$this->plugin->Template();
			$template->setTemplate($this->view->buildViewPath('admin/configureContent/addWidgetSelection'));
			if ($part)
			{
				$template->setKeyValArray($part);
			}
			for ($k=0,$l=count($contentWidgets); $k<$l; $k++)
			{
				$selected='';
				if ($part['widget']==$contentWidgets[$k]['namespace'])
				{
					$selected='selected';
					$getConfigFor=$part['widget'];
				}
				$selectBoxOptions[]='<option '.$selected.' value="'.$contentWidgets[$k]['namespace'].'">'.$contentWidgets[$k]['name'].'</option>';
			}
			$template->setKeyVal('widgetIndex',$widgetIndex);
			//TODO: Load existing options.
			$template->setKeyVal('optionIndex','0');
			
			$template->setKeyVal('widgetTypes',implode('',$selectBoxOptions));
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
	}
}
?>