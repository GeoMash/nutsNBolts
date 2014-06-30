<?php
namespace application\nutsNBolts\controller\admin\settings
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use application\nutsNBolts\plugin\plupload\ThumbnailMaker;
	use nutshell\core\exception\NutshellException;
	use nutshell\helper\ObjectHelper;
	
	class Permissions extends AdminController
	{
		public function index()
		{
			try
			{
				$this->plugin->Auth->can('admin.permission.read');
				
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Permissions','icon-bolt','permissions');
				
				$this->setContentView('admin/settings/permissions/list');
				
				$this->view->getContext()
					->registerCallback
					(
						'getPermissions',
						function()
						{
							return $this->getPermissions();
						}
					);
				
				$renderRef='permissions';
				$this->execHook('onBeforeRender',$renderRef);
				$this->view->render();
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		public function add()
		{
			try
			{
				$this->plugin->Auth->can('admin.permission.create');
				
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Permissions','icon-bolt','permissions');
				$this->addBreadcrumb('Add Permission','icon-plus','add');
				
				if ($this->request->get('name'))
				{
					$record=$this->request->getAll();
					if (($id=$this->model->Permission->handleRecord($record))!==false)
					{
						$this->plugin->Notification->setSuccess('Permission successfully added. Would you like to <a href="/admin/settings/permissions/add/">Add another one?</a>');
						$this->redirect('/admin/settings/permissions/edit/'.$id);
					}
					else
					{
						$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
					}
				}
				
				$this->setupAddEdit();
			}
			catch (AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		public function edit($id)
		{
			try
			{
				$this->plugin->Auth	->can('admin.permission.read')
									->can('admin.permission.update');
				if ($this->request->get('id'))
				{
					$record=$this->request->getAll();
					if ($this->model->Permission->handleRecord($record)!==false)
					{
						$this->plugin->Notification->setSuccess('Permission successfully edited.');
					}
					else
					{
						$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
					}
				}
				
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Permissions','icon-bolt','permissions');
				$this->addBreadcrumb('Edit Permission','icon-pencil','edit');
				
				if ($record=$this->model->Permission->read($id))
				{
					$this->view->setVars($record[0]);
				}
				$this->setupAddEdit();
			}
			catch (AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		private function setupAddEdit()
		{
			$this->setContentView('admin/settings/permissions/addEdit');
			
			$renderRef='editPermission';
			$this->execHook('onBeforeRender',$renderRef);
			
			$this->view->render();
		}
		
		public function remove($id)
		{
			
			try
			{
				$this->plugin->Auth->can('admin.permission.delete');
				if ($this->model->Permission->delete($id))
				{
					$this->plugin->Notification->setSuccess('Permission successfully removed.');
				}
				else
				{
					$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
				}
				$this->redirect('/admin/settings/permissions/');
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		public function getPermissions()
		{
			return $this->model->Permission->read();
		}
	}
}
?>