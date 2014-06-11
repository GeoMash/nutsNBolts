<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\Role as RoleBase;
	
	class Role extends RoleBase	
	{
		public function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				$permissions	=$this->extractPermissions($record);
				$return			=$this->update($this->removeJunk($record),['id'=>$record['id']]);
				//Update items.
				$this->model->PermissionRole->delete(['role_id'=>$record['id']]);
				for ($i=0,$j=count($permissions); $i<$j; $i++)
				{
					$permissions[$i]['role_id']=$record['id'];
					$this->model->PermissionRole->insertAssoc($permissions[$i]);
				}
				return $return;
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$permissions	=$this->extractPermissions($record);
				if ($id=$this->insertAssoc($this->removeJunk($record)))
				{
					for ($i=0,$j=count($permissions); $i<$j; $i++)
					{
						$permissions[$i]['role_id']=$id;
						$this->model->PermissionRole->insertAssoc($permissions[$i]);
					}
					return $this->read($id)[0];
				}
			}
			return false;
		}
		
		private function extractPermissions(&$record)
		{
			$permissions=[];
			if(isset($record['permit']))
			{
				for ($i=0,$j=count($record['permit']); $i<$j; $i++)
				{
					$permissions[]=
					[
						'permission_id'	=>$record['permit'][$i]
					];
				}
				unset($record['permit']);
			}
			return $permissions;
		}
	}
}
?>