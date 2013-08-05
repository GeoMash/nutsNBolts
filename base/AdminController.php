<?php
namespace application\nutsnbolts\base
{
	use application\nutsnbolts\base\Controller as BaseController;
	use nutshell\plugin\mvc\Mvc;
	use nutshell\helper\ObjectHelper;
	
	class AdminController extends BaseController
	{
		public $websiteTitle	="Nuts n' Bolts";
		public $brandTitle		="Nuts n' Bolts";
		
		private $breadcrumbs	=array();
		
		private $jsScriptsToLoad	=array();
		private $jsClassesToExecute	=array();
		
		private $user			=null;
		
		public function __construct(Mvc $MVC)
		{
			parent::__construct($MVC);
			$this->MVC=$MVC;
			
			$this->view->setTemplate('admin');
			
			$this->view->setVar('NS_ENV',		NS_ENV);
			$this->view->setVar('websiteTitle',	$this->websiteTitle);
			$this->view->setVar('brandTitle',	$this->brandTitle);
			$this->show404();
			
			$this->addBreadcrumb('Home','icon-home');
			
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
		
		public function addBreadcrumb($label,$icon)
		{
			$this->breadcrumbs[]=array
			(
				'label'	=>$label,
				'icon'	=>$icon
			);
			return $this;
		}
		
		public function generateBreadcrumbs()
		{
			$html			=array('<div id="breadcrumbs">');
			$itemTemplate	=<<<HTML
<div class="breadcrumb-button {first}">
	<span class="breadcrumb-label">
		<i class="{icon}"></i> {label}
	</span>
	<span class="breadcrumb-arrow"><span></span></span>
</div>

HTML;
			for ($i=0,$j=count($this->breadcrumbs); $i<$j; $i++)
			{
				$first=($i===0)?'blue':'';
				$html[]=str_replace
				(
					array('{label}','{icon}','{first}'),
					array
					(
						$this->breadcrumbs[$i]['label'],
						$this->breadcrumbs[$i]['icon'],
						$first
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
				$html[]	=<<<HTML
<li class="">
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
		
		public function addToJSLoad($classPath,$exec=false)
		{
			$this->jsScriptsToLoad[]=str_replace('\\','.',$classPath);
			if ($exec)
			{
				$this->jsClassesToExecute[]=$exec;
			}
			return $this;
		}
		
		public function getFormattedJsScriptList()
		{
			return "'".implode("','",$this->jsScriptsToLoad)."'";
		}
		
		public function getJSClassesToExecute()
		{
			return $this->jsClassesToExecute;
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
				$this->addToJSLoad('/admin/script/widget/config/'.$getOptionsFor,$exec);
			}
			else
			{
				$widgetOptions='None';
			}
			$template->setKeyVal('options',$widgetOptions);
			
			return $template->compile();
		}
		
	}
}
?>