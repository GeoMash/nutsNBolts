<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use application\nutsNBolts\plugin\plupload\ThumbnailMaker;
	use nutshell\core\exception\NutshellException;
	use nutshell\helper\ObjectHelper;
	
	class Settings extends AdminController
	{
		private $collectionID=null;
		
		public function index()
		{
			$this->show404();
			$renderRef='index';
			$this->execHook('onBeforeRender',$renderRef);
			$this->view->render();
		}
		
		public function nnb()
		{
			try
			{
				$this->plugin->Auth	->can('admin.setting.nutsNBolts.create')
									->can('admin.setting.nutsNBolts.read');
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Nuts n Bolts Settings','icon-circle-blank','nnb');
				
				$renderRef='nnb';
				$this->view->setVar('extraOptions',array());
				$this->execHook('onBeforeRender',$renderRef);
				$this->view->render();
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		public function site()
		{
			try
			{
				$this->plugin->Auth	->can('admin.setting.site.create')
									->can('admin.setting.site.read')
									->can('admin.setting.site.update')
									->can('admin.setting.site.delete');
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Site Settings','icon-circle','site');
				$this->setContentView('admin/settings/siteSettings');
				
				if ($this->request->get('key'))
				{
					$keys	=$this->request->get('key');
					$values	=$this->request->get('value');
					$records=[];
					for ($i=0,$j=count($keys); $i<$j; $i++)
					{
						$records[]=
						[
							'label'	=>$keys[$i],
							'key'	=>$keys[$i],
							'value'	=>$values[$i]
						];
					}
					$this->model->SiteSettings->insertAll($records);
					$this->plugin->Notification->setSuccess('Settings saved.');
				}
				
				$settings=$this->model->SiteSettings->read();
				
				$this->view->setVar('settings',$settings);
				$renderRef='site';
				$this->view->setVar('extraOptions',array());
				$this->execHook('onBeforeRender',$renderRef);
				$this->view->render();
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		public function users($action=null,$id=null)
		{
			$this->addBreadcrumb('System Settings','icon-wrench','settings');
			$this->addBreadcrumb('Users','icon-user','users');
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
					$this->addUser();
					$renderRef='users/add';
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
					$this->editUser($id);
					$renderRef='users/edit';
					break;
				}
				case 'remove':
				{
					$this->removeUser($id);
					$renderRef='users/remove';
					break;
				}
				//View
				default:
				{
					try
					{
						$this->plugin->Auth->can('admin.user.read');
						$this->setContentView('admin/settings/users');
						$this->view->getContext()
						->registerCallback
						(
							'getUserList',
							function()
							{
								print $this->generateUserList();
							}
						);
						$renderRef='users';
					}
					catch(AuthException $exception)
					{
						$this->setContentView('admin/noPermission');
						$this->view->render();
					}
				}
			}
			// $this->setContentView('admin/settings/users');
			
			//TODO: Remove this project specific callback.
			$this->view->getContext()
			->registerCallback
			(
				'generateBarList',
				function() use ($id)
				{
					print $this->generateBarList($id);
				}
			);
			
			$this->view->setVar('extraOptions',array());
			$this->execHook('onBeforeRender',$renderRef);
			$this->view->render();
		}
		
		private function addUser()
		{
			try
			{
				$this->plugin->Auth->can('admin.user.create');
				$this->plugin->Auth->can('admin.collection.create');
				if (!$this->request->get('email'))
				{
					$this->addBreadcrumb('Add User','icon-plus','add');
					$this->setContentView('admin/settings/addEditUser');
				}
				else
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
					else
					{
						// has errors
						$this->addBreadcrumb('Add User','icon-plus','add');
						$this->setContentView('admin/settings/addEditUser');
					}
				}
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		private function editUser($id)
		{
			try
			{
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
				$this->addBreadcrumb('Edit User','icon-edit','edit/'.$id);
				$this->setContentView('admin/settings/addEditUser');
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
				$this->view->render();
			}
		}

		private function removeUser($id)
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
		
		public function generateUserList()
		{
			$records=$this->model->User->read();
			$html	=array();
			for ($i=0,$j=count($records); $i<$j; $i++)
			{
				$html[]=<<<HTML
<tr>
	<td class=""><a href="/admin/settings/users/edit/{$records[$i]['id']}">{$records[$i]['email']}</a></td>
	<td class="">{$records[$i]['name_first']} {$records[$i]['name_last']}</td>
	<td class="">{$records[$i]['date_lastlogin']}</td>
	<td class="">{$records[$i]['status']}</td>
	<td class="center">
		<a href="/admin/settings/users/remove/{$records[$i]['id']}">
			<button title="Archive" class="btn btn-mini btn-red">
				<i class="icon-remove"></i>
			</button>
		</a>
	</td>
</tr>
HTML;
			}
			return implode('',$html);
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