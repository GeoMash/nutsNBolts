<?php
namespace application\controller\admin
{
	use application\base\AdminController;
	
	class ConfigureContent extends AdminController
	{
		public function index()
		{
			$this->show404();
		}
		
		public function types()
		{
			$this->setContentView('admin/configureContent/types');
			$this->addBreadcrumb('Configure Content','icon-cogs');
			$this->addBreadcrumb('Types','icon-th');
			$this->view->getContext()
				->registerCallback
				(
					'getContentTypesList',
					function()
					{
						print $this->generateContentTypeList();
					}
				);
			$this->view->render();
		}
		
		public function widgets()
		{
			$this->view->render();
		}
		
		public function generateContentTypeList()
		{
			$html=array();
			$contentTypes=$this->model->ContentType->read();
			for ($i=0,$j=count($contentTypes); $i<$j; $i++)
			{
				$html[]=<<<HTML
<div class="box-section news with-icons">
	<div class="avatar blue"><i class="{$contentTypes[$i]['icon']} icon-2x"></i></div>
	<div class="news-time">
		<button><i class="icon-edit icon-2x"></i></button>
	</div>
	<div class="news-content">
		<div class="news-title"><a href="#">{$contentTypes[$i]['name']}</a></div>
		<div class="news-text">{$contentTypes[$i]['description']}</div>
	</div>
</div>
HTML;
			}
			return implode('',$html);
		}
	}
}
?>