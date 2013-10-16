<?php
namespace application\nutsNBolts\plugin\userAuth
{
	use application\nutsNBolts\NutsNBolts;
	use nutshell\Nutshell;
	use nutshell\core\plugin\Plugin;
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use application\nutsNBolts\model\User;
	use application\nutsNBolts\plugin\userAuth\UserAuthException;

	
	/**
	 * This class implements application plugin allowing models to be used.
	 * Application plugins don't have dependencies nor behaviours.
	 */
	class UserAuth extends Plugin implements Native,Singleton 
	{
		private $user=null;
		// protected $vModelLoader = null;
		
		public static function loadDependencies()
		{
			// require_once('AppPluginExtension.php');
		}
		
		public static function registerBehaviours()
		{
		
		}
		
		public function init()
		{
			if ($connection=Nutshell::getInstance()->config->plugin->Mvc->connection)
			{
				$this->db=$this->plugin->Db->{$connection};
			}
			$this->user=$this->plugin->Mvc->model->User->read($this->plugin->Session->userId)[0];
		}
		
		private function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			
			if (!empty($record['password']))
			{
				$this->generateSalt($record);
				$record['password']=md5(Nutshell::getInstance()->config->application->salt.$record['salt'].$record['password']);
			}
			
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				if (empty($record['password']))
				{
					unset($record['password']);
				}
				// var_dump($record);exit();
				$roles	=$this->extractRoles($record);
				$return	=$this->plugin->Mvc->model->User->update($record,array('id'=>$record['id']));
				$this->plugin->Mvc->model->UserRole->delete(array('user_id'=>$record['id']));
				
				for ($i=0,$j=count($roles); $i<$j; $i++)
				{
					$this->plugin->Mvc->model->UserRole->insert($roles[$i]);
				}
				return $return;
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$record['date_created']		=date('Y-m-d H:i:s');
				$record['date_lastlogin']	='0000-00-00 00:00:00';
				$record['date_lastactive']	='0000-00-00 00:00:00';
				
				$roles=$this->extractRoles($record);
				if ($id=$this->plugin->Mvc->model->User->insertAssoc($record))
				{
					for ($i=0,$j=count($roles); $i<$j; $i++)
					{
						$roles[$i]['user_id']=$id;
						$this->plugin->Mvc->model->UserRole->insertAssoc($roles[$i]);
					}
					return $id;
				}
			}
			return false;
		}

		private function extractRoles(&$record)
		{
			if (isset($record['role']))
			{
				$roles=array();
				$id=(!empty($record['id']))?$record['id']:0;
				foreach ($record['role'] as $roleID=>$enabled)
				{
					$roles[]=array
					(
						'user_id'	=>$id,
						'role_id'	=>$roleID
					);
				}
				unset($record['role']);
				return $roles;
			}
			return array();
		}
		
		private function generateSalt(&$record)
		{
			$record['salt']=sha1('wheretoparty_ce1833cca4627da0751a2dcdde1f0b3b_'.time());
		}

		public function addUser(array $array)
		{
			try
			{
				if (!$array['email'] || !$array['password'] || !$array['password_confirm'])
				{
					throw new UserAuthException(UserAuthException::REQUIRED_FIELD_MISSING);
				}
				else
				{
					if ($array['password']!=$array['password_confirm'])
					{
						throw new UserAuthException(UserAuthException::PASSWORDS_DO_NOT_MATCH);
					}
					
					unset($array['password_confirm']);
					
					if($this->handleRecord($array))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			}
			catch(NutshellException $e)
			{
				throw new UserAuthException(UserAuthException::REQUEST_FAILED, $this->plugin->Logger()->warn($e->__toString()));
			}
			
		}

		public function editUser(array $array)
		{
			if ($array['email'])
			{
				if ($array['password']!=$array['password_confirm'])
				{
					throw new UserAuthException
					(
						UserAuthException::PASSWORDS_DO_NOT_MATCH,
						$this->plugin->Logger()->warn(UserAuthException::PASSWORDS_DO_NOT_MATCH)
					);
				}

				unset($array['password_confirm']);

				if ($this->handleRecord($array)!==false)
				{
					return $this->plugin->Mvc->model->User->read($array['id']);
				}
				else
				{
					throw new UserAuthException
					(
						UserAuthException::REQUEST_FAILED,
						$this->plugin->Logger()->warn(UserAuthException::REQUEST_FAILED)
					);
				}
			}
		}

		public function generateUserList()
		{
			return $this->plugin->Mvc->model->User->read();

		}

		public function generateRolesList($userId=null)
		{
			$return='';
			if (is_numeric($userId))
			{
				$userRoles=$this->plugin->Mvc->model->UserRole->read(array('user_id'=>$userId));
			}
			$roles	=$this->plugin->Mvc->model->Role->read();
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
		
		public function userHasRole($userRoles,$roleID)
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

		public function authenticate($fieldName,$fieldValue,$password)
		{
			$user		=$this->plugin->Mvc->model->User->read(array($fieldName=>$fieldValue));
			
			if (isset($user[0]))
			{
				$userSalt	=$user[0]['salt'];
				$systemSalt	=Nutshell::getInstance()->config->application->salt;
				$email		=$user[0]['email'];
				$result		=$this->plugin->Mvc->model->User->read
				(
					array
					(
						'email'			=>$email,
						'password'		=>md5($systemSalt.$userSalt.$password),
						'status'		=>1
					)
				);
				if (isset($result[0]))
				{
					return $result[0];
				}
			}
			return false;
		}

		public function getUser()
		{
			return $this->user;
		}

		public function getUserId()
		{
			return $this->user['id'];
		}

		public function isAuthenticated()
		{
			return (bool)($this->plugin->Session->authenticated);
		}

		public function isSuper()
		{
			$user=$this->getUser();
			for ($i=0,$j=count($user['roles']); $i<$j; $i++)
			{
				if ($user['roles'][$i]['id']==NutsNBolts::USER_SUPER)
				{
					return true;
				}
			}
			return false;
		}
	}
}
