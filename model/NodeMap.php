<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\NodeMap as NodeMapBase;
	
	class NodeMap extends NodeMapBase	
	{
		public function getNodesForPath($path)
		{
			$path=rtrim($path,'/');
			if (empty($path))
			{
				$path='/';
			}
			//Is there a URL matching this exact path?
			$query=<<<SQL
			SELECT node.*
			FROM node
			LEFT JOIN node_map ON node_map.node_id=node.id
			WHERE node_map.url=?;
SQL;
			if ($this->db->select($query,array($path)))
			{
				$records=$this->db->result('assoc');
				$this->attachParts($records);
				return $records;
			}
			
			//No, so lets see if we can find one that has a wildcard.
			
			$pathParts	=explode('/',$path);
			$checks		=array();
			$currentPath='';
			for ($i=0,$j=count($pathParts); $i<$j; $i++)
			{
				if (!empty($pathParts[$i]))
				{
					$currentPath.='/';
				}
				$currentPath.=$pathParts[$i];
				$checks[]=$currentPath.'/\\\\*';
			}
			$regexp='('.implode(')|(',$checks).')';
			$query=<<<SQL
			SELECT node.*
			FROM node
			LEFT JOIN node_map ON node_map.node_id=node.id
			WHERE node_map.url REGEXP('^{$regexp}\$');
SQL;
			if ($this->db->select($query))
			{
				$records=$this->db->result('assoc');		
				$this->attachParts($records);
				return $records;
			}
			return false;
		}
		
		private function attachParts(&$records)
		{
			for ($i=0,$j=count($records); $i<$j; $i++)
			{
				$query=<<<SQL
				SELECT node_part.value,content_part.ref
				FROM node_part
				LEFT JOIN content_part ON content_part.id=node_part.content_part_id
				WHERE node_part.node_id=?
				ORDER BY node_id DESC;
SQL;
				if ($this->db->select($query,array($records[$i]['id'])))
				{
					$nodePart=$this->db->result('assoc');
					for ($k=0,$l=count($nodePart); $k<$l; $k++)
					{
						$records[$i][$nodePart[$k]['ref']]=$nodePart[$k]['value'];
					}
				}
			}
		}

	}
}
?>