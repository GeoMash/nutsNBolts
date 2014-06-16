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
				$this->plugin->Auth->can('admin.permissions.role.read');
				
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Permissions','icon-bolt','permissions');
				
				$this->setContentView('admin/settings/permissions/roles');
				
				$this->view->getContext()
					->registerCallback
					(
						'getRoles',
						function()
						{
							return $this->getRoles(null);
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
		
		public function addRole()
		{
			try
			{
				$this->plugin->Auth->can('admin.permissions.role.create');
				
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Permissions','icon-bolt','permissions');
				$this->addBreadcrumb('Add Role','icon-pencil','editRole');
				
				if ($this->request->get('name'))
				{
					$record=$this->request->getAll();
					if ($id=$this->model->Role->handleRecord($record)!==false)
					{
						var_dump($id);exit();
						$this->plugin->Notification->setSuccess('Role successfully added. Would you like to <a href="/admin/settings/permissions/addRole/">Add another one?</a>');
						$this->redirect('/admin/settings/permissions/editRole/'.$id);
					}
					else
					{
						$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
					}
				}
				
				$this->setupAddEditRole();
			}
			catch (AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		public function editRole($id)
		{
			try
			{
				$this->plugin->Auth	->can('admin.permissions.role.read')
									->can('admin.permissions.role.update');
				if ($this->request->get('id'))
				{
					$record=$this->request->getAll();
					if ($role=$this->model->Role->handleRecord($record)!==false)
					{
						$this->plugin->Notification->setSuccess('Role successfully edited.');
					}
					else
					{
						$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
					}
				}
				
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Permissions','icon-bolt','permissions');
				$this->addBreadcrumb('Edit Role','icon-pencil','editRole');
				
				if ($record=$this->model->Role->read($id))
				{
					$this->view->setVars($record[0]);
				}
				$this->setupAddEditRole();
			}
			catch (AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		private function setupAddEditRole()
		{
			$this->setContentView('admin/settings/permissions/addEditRole');
			
			$this->view->getContext()
				->registerCallback
				(
					'getPermissionTable',
					function()
					{
						return $this->getPermissionTable(null);
					}
				);
			
			$renderRef='editRole';
			$this->execHook('onBeforeRender',$renderRef);
			
			$this->view->render();
		}
		
		public function removeRole($id)
		{
			
			try
			{
				$this->plugin->Auth->can('admin.role.delete');
				if ($this->model->Role->handleDeleteRecord($id))
				{
					$this->plugin->Notification->setSuccess('Role successfully removed.');
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
		
		public function getRoles()
		{
			return $this->model->Role->read();
		}
		
		public function getPermissionTable()
		{
			$roleId=$this->request->lastNode();
			$permissions=$this->model->Permission->read();
			$rolePermissions=$this->model->PermissionRole->read(['role_id'=>$roleId]);
			for ($i=0,$j=count($permissions); $i<$j; $i++)
			{
				$permissions[$i]['permit']=$this->isPermitted($permissions[$i]['id'],$rolePermissions);
			}
			return $permissions;
		}
		
		private function isPermitted($id,&$rolePermissions)
		{
			for ($i=0,$j=count($rolePermissions); $i<$j; $i++)
			{
				if ($rolePermissions[$i]['permission_id']==$id)
				{
					return true;
				}
			}
			return false;
		}
	}
}
?>