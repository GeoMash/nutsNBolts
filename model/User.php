<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\NutsNBolts;
	use application\nutsNBolts\model\base\User as UserBase;
	use nutshell\core\exception\NutshellException;
	
	class User extends UserBase
	{
		public function read($whereKeyVals = array(), $readColumns = array(), $additionalPartSQL='')
		{
			$result=parent::read($whereKeyVals, $readColumns, $additionalPartSQL);
			
			for ($i=0,$j=count($result); $i<$j; $i++)
			{
				$result[$i]['roles']=$this->getRoles($result[$i]['id']);
			}
			return $result;
		}
		
		public function handleRecord($record,$removeRoles=false)
		{
			if (!isset($record['status']))$record['status']=0;
			
			if (!empty($record['password']))
			{
				$this->generateSalt($record);
				$record['password']=md5($this->config->application->salt.$record['salt'].$record['password']);
			}
			
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				if (empty($record['password']))
				{
					unset($record['password']);
				}
				$result=$this->update($this->removeJunk($record),['id'=>$record['id']]);
				
				if($removeRoles)
				{
					$this->model->UserRole->delete(['user_id'=>$record['id']]);
				}
				if (isset($record['role']))
				{
					$roles=$this->extractRoles($record);
					for ($i=0,$j=count($roles); $i<$j; $i++)
					{
						$this->model->UserRole->insertAssoc($roles[$i]);
					}
				}
				if (isset($record['permit']))
				{
					$this->model->PermissionUser->delete(['user_id'=>$record['id']]);
					$permissions=$this->extractPermissions($record);
					for ($i=0,$j=count($permissions); $i<$j; $i++)
					{
						$this->model->PermissionUser->insertAssoc($permissions[$i]);
					}
				}
				if ($result!==false)
				{
					return $this->read($record['id'])[0];
				}
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$record['date_created']		=date('Y-m-d H:i:s');
				$record['date_lastlogin']	='0000-00-00 00:00:00';
				$record['date_lastactive']	='0000-00-00 00:00:00';

				if ($id=$this->insertAssoc($this->removeJunk($record)))
				{
					if (isset($record['role']))
					{
						$roles=$this->extractRoles($record);
						for ($i=0,$j=count($roles); $i<$j; $i++)
						{
							$roles[$i]['user_id']=$id;
							$this->model->UserRole->insert($roles[$i]);
						}
						$permissions=$this->extractPermissions($record);
						for ($i=0,$j=count($permissions); $i<$j; $i++)
						{
							$this->model->PermissionUser->insert($permissions[$i]);
						}
					}
					return $this->read($id)[0];
				}
			}
			return false;
		}

		public function handleDeleteRecord($recordId)
		{
			$this->model->UserRole->delete(array('user_id'=>$recordId));
			$this->delete($recordId);
			return true;
		}

		public function extractRoles(&$record)
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
		
		private function extractPermissions(&$record)
		{
			$permissions=[];
			if(isset($record['permit']))
			{
				$id=(!empty($record['id']))?$record['id']:0;
				for ($i=0,$j=count($record['permit']); $i<$j; $i++)
				{
					$permissions[]=
					[
						'user_id'		=>$id,
						'permission_id'	=>$record['permit'][$i]
					];
				}
				unset($record['permit']);
			}
			return $permissions;
		}
		
		private function generateSalt(&$record)
		{
			$record['salt']=sha1('nutsnbolts_ce1833cca4627da0751a2dcdde1f0b3b_'.time());
		}
		
		public function getRoles($userId)
		{
			if ($userId!=NutsNBolts::USER_SUPER)
			{
				$query=<<<SQL
SELECT role.*
FROM user_role
LEFT JOIN role ON role.id=user_role.role_id
WHERE user_id=?
SQL;
				if ($this->db->select($query,array($userId)))
				{
					$records=$this->db->result('assoc');
					return isset($records)?$records:null;
				}
			}
			else
			{
				$query='SELECT * FROM role WHERE id=-100;';
				if ($this->db->select($query,array($userId)))
				{
					$records=$this->db->result('assoc');
					return isset($records)?$records:null;
				}
				else
				{
					throw new NutshellException('Ooops! Root role has not been configured.');
				}
			}
			return null;
		}

		public function getUsersByRole($role, $where=null)
		{
			if (!is_array($role))
			{
				$role=array($role);
			}
			$roleQueryPart=array();
			if(!$where)
			{
				$where='';
			}
			if (is_numeric($role[0]))
			{
				for ($i=0,$j=count($role); $i<$j; $i++)
				{
					$roleQueryPart[]=$role[$i];
				}
				$roleQueryPart=implode(',',$roleQueryPart);
				$query=<<<SQL
SELECT DISTINCT user.*
FROM user
LEFT JOIN user_role ON user_role.user_id=user.id
LEFT JOIN role ON role.id=user_role.role_id
WHERE (role.id IN ({$roleQueryPart}))
{$where}
;
SQL;
			}
			else
			{
				for ($i=0,$j=count($role); $i<$j; $i++)
				{
					$roleQueryPart[]='"'.$role[$i].'"';
				}
				$roleQueryPart=implode(',',$roleQueryPart);
				$query=<<<SQL
SELECT DISTINCT user.*
FROM user
LEFT JOIN user_role ON user_role.user_id=user.id
LEFT JOIN role ON role.id=user_role.role_id
WHERE role.id=-100 OR role.ref IN ({$roleQueryPart});
SQL;
			}
			if ($this->db->select($query))
			{
				$records=$this->db->result('assoc');
				return isset($records)?$records:null;
			}
			return false;
		}
	}
}
?>