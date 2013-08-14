<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use nutshell\helper\ObjectHelper;
	
	class ConfigureContent extends AdminController
	{
		
		public function index()
		{
			$this->show404();
			$this->view->render();
		}
		
		public function types($action=null,$id=null)
		{
			$this->addBreadcrumb('Configure Content','icon-cogs','configurecontent');
			$this->addBreadcrumb('Types','icon-th','types');
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
			if (!$this->request->get('name'))
			{
				$this->addBreadcrumb('Add Content Type','icon-plus','add');
				$this->setContentView('admin/configureContent/addEditType');
			}
			else
			{
				$record=$this->request->getAll();
				$record['site_id']=$this->getSiteId();
				
				if ($id=$this->model->ContentType->handleRecord($record))
				{
					$this->plugin->Notification->setSuccess('Content type successfully added. Would you like to <a href="/admin/configurecontent/types/add/">Add another one?</a>');
					$this->redirect('/admin/configureContent/types/edit/'.$id);
				}
				else
				{
					$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
				}
			}
		}
		
		private function editType($id)
		{
			if ($this->request->get('name'))
			{
				if ($this->model->ContentType->handleRecord($this->request->getAll())!==false)
				{

					$this->plugin->Notification->setSuccess('Content type successfully edited.');
				}
				else
				{
					$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
				}
			}
			$this->addBreadcrumb('Edit Content Type','icon-edit','edit/'.$id);
			$this->setContentView('admin/configureContent/addEditType');
			if ($record=$this->model->ContentType->read($id))
			{
				$record		=$record[0];
				$partHTML	='';
				$this->view->setVars($record);
				$parts=$this->model->ContentPart->read(array('content_type_id'=>$id));
				if (count($parts))
				{
					$contentWidgets=$this->application->NutsNBolts->getWidgetList();
					for ($i=0,$j=count($parts); $i<$j; $i++)
					{
						 $partHTML.=$this->buildWidgetHTML($contentWidgets,$i,$parts[$i]);
					}
					$partHTML.=$this->JSLoader->getLoaderHTML();
				}
				$this->view->setVar('parts',$partHTML);
			}
			else
			{
				$this->view->setVar('record',array());
			}
		}
		
		public function removeType($id)
		{
			if ($this->model->ContentType->handleDeleteRecord($id))
			{
				$this->plugin->Notification->setSuccess('Content type successfully removed.');
				$this->redirect('/admin/configurecontent/types/');
			}
			else
			{
				$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
			}
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
<div class="box-section news with-icons relative">
	<div class="avatar blue"><i class="{$contentTypes[$i]['icon']} icon-2x"></i></div>
	<a href="/admin/configurecontent/types/remove/{$contentTypes[$i]['id']}">
		<span class="triangle-button red"><i class="icon-remove"></i></span>
	</a>
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
			$contentWidgets=$this->application->NutsNBolts->getWidgetList();
			for ($i=0,$j=count($contentWidgets); $i<$j; $i++)
			{
				$options[]='<option value="'.$contentWidgets[$i]['namespace'].'">'.$contentWidgets[$i]['name'].'</option>';
			}
			$this->plugin	->Responder('html')
							->setData(implode('',$options))
							->send();
		}
		
		public function getConfigForWidget()
		{
			$widget			=$this->getWidgetInstance($this->request->get('widget'));
			$widgetOptions	=$widget->getConfigHTML($this->request->get('index'));
			
			if (!empty($widgetOptions))
			{
				$exec=str_replace
				(
					array('application\\','\\'),
					array('','.'),
					$this->request->get('widget')
				).'.Config';
				$this->JSLoader->loadScript('/admin/script/widget/config/'.$this->request->get('widget'),$exec);
				$widgetOptions.=$this->JSLoader->getLoaderHTML();
			}
			else
			{
				$widgetOptions='None';
			}
			$this->plugin	->Responder('html')
							->setData($widgetOptions)
							->send();
		}

		public function getJsForWidget()
		{
			$widget			=$this->getWidgetInstance($this->request->get('widget'));
			$widgetOptions	=$widget->getJsHTML($this->request->get('index'));
			
			if (!empty($widgetOptions))
			{
				// $exec=str_replace
				// (
				// 	array('application\\','\\'),
				// 	array('','.'),
				// 	$this->request->get('widget')
				// ).'.Widget';
				// $this->JSLoader->loadScript('/admin/script/widget/config/'.$this->request->get('widget'),$exec);
				$widgetOptions.=$this->JSLoader->getLoaderHTML();
			}
			else
			{
				$widgetOptions='None';
			}
			$this->plugin	->Responder('html')
							->setData($widgetOptions)
							->send();
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		public function forms($action=null,$id=null)
		{
			$this->addBreadcrumb('Configure Content','icon-cogs','configurecontent');
			$this->addBreadcrumb('Forms','icon-list-alt','forms');
			switch ($action)
			{
				case 'add':
				{
					$this->addForm();
					break;
				}
				case 'edit':
				{
					$this->editForm($id);
					break;
				}
				case 'remove':
				{
					$this->removeForm($id);
					break;
				}
				default:
				{
					$this->setContentView('admin/configureContent/forms');
					$this->view->getContext()
					->registerCallback
					(
						'getFormsList',
						function()
						{
							print $this->generateFormsList();
						}
					);
				}
			}
			$this->view->render();
		}
		
		private function addForm()
		{
			if (!$this->request->get('name'))
			{
				$this->addBreadcrumb('Add Form','icon-plus','add');
				$this->setContentView('admin/configureContent/addEditForm');
			}
			else
			{
				$record=$this->request->getAll();
				$record['site_id']=$this->getSiteId();
				
				if ($id=$this->model->Form->handleRecord($record))
				{
					$this->plugin->Notification->setSuccess('Form successfully added. Would you like to <a href="/admin/configurecontent/form/add/">Add another one?</a>');
					$this->redirect('/admin/configureContent/forms/edit/'.$id);
				}
				else
				{
					$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
				}
			}
		}
		
		private function editForm($id)
		{
			if ($this->request->get('name'))
			{
				if ($this->model->Form->handleRecord($this->request->getAll())!==false)
				{

					$this->plugin->Notification->setSuccess('Form successfully edited.');
				}
				else
				{
					$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
				}
			}
			$this->addBreadcrumb('Edit Form','icon-edit','edit/'.$id);
			$this->setContentView('admin/configureContent/addEditForm');
			if ($record=$this->model->Form->read($id))
			{
				$this->view->setVars($record[0]);
			}
			else
			{
				$this->view->setVar('record',array());
			}
		}
		
		private function removeForm()
		{
			
		}
		
		
		
		private function generateFormsList()
		{
			$html=array();
			$forms=$this->model->Form->read();
			for ($i=0,$j=count($forms); $i<$j; $i++)
			{
				$html[]=<<<HTML
<div class="box-section news with-icons relative">
	<div class="avatar blue"></div>
	<a href="/admin/configurecontent/forms/remove/{$forms[$i]['id']}">
		<span class="triangle-button red"><i class="icon-remove"></i></span>
	</a>
	<div class="news-content">
		<div class="news-title"><a href="/admin/configurecontent/forms/edit/{$forms[$i]['id']}">{$forms[$i]['name']}</a></div>
		<div class="news-text">{$forms[$i]['description']}</div>
	</div>
</div>
HTML;
			}
			return implode('',$html);
		}
		
	}
}
?>