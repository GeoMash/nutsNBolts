<?php
namespace application\base
{
	use nutshell\plugin\mvc\Mvc;
	use nutshell\plugin\mvc\Controller;
	
	class AdminController extends Controller
	{
		public $websiteTitle	="Nuts n' Bolts";
		public $brandTitle		="Nuts n' Bolts";
		
		private $breadcrumbs	=array();
		
		public function __construct(Mvc $MVC)
		{
			parent::__construct($MVC);
			$this->MVC		=$MVC;
			
			$this->view->setTemplate('admin');
			
			$this->view->setVar('NS_ENV',		NS_ENV);
			$this->view->setVar('websiteTitle',	$this->websiteTitle);
			$this->view->setVar('brandTitle',	$this->brandTitle);
			$this->show404();
			
			$this->addBreadcrumb('Home','icon-home');
			
			
			
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
	<a href="/admin/content/list/{$contentTypes[$i]['id']}">
		<i class="{$contentTypes[$i]['icon']}"></i> {$contentTypes[$i]['name']}
	</a>
</li>
HTML;
			}
			return implode('',$html);
		}
	}
}
?>