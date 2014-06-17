<?php
namespace application\nutsNBolts\controller\admin\settings
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\Auth;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use application\nutsNBolts\plugin\plupload\ThumbnailMaker;
	use nutshell\core\exception\NutshellException;
	use nutshell\helper\ObjectHelper;
	
	class Users extends AdminController
	{
		public function index()
		{
			try
			{
				$this->plugin->Auth->can('admin.user.read');
				
				$this->addBreadcrumb('System Settings','icon-wrench','settings');
				$this->addBreadcrumb('Users','icon-user','users');
				
				$this->setContentView('admin/settings/users/list');
				$this->view->getContext()
				->registerCallback
				(
					'getUsers',
					function()
					{
						return $this->getUsers();
					}
				);
				
				$renderRef='users';
				$this->view->setVar('extraOptions',array());
				$this->execHook('onBeforeRender',$renderRef);
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
			}
			$this->view->render();
		}
		
		public function add()
		{
			try
			{
				$this->addBreadcrumb('System Settings','icon-wrench','settings');
				$this->addBreadcrumb('Users','icon-user','users');
				$this->addBreadcrumb('Add','icon-plus','add');
				
				$this->plugin->Auth->can('admin.user.create');
				$this->plugin->Auth->can('admin.collection.create');
				
				if ($this->request->get('email'))
				{
					$record=$this->request->getAll();
					if ($record['password']!=$record['password_confirm'])
					{
						$this->plugin->Notification->setError('Passwords did not match. Please try again.');
						$error=true;
					}
					else if (empty($record['password']))
					{
						$this->plugin->Notification->setError('Password cannot be blank.');
						$error=true;
					}
	
					$this->execHook('onBeforeAddUser',$record);
					unset($record['password_confirm']);
					if(!$record['error'])
					{
						// no errors
						if ($user=$this->model->User->handleRecord($record))
						{
							$this->execHook('onAddUser',$user);
							$this->plugin->Notification->setSuccess('User successfully added. Would you like to <a href="/admin/settings/users/add/">Add another one?</a>');
	
							try
							{
								$this->plugin->Collection->create
								(
									array
									(
										'name'			=>'My Files',
										'description'	=>'User Collection',
										'status'		=>1
									),
									$user['id']
								);
							}
							catch(NutshellException $exception)
							{
								$this->plugin->Notification->setError($exception->getMessage());
							}
							$this->redirect('/admin/settings/users/edit/'.$user['id']);
						}
						else
						{
							$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
						}
					}
				}
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
			}
			$renderRef='users/add';
			$this->setupAddEdit($renderRef);
		}
		
		public function edit($id)
		{
			try
			{
				$this->addBreadcrumb('System Settings','icon-wrench','settings');
				$this->addBreadcrumb('Users','icon-user','users');
				$this->addBreadcrumb('Edit','icon-edit','edit/'.$id);
				
				$this->plugin->Auth	->can('admin.user.read')
									->can('admin.user.update');
				
				if ($this->request->get('email'))
				{
					$record=$this->request->getAll();
					if ($record['password']!=$record['password_confirm'])
					{
						$this->plugin->Notification->setError('Passwords did not match. Please try again.');
					}
					unset($record['password_confirm']);
					if ($user=$this->model->User->handleRecord($record))
					{
						$this->execHook('onEditUser',$user);
						$this->plugin->Notification->setSuccess('User successfully edited.');
					}
					else
					{
						$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
					}
				}
				
				if ($record=$this->model->User->read($id))
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
			}
			$renderRef='users/edit';
			$this->setupAddEdit($renderRef);
		}
		
		public function remove($id)
		{
			try
			{
				$this->plugin->Auth->can('admin.user.delete');
				if ($id==-100)
				{
					$this->plugin->Notification->setError('The system super user cannot be removed.');
					$this->redirect('/admin/settings/users/');
				}
				if ($id==$this->getUserId())
				{
					$this->plugin->Notification->setError('You cannot remove yourself.');
					$this->redirect('/admin/settings/users/');
				}
	
				if ($this->model->User->handleDeleteRecord($id))
				{
					$this->plugin->Notification->setSuccess('User successfully removed.');
					//TODO: Remove collection items? Needs discussion.
					$this->redirect('/admin/settings/users/');
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
		
		public function impersonate($id)
		{
			try
			{
				$this->plugin->Auth->can('admin.user.impersonate');
				if ($id==Auth::USER_SUPER)
				{
					$this->plugin->Notification->setError('You cannot impersonate root.');
					$this->redirect('/admin/settings/users/');
				}
				if ($id==$this->getUserId())
				{
					$this->plugin->Notification->setError('You cannot impersonate yourself.');
					$this->redirect('/admin/settings/users/');
				}
				if (is_numeric($id))
				{
					$this->plugin->Auth->startImpersonating($id);
					$this->plugin->Notification->setSuccess('You\'ve started impersonating a user. Click the button in the nav to stop.');
				}
				else
				{
					$this->plugin->Notification->setError('Invalid User! Unable to impersonate this.');
				}
				$this->redirect('/admin/settings/users/');
			}
			catch(AuthException $exception)
			{
				$this->plugin->Notification->setError('Nope, you don\'t have permission to impersonate other users!');
			}
			
		}
		
		private function setupAddEdit(&$renderRef)
		{
			$this->setContentView('admin/settings/users/addEdit');
			
			$this->view->getContext()
				->registerCallback
				(
					'getUserRoles',
					function()
					{
						$id=$this->request->lastNode();
						print $this->generateRolesList($id);
					}
				);
			
			$this->view->setVar('extraOptions',array());
			$this->execHook('onBeforeRender',$renderRef);
			$this->view->render();
		}
		
		public function getUsers()
		{
			return $this->model->User->read();
		}
		
		public function generateRolesList($userId=null)
		{
			$return='';
			if (is_numeric($userId))
			{
				$userRoles=$this->model->UserRole->read(array('user_id'=>$userId));
			}
			$roles	=$this->model->Role->read();
			$html	=array();
			for ($i=0,$j=count($roles); $i<$j; $i++)
			{
				if ($roles[$i]['id']==-100)
				{
					continue;
				}
				$checked='';
				if (isset($userRoles))
				{
					$checked=($this->userHasRole($userRoles,$roles[$i]['id']))?'checked':'';
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
		
		private function userHasRole($userRoles,$roleID)
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