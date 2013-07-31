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
				case 'add':		$this->addType();		break;
				case 'edit':	$this->editType($id);	break;
				case 'remove':	$this->removeType($id);	break;
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
		
		public function pages($action=null,$id=null)
		{
			$this->addBreadcrumb('Configure Pages','icon-cogs');
			$this->addBreadcrumb('Pages','icon-copy');
			switch ($action)
			{
				case 'add':		$this->addPage();		break;
				case 'edit':	$this->editPage($id);	break;
				case 'remove':	$this->removePage($id);	break;
				default:
				{
					$this->setContentView('admin/configurePages/pages');
					$this->view->getContext()
					->registerCallback
					(	
						'getPagesList',
						function()
						{
							print $this->generatePageList();
						}
					);
				}
			}
			$this->view->getContext()
			->registerCallback
			(	
				'getPageTypeOptions',
				function($options=null)
				{
					print $this->getPageTypeOptions($options);
				}
			);
			$this->view->render();
		}
		
		private function addType()
		{
			if (!$this->request->get('name'))
			{
				$this->addBreadcrumb('Add Page','icon-copy');
				$this->setContentView('admin/configurePages/addEditType');
			}
			else
			{
				$id=$this->model->PageType->handleRecord($this->request->getAll());
				$this->redirect('/admin/configurepages/types/edit/'.$id);
			}
		}
		
		private function addPage()
		{
			if (!$this->request->get('name'))
			{
				$this->addBreadcrumb('Add Page','icon-plus');
				$this->setContentView('admin/configurePages/addEditPage');
			}
			else
			{
				$id=$this->model->Page->handleRecord($this->request->getAll());
				$this->redirect('/admin/configurepages/pages/edit/'.$id);
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
		
		private function editPage($id)
		{
			if ($this->request->get('id'))
			{
				$this->model->Page->handleRecord($this->request->getAll());
			}
			$this->addBreadcrumb('Edit Page','icon-edit');
			$this->setContentView('admin/configurePages/addEditPage');
			if ($record=$this->model->Page->read($id))
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
			$this->model->PageType->handleDeleteRecord($id);
			$this->redirect('/admin/configurepages/types/');
		}
		
		public function removePage($id)
		{
			$this->model->Page->handleDeleteRecord($id);
			$this->redirect('/admin/configurepages/pages/');
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
		
		public function generatePageList()
		{
			$html=array();
			$pages=$this->model->Page->read();
			for ($i=0,$j=count($pages); $i<$j; $i++)
			{
				$html[]=<<<HTML
<div class="box-section news with-icons relative">
	<div class="avatar blue"></div>
	<a href="/admin/configurepages/pages/remove/{$pages[$i]['id']}">
		<span class="triangle-button red"><i class="icon-remove"></i></span>
	</a>
	<div class="news-content">
		<div class="news-title"><a href="/admin/configurepages/pages/edit/{$pages[$i]['id']}">{$pages[$i]['title']}</a></div>
		<div class="news-text">{$pages[$i]['description']}</div>
	</div>
</div>
HTML;
			}
			return implode('',$html);
		}
		
		public function getPageTypeOptions($selectedId=null)
		{
			$return=array();
			$pageTypes=$this->model->PageType->read(array('status'=>1));
			for ($i=0,$j=count($pageTypes); $i<$j; $i++)
			{
				$selected=($pageTypes[$i]['id']==$selectedId)?'selected':'';
				$return[]='<option value="'.$pageTypes[$i]['id'].'" '.$selected.'>'.$pageTypes[$i]['name'].'</option>';
			}
			return implode('',$return);
		}
	}
}
?>