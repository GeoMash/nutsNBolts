<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
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
			}
			// $this->setContentView('admin/settings/users');
			
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
				}
				else if (empty($record['password']))
				{
					$this->plugin->Notification->setError('Password cannot be blank.');
				}
				unset($record['password_confirm']);
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
		
		private function editUser($id)
		{
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