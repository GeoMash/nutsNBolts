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
			
			$this->addBreadcrumb('Home','icon-home');
			
			
			
			$this->view->getContext()
				->registerCallback
				(
					'breadcrumbs',
					function()
					{
						print $this->generateBreadcrumbs();
					}
				);
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
			for ($i=0,$j=1,$k=count($this->breadcrumbs); $i<$k; $i++,$j++)
			{
				$arrow	='';
				$first	='';
				$last	='';
				if ($i===0)
				{
					$first='blue';
				}
				if ($j!=$k)
				{
					$arrow='<span class="breadcrumb-arrow"><span></span></span>';
				}
				else
				{
					$last='<span class="breadcrumb-arrow"><span></span></span>';
				}
				$html[]=str_replace
				(
					array('{label}','{icon}','{arrow}','{first}','{last}'),
					array
					(
						$this->breadcrumbs[$i]['label'],
						$this->breadcrumbs[$i]['icon'],
						$arrow,
						$first,
						$last
					),
					$itemTemplate
				);
			}
			$html[]='</div>';
			return implode('',$html);
		}
		
		private function parseBreadcrumbItem()
		{
			
		}
	}
}
?>