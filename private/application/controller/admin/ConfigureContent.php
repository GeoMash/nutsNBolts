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
		
		public function types($action=null,$id=null)
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
					$this->editType($id);
					break;
				}
				case 'remove':
				{
					$this->deleteType($id);
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
				$this->addBreadcrumb('Add Content Type','icon-plus');
				$this->setContentView('admin/configureContent/addEditType');
			}
			else
			{
				$id=$this->model->ContentType->handleRecord($this->request->getAll());
				$this->redirect('/admin/configurecontent/types/edit/'.$id);
			}
		}
		
		private function editType($id)
		{
			if ($this->request->get('name'))
			{
				$this->model->ContentType->handleRecord($this->request->getAll());
			}
			$this->addBreadcrumb('Edit Content Type','icon-edit');
			$this->setContentView('admin/configureContent/addEditType');
			if ($record=$this->model->ContentType->read($id))
			{
				$record		=$record[0];
				$partHTML	='';
				$this->view->setVars($record);
				$parts=$this->model->ContentPart->read(array('content_type_id'=>$id));
				
				if (count($parts))
				{
					$contentWidgets=$this->model->ContentWidget->read();
					for ($i=0,$j=count($parts); $i<$j; $i++)
					{
						$template=$this->plugin->Template();
						$template->setTemplate($this->view->buildViewPath('admin/configureContent/addWidgetSelection'));
						$template->setKeyValArray($parts[$i]);
						$options=array();
						for ($k=0,$l=count($contentWidgets); $k<$l; $k++)
						{
							$selected=($parts[$i]['content_widget_id']==$contentWidgets[$k]['id'])?'selected':'';
							$options[]='<option '.$selected.' value="'.$contentWidgets[$k]['id'].'">'.$contentWidgets[$k]['name'].'</option>';
						}
						$template->setKeyVal('widgetTypes',implode('',$options));
						$partHTML.=$template->compile();
					}
				}
				$this->view->setVar('parts',$partHTML);
			}
			else
			{
				$this->view->setVar('record',array());
			}
		}
		
		public function deleteType($id)
		{
			$this->model->ContentType->handleDeleteRecord($id);
			$this->redirect('/admin/configurecontent/types/');
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