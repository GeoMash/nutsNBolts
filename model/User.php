<?php
namespace application\nutsnbolts\model
{
	use application\nutsnbolts\model\base\User as UserBase;
	
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
				// $roles	=$this->extractRoles($record);
				$return=$this->update($record,array('id'=>$record['id']));
				return $return;
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$record['date_created']		=date('Y-m-d H:i:s');
				$record['date_lastlogin']	='0000-00-00 00:00:00';
				$record['date_lastactive']	='0000-00-00 00:00:00';
				// $contentParts=$this->extractRoles($record);
				if ($id=$this->insertAssoc($record))
				{
					// for ($i=0,$j=count($contentParts); $i<$j; $i++)
					// {
					// 	$contentParts[$i]['content_type_id']=$id;
					// 	$this->model->ContentPart->insertAssoc($contentParts[$i]);
					// }
					return $id;
				}
			}
			return false;
		}
		
		public function extractRoles(&$record)
		{
			
		}
		
		private function generateSalt(&$record)
		{
			$record['salt']=sha1('nutsnbolts_ce1833cca4627da0751a2dcdde1f0b3b_'.time());
		}
	}
}
?>