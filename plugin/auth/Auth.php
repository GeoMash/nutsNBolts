<?php
namespace application\nutsNBolts\plugin\auth
{
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use nutshell\Nutshell;
	use application\nutsNBolts\base\Plugin;
	use application\nutsNBolts\NutsNBolts;

	class Auth extends Plugin implements Singleton, Native
	{
		const USER_SUPER				=-100;
		
		private $user					=null;
		private $originalUser			=null;
		private $impersonating			=false;
		private $permissionFullMatrix	=null;
		private $permissionKeyMatrix	=null;

		public static function registerBehaviours(){}
		
		public function init()
		{
			if ($connection=Nutshell::getInstance()->config->plugin->Mvc->connection)
			{
				$this->db=$this->plugin->Db->{$connection};
			}
			if ($this->isAuthenticated())
			{
				$this->impersonating	=$this->plugin->Session->impersonating;
				$this->user				=$this->plugin->Mvc->model->User->read($this->plugin->Session->userId)[0];
				$this->originalUser		=$this->plugin->Mvc->model->User->read($this->plugin->Session->originalUserId)[0];
				$this->generateUserPermissionsMatrix();
			}
		}
		
		public function authenticate($email,$password)
		{
			$user=$this->plugin->Mvc->model->User->read(['email'=>$email]);
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
					$this->plugin->Session->authenticated	=true;
					$this->plugin->Session->impersonating	=false;
					$this->plugin->Session->userId			=$user[0]['id'];
					$this->plugin->Session->originalUserId	=$user[0]['id'];
					$this->user								=$user[0];
					$this->originalUser						=$user[0];
					$this->generateUserPermissionsMatrix();
					return $user[0];
				}
				else
				{
					throw new AuthException(AuthException::INVALID_PASSWORD,'Invalid login.');
				}
			}
			else
			{
				throw new AuthException(AuthException::INVALID_EMAIL,'Sorry, email not registered.');
			}
		}
		
		public function authenticateUser($userId)
		{
			$user=$this->plugin->Mvc->model->User->read(['id'=>$userId]);
			if (isset($user[0]))
			{
				$this->plugin->Session->authenticated	=true;
				$this->plugin->Session->impersonating	=false;
				$this->plugin->Session->userId			=$user[0]['id'];
				$this->plugin->Session->originalUserId	=$user[0]['id'];
			}
			else
			{
				throw new AuthException(AuthException::INVALID_USER_ID,'No user with this ID found.');
			}
		}

		public function unauthenticate()
		{
			$session=Nutshell::getInstance()->plugin->Session;
			unset($session->authenticated);
			unset($session->userId);
			$session->destroy();
		}
		
		public function startImpersonating($userId)
		{
			$user=$this->plugin->Mvc->model->User->read(['id'=>$userId]);
			if (isset($user[0]))
			{
				$this->impersonating					=true;
				$this->plugin->Session->impersonating	=true;
				$this->plugin->Session->userId			=$user[0]['id'];
				$this->user								=$user[0];
				$this->generateUserPermissionsMatrix();
			}
			return $this;
		}
		
		public function stopImpersonating()
		{
			$this->impersonating					=false;
			$this->plugin->Session->impersonating	=false;
			$this->plugin->Session->userId			=$this->plugin->Session->originalUserId;
			$this->user								=$this->originalUser;
			$this->generateUserPermissionsMatrix();
			return $this;
		}
		
		public function isImpersonating()
		{
			return $this->impersonating;
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
			return isset(Nutshell::getInstance()->plugin->Session->userId);
		}

		public function isSuperUser()
		{
			$user=$this->getUser();
			for ($i=0,$j=count($user['roles']); $i<$j; $i++)
			{
				if ($user['roles'][$i]['id']==self::USER_SUPER)
				{
					return true;
				}
			}
			return false;
		}
		
		public function getUserRoles()
		{
			return $this->getUser()['roles'];
		}
		
		public function hasRole($roles)
		{
			if (is_null($roles) || $this->isSuperUser())
			{
				return true;
			}
			if (!is_array($roles))
			{
				$roles=[$roles];
			}
			$userRoles=$this->getUserRoles();
			for ($i=0,$j=count($roles); $i<$j; $i++)
			{
				for ($k=0,$l=count($userRoles); $k<$l; $k++)
				{
					if ($roles[$i]['id']==$userRoles[$i]['id'])
					{
						return true;
					}
				}
			}
			return false;
		}
		
		public function generateUserPermissionsMatrix()
		{
			if (is_null($this->permissionFullMatrix))
			{
				$roles			=$this->getUserRoles();
				$permissionIDs	=[];
				//Capture the permission id of every assigned permission in every role assigned to the user.
				for ($i=0,$j=count($roles); $i<$j; $i++)
				{
					$thesePermissions=$this->model->PermissionRole->read(['role_id'=>$roles[$i]['id']]);
					for ($k=0,$l=count($thesePermissions); $k<$l; $k++)
					{
						$permissionIDs[]=$thesePermissions[$k]['permission_id'];
					}
				}
				//Now capture every permission id which is explicitly assigned to the user.
				$thesePermissions	=$this->model->PermissionUser->read(['user_id'=>$this->getUserId()]);
				for ($i=0,$j=count($thesePermissions); $i<$j; $i++)
				{
					$permissionIDs[]=$thesePermissions[$i]['permission_id'];
				}
				if (count($permissionIDs))
				{
					//Generate a single query to capture all the permissions.
					$permissionIDs=implode(',',$permissionIDs);
					$query=<<<SQL
					SELECT * FROM permission
					WHERE id IN({$permissionIDs});
SQL;
					$result=$this->plugin->Db->nutsnbolts->select($query);
					if ($result)
					{
						$this->permissionFullMatrix=$this->plugin->Db->nutsnbolts->result('assoc');
					}
					else
					{
						$this->permissionFullMatrix=[];
					}
					$this->permissionKeyMatrix=[];
					for ($i=0,$j=count($this->permissionFullMatrix); $i<$j; $i++)
					{
						$this->permissionKeyMatrix[]=$this->permissionFullMatrix[$i]['key'];
					}
				}
				else
				{
					$this->permissionFullMatrix	=[];
					$this->permissionKeyMatrix	=[];
				}
			}
			return $this;
		}
		
		public function can($do)
		{
			if ($this->isSuperUser() || in_array($do,$this->permissionKeyMatrix))
			{
				return $this;
			}
			throw new AuthException(AuthException::PERMISSION_DENIED,'Permission Denied.');
		}
		
		private function generateSalt(&$record)
		{
			$record['salt']=sha1('wheretoparty_ce1833cca4627da0751a2dcdde1f0b3b_'.time());
		}
	}
}