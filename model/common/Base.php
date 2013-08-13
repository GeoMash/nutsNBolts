<?php
namespace application\nutsNBolts\model\common
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
		
		public function removeJunk($record)
		{
			$columns=array_keys($this->columns);
			foreach ($record as $field=>$value)
			{
				if (!in_array($field,$columns))
				{
					unset($record[$field]);
				}
			}
			return $record;
		}
	}
}