<?php
namespace application\nutsnbolts\model
{
	use application\nutsnbolts\model\base\Node as NodeBase;
	
	class Node extends NodeBase	
	{
		public function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				$nodeParts		=$this->extractContentParts($record);
				$this->update($record,array('id'=>$record['id']));
				//Update parts.
				for ($i=0,$j=count($nodeParts); $i<$j; $i++)
				{
					//For Update
					if ($nodeParts[$i]['id']!==0)
					{
						$this->model->NodePart->update($nodeParts[$i],array('id'=>$nodeParts[$i]['id']));
					}
					//For Insert
					else
					{
						$this->model->NodePart->insertAssoc($nodeParts[$i]);
					}
				}
			}
			//For Inserts
			else
			{
				$nodeParts=$this->extractContentParts($record);
				if ($id=$this->insertAssoc($record))
				{
					for ($i=0,$j=count($nodeParts); $i<$j; $i++)
					{
						$nodeParts[$i]['node_id']=$id;
						$this->model->NodePart->insertAssoc($nodeParts[$i]);
					}
					return $id;
				}
			}
			return false;
		}
		
		private function extractContentParts(&$record)
		{
			$nodeParts=array();
			foreach ($record as $key=>$val)
			{
				if (strstr($key,'node_part_id_'))
				{
					list($contentPartId,$nodeId)=explode('_',str_replace('node_part_id_','',$key));
					if ($nodeId==='0')$nodeId=0;
					$nodeParts[]=array
					(
					 	'id'				=>$nodeId,
						'node_id'			=>$record['id'],
						'content_part_id'	=>$contentPartId,
						'value'				=>$val
					);
					unset($record[$key]);
				}
			}
			return $nodeParts;
		}
	}
}
?>