<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;

	class ConfigurePages extends AdminController
	{
		public function index()
		{
			$this->show404();
			$this->view->render();
		}
		
		public function types($action=null,$id=null)
		{
			$this->addBreadcrumb('Configure Pages','icon-cogs','configurepages');
			$this->addBreadcrumb('Types','icon-th-large','types');
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
					);
					$this->editType($id);
					break;
				}
				case 'remove':	$this->removeType($id);	break;
				default:
				{
					try
					{
						$this->plugin->Auth->can('admin.pageType.read');
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
					catch(AuthException $exception)
					{
						$this->setContentView('admin/noPermission');
						$this->view->render();
					}
				}
			}
			$this->view->render();
		}
		
		public function pages($action=null,$id=null)
		{
			$this->addBreadcrumb('Configure Pages','icon-cogs','configurepages');
			$this->addBreadcrumb('Pages','icon-copy','pages');
			if ($this->model->PageType->count())
			{
				switch ($action)
				{
					case 'add':
					{
						$this->addPage();
						break;
					}
					case 'edit':
					{
						$this->editPage($id);
						break;
					}
					case 'remove':	$this->removePage($id);	break;
					default:
					{
						try
						{
							$this->plugin->Auth->can('admin.page.read');
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
						catch(AuthException $exception)
						{
							$this->setContentView('admin/noPermission');
							$this->view->render();
						}
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
			}
			else
			{
				$this->plugin->Notification->setInfo('Hey there! I noticed you haven\'t created a page type yet. <a href="/admin/configurepages/types/add">Click here to create one.</a>');
				$this->setContentView('admin/blank');
			}
			
			$this->view->render();
		}
		
		private function addType()
		{
			try
			{
				$this->plugin->Auth->can('admin.pageType.create');
				if (!$this->request->get('name'))
				{
					$this->addBreadcrumb('Add Page','icon-copy','add');
					$this->setContentView('admin/configurePages/addEditType');
				}
				else
				{
					$record=$this->request->getAll();
					$record['site_id']=$this->getSiteId();
					if ($id=$this->model->PageType->handleRecord($record))
					{
						$this->plugin->Notification->setSuccess('Page type successfully added. Would you like to <a href="/admin/configurepages/types/add/">Add another one?</a>');
						$this->redirect('/admin/configurepages/types/edit/'.$id);
					}
					else
					{
						$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
					}
				}
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		private function addPage()
		{
			try
			{
				$this->plugin->Auth->can('admin.page.create');
				// MD we dont have field with "name", checking the next best thing which is URL
				if (!$this->request->get('url'))
				{
					$this->addBreadcrumb('Add Page','icon-plus','add');
					$this->setContentView('admin/configurePages/addEditPage');
				}
				else
				{
					$record=$this->request->getAll();
					$record['site_id']=$this->getSiteId();
					if ($id=$this->model->Page->handleRecord($record))
					{
						$this->plugin->Notification->setSuccess('Page successfully added. Would you like to <a href="/admin/configurepages/pages/add/">Add another one?</a>');
						$this->redirect('/admin/configurepages/pages/edit/'.$id);
					}
					else
					{
						$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
					}
				}
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		private function editType($id)
		{
			try
			{
				$this->plugin->Auth->can('admin.pageType.read');
				$this->plugin->Auth->can('admin.pageType.update');
				if ($this->request->get('id'))
				{
					if ($this->model->PageType->handleRecord($this->request->getAll())!==false)
					{
						$this->plugin->Notification->setSuccess('Page type successfully edited.');
					}
					else
					{
						$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
					}
				}
				$this->addBreadcrumb('Edit Page Type','icon-edit','edit/'.$id);
				$this->setContentView('admin/configurePages/addEditType');
				$this->view->setVar('pageUrls',array());
				if ($record=$this->model->PageType->read($id))
				{
					$this->view->setVars($record[0]);
				}
				else
				{
					$this->view->setVar('record',array());
				}
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		private function editPage($id)
		{
			try
			{
				$this->plugin->Auth->can('admin.page.read');
				$this->plugin->Auth->can('admin.page.update');
				if ($this->request->get('id'))
				{
					if ($this->model->Page->handleRecord($this->request->getAll())!==false)
					{
						$this->plugin->Notification->setSuccess('Page successfully edited.');
					}
					else
					{
						$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
					}
				}
				$pageURLs	=$this->model->PageMap->read(array('page_id'=>$id));
	
				$this->addBreadcrumb('Edit Page','icon-edit','edit/'.$id);
				$this->setContentView('admin/configurePages/addEditPage');
				$this->view->setVar('pageUrls',$pageURLs);
				if ($record=$this->model->Page->read($id))
				{
					$this->view->setVars($record[0]);
				}
				else
				{
					$this->view->setVar('record',array());
				}
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		public function removeType($id)
		{
			try
			{
				$this->plugin->Auth->can('admin.pageType.delete');
				if ($this->model->PageType->handleDeleteRecord($id))
				{
					$this->plugin->Notification->setSuccess('Page type successfully removed.');
					$this->redirect('/admin/configurepages/types/');
				}
				else
				{
					$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
				}
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		public function removePage($id)
		{
			try
			{
				$this->plugin->Auth->can('admin.page.delete');
				if ($this->model->Page->handleDeleteRecord($id))
				{
					$this->plugin->Notification->setSuccess('Page successfully removed.');
					$this->redirect('/admin/configurepages/pages/');
				}
				else
				{
					$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
				}
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
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
		
		public function generateRolesList($contentTypeId=null)
		{
			$return				='';
			$contentTypeRoles	=array();
			if (is_numeric($contentTypeId))
			{
				$contentTypeRoles=$this->model->ContentTypePermission->read(array('content_type_id'=>$contentTypeId));
			}
			$roles	=$this->model->Role->read();
			$html	=array();
			for ($i=0,$j=count($roles); $i<$j; $i++)
			{
				$checked='';
				if (isset($userRoles))
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
		
		private function contentTypeHasRole($userRoles,$roleID)
		{
			for ($i=0,$j=count($userRoles); $i<$j; $i++)
			{
				if ($userRoles[$i]['role_id']==$roleID)
				{
					return true;
				}
			}
			return false;
		}
		
		
		
	}
}
?>