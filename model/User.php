<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\NutsNBolts;
	use application\nutsNBolts\model\base\User as UserBase;
	use nutshell\exception\NutshellException;
	
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
		
		public function handleRecord($record)
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
				// var_dump($record);exit();

				
				if(isset($record['role']))
				{
					$roles	=$this->extractRoles($record);
					
					$this->model->UserRole->delete(array('user_id'=>$record['id']));					
					for ($i=0,$j=count($roles); $i<$j; $i++)
					{
						$this->model->UserRole->insert($roles[$i]);
					}					
				}
				
				if(isset($record['bars']))
				{
					$bars	=$this->extractBars($record);
					
					$this->model->UserBar->delete(array('user_id'=>$record['id']));					
					for ($i=0,$j=count($bars); $i<$j; $i++)
					{

						$this->model->UserBar->insert($bars[$i]);
					}					
				}				
				
				$return	=$this->update($record,array('id'=>$record['id']));
				return $return;
				
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$record['date_created']		=date('Y-m-d H:i:s');
				$record['date_lastlogin']	='0000-00-00 00:00:00';
				$record['date_lastactive']	='0000-00-00 00:00:00';
				$role=$record['role'];
				unset($record['role']);
				$bars=$record['bars'];
				unset($record['bars']);
				// if(!isset($record['role']))
				// {
				// 	$record['role']=1;
				// }

				// $roles=$this->extractRoles($record);
				// var_dump($roles); exit();
				if ($id=$this->insertAssoc($record))
				{
					$array=array('user_id'=>$id,'role_id'=>$role);
					$this->model->UserRole->insertAssoc($array);
					// var_dump($id); exit();
					return $id;
				}
			}
			return false;
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
		
		public function extractBars(&$record)
		{
			if (isset($record['bars']))
			{
				$bars=array();
				$id=(!empty($record['id']))?$record['id']:0;
				foreach ($record['bars'] as $barId=>$enabled)
				{
					$bars[]=array
					(
						'user_id'	=>$id,
						'bar_id'	=>$barId
					);
				}
				unset($record['bars']);
				return $bars;
			}
			return array();
		}		
		
		private function generateSalt(&$record)
		{
			$record['salt']=sha1('nutsnbolts_ce1833cca4627da0751a2dcdde1f0b3b_'.time());
		}
		
		public function authenticate($email,$password)
		{
			$user		=$this->read(array('email'=>$email));
			if (isset($user[0]))
			{
				$userSalt	=$user[0]['salt'];
				$systemSalt	=$this->config->application->salt;
				
				$result	=$this->read
				(
					array
					(
						'email'		=>$email,
						'password'	=>md5($systemSalt.$userSalt.$password),
						'status'	=>1
					)
				);
				if (isset($result[0]))
				{
					return $result[0];
				}
			}
			return false;
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

		public function getUsersByRole($role)
		{
			if (!is_array($role))
			{
				$role=array($role);
			}
			$roleQueryPart=array();
			if (is_numeric($role[0]))
			{
				for ($i=0,$j=count($role); $i<$j; $i++)
				{
					$roleQueryPart[]=$role[$i];
				}
				$roleQueryPart=implode(',',$roleQueryPart);
				$query=<<<SQL
SELECT DISTINCT user.*
FROM USER
LEFT JOIN user_role ON user_role.user_id=user.id
LEFT JOIN role ON role.id=user_role.role_id
WHERE role.id=-100 OR role.id IN ({$roleQueryPart});
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
FROM USER
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