<?php
namespace application\model
{
	use application\model\base\NodeMap as NodeMapBase;
	
	class NodeMap extends NodeMapBase	
	{
		public function getNodesForPath($path)
		{
			$query=<<<SQL
			SELECT node.*
			FROM node
			LEFT JOIN node_map ON node_map.node_id=node.id
			WHERE node_map.url=?;
SQL;
			if ($this->db->select($query,array($path)))
			{
				$records=$this->db->result('assoc');
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					$query=<<<SQL
					SELECT node_part.value,content_part.ref
					FROM node_part
					LEFT JOIN content_part ON content_part.id=node_part.content_part_id
					WHERE node_part.node_id=?;
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
				return isset($records)?$records:false;
			}
			return false;
		}
	}
}
?>