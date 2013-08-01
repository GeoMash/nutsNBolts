<?php
namespace application\nutsnbolts\model\common
{
	use nutshell\plugin\mvc\model\CRUD;

	abstract class Base extends CRUD
	{
		public function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				$this->update($record,array('id'=>$record['id']));
			}
			//For Inserts
			else
			{
				return $this->insertAssoc($record);
			}
			return false;
		}
	}
}