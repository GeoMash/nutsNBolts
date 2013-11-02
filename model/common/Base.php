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
				return $this->update($record,array('id'=>$record['id']));
			}
			//For Inserts
			else
			{
				return $this->insertAssoc($record);
			}
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

		public function extractURLs(&$record,$idField)
		{
			$urls=array();
			$id=(!empty($record['id']))?$record['id']:0;
			if(isset($record['url']))
			{
				for ($i=0,$j=count($record['url']); $i<$j; $i++)
				{
					$urls[]=array
					(
						$idField	=>$id,
						'url'		=>$record['url'][$i]
					);
				}
				unset($record['url']);
			}
			return $urls;
		}
	}
}