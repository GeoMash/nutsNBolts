<?php
namespace application\nutsnbolts\base
{
	use application\nutsnbolts\base\Controller as BaseController;
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
		
		public function __construct(Mvc $MVC)
		{
			parent::__construct($MVC);
			$this->MVC		=$MVC;
			$this->JSLoader	=$this->plugin->JsLoader();
			
			$this->view->setTemplate('admin');
			
			$this->view->setVar('NS_ENV',		NS_ENV);
			$this->view->setVar('websiteTitle',	$this->websiteTitle);
			$this->view->setVar('brandTitle',	$this->brandTitle);
			
			$mainNav=($this->request->node(1))?$this->request->node(1):'dashboard';
			$this->view->setVar('nav_active_main',$mainNav);
			$this->view->setVar('nav_active_sub',$this->request->node(2));
			
			$this->show404();
			
			$this->addBreadcrumb('Dashboard','icon-dashboard','dashboard');
			
			//TODO: Change this once users are actually implemented.
			$this->user=array('id'=>1);
			
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
		
		public function redirect($path)
		{
			header('location:'.$path);
			exit();
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
			$getOptionsFor		=$contentWidgets[0]['namespace'];
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
					$getOptionsFor=$part['widget'];
				}
				$selectBoxOptions[]='<option '.$selected.' value="'.$contentWidgets[$k]['namespace'].'">'.$contentWidgets[$k]['name'].'</option>';
			}
			$template->setKeyVal('widgetIndex',$widgetIndex);
			//TODO: Load existing options.
			$template->setKeyVal('optionIndex','0');
			
			$template->setKeyVal('widgetTypes',implode('',$selectBoxOptions));
			$widgetOptions=$this->getWidgetInstance($getOptionsFor)->getConfigHTML($widgetIndex,$part['config']);
			
			if ($widgetOptions)
			{
				$className=ucwords(ObjectHelper::getBaseClassName($getOptionsFor));
				$exec=str_replace
				(
					array('application\\','\\'),
					array('','.'),
					$getOptionsFor
				).'.Config';
				
				$this->JSLoader->loadScript('/admin/script/widget/config/'.$getOptionsFor,$exec);
			}
			else
			{
				$widgetOptions='None';
			}
			$template->setKeyVal('options',$widgetOptions);
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
	}
}
?>