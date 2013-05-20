<?php
namespace application\controller\admin
{
	use application\base\AdminController;
	
	class ConfigurePages extends AdminController
	{
		public function index()
		{
			$this->show404();
		}
		
		public function types($action=null,$id=null)
		{
			$this->addBreadcrumb('Configure Pages','icon-cogs');
			$this->addBreadcrumb('Types','icon-th-large');
			switch ($action)
			{
				case 'add':
				{
					$this->addType();
					break;
				}
				case 'edit':
				{
					$this->editType($id);
					break;
				}
				case 'remove':
				{
					$this->removeType($id);
					break;
				}
				default:
				{
					$this->setContentView('admin/configurePages/types');
					$this->view->getContext()
					->registerCallback
					(
						'getTypesList',
						function()
						{
							print $this->generateTypeList();
						}
					);
				}
			}
			$this->view->render();
		}
		
		private function addType()
		{
			if (!$this->request->get('name'))
			{
				$this->addBreadcrumb('Add Page Type','icon-plus');
				$this->setContentView('admin/configurePages/addEditType');
			}
			else
			{
				$id=$this->model->PageType->handleRecord($this->request->getAll());
				$this->redirect('/admin/configurepages/types/edit/'.$id);
			}
		}
		
		private function editType($id)
		{
			if ($this->request->get('id'))
			{
				$this->model->PageType->handleRecord($this->request->getAll());
			}
			$this->addBreadcrumb('Edit Page Type','icon-edit');
			$this->setContentView('admin/configurePages/addEditType');
			if ($record=$this->model->PageType->read($id))
			{
				$this->view->setVars($record[0]);
			}
			else
			{
				$this->view->setVar('record',array());
			}
		}
		
		public function removeType($id)
		{
			$this->model->ContentType->handleDeleteRecord($id);
			$this->redirect('/admin/configurecontent/types/');
		}
		
		public function generateTypeList()
		{
			$html=array();
			$pageTypes=$this->model->PageType->read();
			for ($i=0,$j=count($pageTypes); $i<$j; $i++)
			{
				$html[]=<<<HTML
<div class="box-section news with-icons relative">
	<div class="avatar blue"></div>
	<a href="/admin/configurepages/types/remove/{$pageTypes[$i]['id']}">
		<span class="triangle-button red"><i class="icon-remove"></i></span>
	</a>
	<div class="news-content">
		<div class="news-title"><a href="/admin/configurepages/types/edit/{$pageTypes[$i]['id']}">{$pageTypes[$i]['name']}</a></div>
		<div class="news-text">{$pageTypes[$i]['description']}</div>
	</div>
</div>
HTML;
			}
			return implode('',$html);
		}
	}
}
?>