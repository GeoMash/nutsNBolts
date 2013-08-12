<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\User as UserBase;
	
	class User extends UserBase	
	{
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
				$roles	=$this->extractRoles($record);
				$return	=$this->update($record,array('id'=>$record['id']));
				$this->model->UserRole->delete(array('user_id'=>$record['id']));
				
				for ($i=0,$j=count($roles); $i<$j; $i++)
				{
					$this->model->UserRole->insert($roles[$i]);
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
				if ($id=$this->insertAssoc($record))
				{
					for ($i=0,$j=count($roles); $i<$j; $i++)
					{
						$roles[$i]['user_id']=$id;
						$this->model->UserRole->insertAssoc($roles[$i]);
					}
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
	}
}
?>