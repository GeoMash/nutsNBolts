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
					$this->view->getContext()
					->registerCallback
					(
						'getUserRoles',
						function()
						{
							print $this->generateRolesList(null);
						}
					)->registerCallback
					(
						'getUserList',
						function()
						{
							print $this->generateUserList(null);
						}
					)->registerCallback
					(
						'getWorkflowOptions',
						function()
						{
							print $this->plugin->Workflow->getWorkflowOptions();
						}
					);
					$this->addType();
					break;
				}
				case 'edit':
				{
					$this->view->getContext()
					->registerCallback
					(
						'getUserRoles',
						function() use ($id)
						{
							print $this->generateRolesList($id);
						}
					)->registerCallback
					(
						'getUserList',
						function() use ($id)
						{
							print $this->generateUserList($id);
						}
					)->registerCallback
					(
						'getWorkflowOptions',
						function($workflowId)
						{
							$options=$this->plugin->Workflow->getWorkflowOptions($workflowId);
							$html	=array();
							for ($i=0,$j=count($options); $i<$j; $i++)
							{
								$selected	=($options[$i]['selected'])?'selected':'';
								$html[]		='<option value="'.$options[$i]['value'].'" '.$selected.'>'.$options[$i]['label'].'</option>';
							}
							print implode('',$html);
						}
					);
					$this->editType($id);
					break;
				}
				case 'remove':
				{
					$this->removeType($id);
					break;
				}
				case 'duplicate':
				{
					$this->duplicateContentType($id);
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
			if ($this->returnToAction)
			{
				$this->plugin->Notification->setInfo('Do you want to return to <a href="'.$this->returnToAction.'">editing the content item</a>?');
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
		<a href="/admin/configurecontent/types/duplicate/{$contentTypes[$i]['id']}">
			<div class="duplicate">duplicate</div>
		</a>
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
				$exec=strtolower(str_replace
				(
					array('application\\','\\'),
					array('','.'),
					$this->request->get('widget')
				)).'.Config';
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
		
		public function generateRolesList($contentTypeId=null)
		{
			$return='';
			if (is_numeric($contentTypeId))
			{
				$contentTypeRoles=$this->model->ContentTypeRole->read(array('content_type_id'=>$contentTypeId));
			}
			$roles	=$this->model->Role->read();
			$html	=array();
			for ($i=0,$j=count($roles); $i<$j; $i++)
			{
				$checked='';
				if (isset($contentTypeRoles))
				{
					$checked=($this->contentTypeHasRole($contentTypeRoles,$roles[$i]['id']))?'checked':'';
				}
				$html[]=<<<HTML
<tr>
	<td class=""><input type="checkbox" name="role[{$roles[$i]['id']}]" value="1" {$checked}></td>
	<td class="">{$roles[$i]['name']}</td>
	<td class="">{$roles[$i]['description']}</td>
</tr>
HTML;
			}
			$return=implode('',$html);
			return $return;
		}
		
		private function contentTypeHasRole($contentTypeRoles,$roleID)
		{
			for ($i=0,$j=count($contentTypeRoles); $i<$j; $i++)
			{
				if ($contentTypeRoles[$i]['role_id']==$roleID)
				{
					return true;
				}
			}
			return false;
		}
		
		public function generateUserList($contentTypeId=null)
		{
			$return='';
			if (is_numeric($contentTypeId))
			{
				$contentTypeUsers=$this->model->ContentTypeUser->read(array('content_type_id'=>$contentTypeId));
			}
			$users	=$this->model->User->read();
			$html	=array();
			for ($i=0,$j=count($users); $i<$j; $i++)
			{
				$checked='';
				if (isset($contentTypeUsers))
				{
					$checked=($this->contentTypeHasUser($contentTypeUsers,$users[$i]['id']))?'checked':'';
				}
				$html[]=<<<HTML
<tr>
	<td class=""><input type="checkbox" name="user[{$users[$i]['id']}]" value="1" {$checked}></td>
	<td class="">{$users[$i]['email']}</td>
	<td class="">{$users[$i]['name_first']} {$users[$i]['name_last']}</td>
</tr>
HTML;
			}
			$return=implode('',$html);
			return $return;
		}
		
		private function contentTypeHasUser($contentTypeUsers,$roleID)
		{
			for ($i=0,$j=count($contentTypeUsers); $i<$j; $i++)
			{
				if ($contentTypeUsers[$i]['user_id']==$roleID)
				{
					return true;
				}
			}
			return false;
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
				case 'download':
				{
					$this->downloadFormRecords($id);
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
					$this->plugin->Notification->setSuccess('Form successfully added. Would you like to <a href="/admin/configurecontent/forms/add/">Add another one?</a>');
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
		
		private function removeForm($id)
		{
			if ($this->model->Form->delete(array('id'=>$id)))
			{
				$this->plugin->Notification->setSuccess('Form successfully removed.');
				$this->redirect('/admin/configurecontent/forms/');
			}
			else
			{
				$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
			}
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
		<div class="news-text"><a href="/admin/configurecontent/forms/download/{$forms[$i]['id']}"><i class="icon-download"></i>&nbsp;<b>Download New Records</b></a></div>
	</div>
</div>
HTML;
			}
			return implode('',$html);
		}
		
		
		private function downloadFormRecords($id)
		{
			$form		=$this->model->Form->read($id);
			if (isset($form[0]))
			{
				$records	=$this->model->FormSubmission->exportNewRecords($id);
				if (count($records))
				{
					$headers	=$this->getHeaders($records);
					$fileName	=$form[0]['ref'].'_'.time().'_.csv';
					$CSV=$this->plugin->Format('CSV');
					$CSV->set_base_dir(APP_HOME.'nutsNBolts'._DS_.'data'._DS_);
					$CSV->setHeaders($headers);
					$CSV->new_file($fileName);
					$CSV->process($records);
					$CSV->close_file();
					$this->plugin	->Responder('csv')
									->forceDownload($fileName)
									->setData($CSV->read())
									->send();
				}
				else
				{
					$this->plugin->Notification->setInfo('Sorry, no new records found for export.');
				}
			}
			else
			{
				$this->plugin->Notification->setError('Cannot export from invalid form.');
			}
			$this->redirect('/admin/configurecontent/forms/');
		}
		
		private function getHeaders(&$records)
		{
			$headers=array();
			for ($i=0,$j=count($records); $i<$j; $i++)
			{
				foreach ($records[$i] as $header=>$value)
				{
					if (!in_array($header,$headers))
					{
						$headers[]=$header;
					}
				}
			}
			return $headers;
		}

		private function duplicateContentType($id)
		{
			$roles=array();
			$thisContentType=$this->model->ContentType->read(array('id'=>$id));

			if(isset($thisContentType[0]['id']))
			{
				$thisContentType[0]['name'].= " (copy)";	
				unset($thisContentType[0]['id']);

				$roles['roles']=$thisContentType[0]['roles'];
				$roles['users']=$thisContentType[0]['users'];

				unset($thisContentType[0]['roles']);
				unset($thisContentType[0]['users']);					
			}
			
			$duplicatedContentType=$thisContentType[0];
			$contentTypeId=$this->model->ContentType->insert($duplicatedContentType);

			$thisContentPart=$this->model->ContentPart->read(array('content_type_id'=>$id));
			for($i=0;$i<count($thisContentPart); $i++)
			{
				unset($thisContentPart[$i]['id']);
				$thisContentPart[$i]['content_type_id']=$contentTypeId;
			}
// print_r($duplicatedContentType);
			if($roles['roles'] > 0)
			{

				$role=array
				(
					'content_type_id'		=>$contentTypeId,
					'role_id'				=>$roles['roles'][0]['id']
				);

				

				$user=array
				(
					'content_type_id'		=>$contentTypeId,
					'user_id'				=>$roles['users'][0]['id']
				);

				foreach ($roles['roles'] AS $role)
				{
					$role=array
					(
						'content_type_id'		=>$contentTypeId,
						'role_id'				=>$role['id']
					);
					$this->model->ContentTypeRole->insert($role);

				}

				foreach ($roles['users'] AS $user)
				{
					$user=array
					(
						'content_type_id'		=>$contentTypeId,
						'user_id'				=>$user['id']
					);
					$this->model->ContentTypeUser->insert($user);
					
				}				
			}
			$this->redirect('/admin/configureContent/types/edit/'.$contentTypeId);
			die();
		}
	}
}
?>