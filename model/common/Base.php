<?php
namespace application\nutsNBolts\model\common
{
	use application\nutsNBolts\exception\NutsNBoltsException;
	use nutshell\plugin\mvc\model\CRUD;

	abstract class Base extends CRUD
	{
		public function count($whereKeyVals=array())
		{
			if (count($whereKeyVals))
			{
				$where=[];
				foreach ($whereKeyVals as $key=>$val)
				{
					$where[]=$key.'="'.$val.'"';
				}
				$where=implode(' AND ',$where);
				$this->getDb()->query('SELECT COUNT(*) AS count FROM '.$this->name.' WHERE '.$where.';');
			}
			else
			{
				$this->getDb()->query('SELECT COUNT(*) AS count FROM '.$this->name.';');
			}
			$result=$this->getDb()->result('assoc');
			return (int)$result[0]['count'];
		}
		
		public function handleRecord($record)
		{
			$columns=array_keys($this->columns);
			if (in_array('status',$columns))
			{
				if (!isset($record['status']))$record['status']=0;
			}
			//For Updates
			if (!empty($record['id']) && is_numeric($record['id']))
			{
				return $this->update($record,array('id'=>$record['id']));
			}
			//For Inserts
			else
			{
				unset($record['id']);
				return $this->insertAssoc($record);
			}
		}

		public function setStatus($id,$status)
		{
			if (isset($this->columns['status']))
			{
				return $this->update(array('status'=>$status),array('id'=>$id));
			}
			else
			{
				throw new NutsNBoltsException('Status column doesn\'t exist for table "'.$this->name.'".');
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
		
		public function makeSafeFieldName($string)
		{
			$string=preg_replace('/[^\w]/','_',strtolower($string));
			while (strstr($string,'__'))
			{
				$string=str_replace('__','_',$string);
			}
			return $string;
		}
	}
}