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
		
		public function types($action=null)
		{
			$this->addBreadcrumb('Configure Content','icon-cogs');
			$this->addBreadcrumb('Types','icon-th');
			switch ($action)
			{
				case 'add':
				{
					$this->addType();
					break;
				}
				case 'edit':
				{
					$this->editType();
					break;
				}
				default:
				{
					$this->setContentView('admin/configureContent/types');
					$this->view->getContext()
					->registerCallback
					(
						'getContentTypesList',
						function()
						{
							print $this->generateContentTypeList();
						}
					);
				}
			}
			$this->view->render();
		}
		
		private function addType()
		{
			$this->addBreadcrumb('Add Content Type','icon-plus');
			$this->setContentView('admin/configureContent/addEditType');
		}
		
		private function editType()
		{
			$this->addBreadcrumb('Edit Content Type','icon-edit');
			$this->setContentView('admin/configureContent/addEditType');
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
	<div class="news-content">
		<div class="news-title"><a href="/admin/configurecontent/types/edit/{$contentTypes[$i]['id']}">{$contentTypes[$i]['name']}</a></div>
		<div class="news-text">{$contentTypes[$i]['description']}</div>
	</div>
</div>
HTML;
			}
			return implode('',$html);
		}
		
		public function getAddContentTypeWidgetOptions()
		{
			$options=array();
			$contentWidgets=$this->model->ContentWidget->read();
			for ($i=0,$j=count($contentWidgets); $i<$j; $i++)
			{
				$options[]='<option value="'.$contentWidgets[$i]['id'].'">'.$contentWidgets[$i]['name'].'</option>';
			}
			$this->plugin	->Responder('html')
							->setData(implode('',$options))
							->send();
		}
	}
}
?>