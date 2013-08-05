<?php
namespace application\nutsnbolts\model
{
	use application\nutsnbolts\model\base\ContentType as ContentTypeBase;
	
	class ContentType extends ContentTypeBase	
	{
		public function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				$existingParts	=$this->model->ContentPart->read(array('content_type_id'=>$record['id']));
				$contentParts	=$this->extractContentParts($record);
				$this->update($record,array('id'=>$record['id']));
				//Update parts.
				for ($i=0,$j=count($contentParts); $i<$j; $i++)
				{
					//For Update
					if ($contentParts[$i]['id']!==0)
					{
						$this->model->ContentPart->update($contentParts[$i],array('id'=>$contentParts[$i]['id']));
					}
					//For Insert
					else
					{
						$contentParts[$i]['content_type_id']=$record['id'];
						$this->model->ContentPart->insertAssoc($contentParts[$i]);
					}
				}
				//Now delete parts.
				for ($i=0,$j=count($existingParts); $i<$j; $i++)
				{
					$found=false;
					for ($k=0,$l=count($contentParts); $k<$l; $k++)
					{
						if ($contentParts[$k]['id']==$existingParts[$i]['id'])
						{
							$found=true;
							break;
						}
					}
					if (!$found)
					{
						$this->model->ContentPart->delete($existingParts[$i]);
					}
				}
				
				
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$contentParts=$this->extractContentParts($record);
				if ($id=$this->insertAssoc($record))
				{
					for ($i=0,$j=count($contentParts); $i<$j; $i++)
					{
						$contentParts[$i]['content_type_id']=$id;
						$this->model->ContentPart->insertAssoc($contentParts[$i]);
					}
					return $id;
				}
			}
			return false;
		}
		
		public function handleDeleteRecord($recordId)
		{
			$this->model->ContentPart->delete(array('content_type_id'=>$recordId));
			$this->delete($recordId);
			return true;
		}
		
		private function extractContentParts(&$record)
		{
			$contentParts=array();
			for ($i=0,$j=count($record['widget']); $i<$j; $i++)
			{
				$id=(isset($record['part_id'][$i]))?$record['part_id'][$i]:0;
				$ref=str_replace
				(
					array(' '),
					array('_'),
					strtolower($record['label'][$i])
				);
				$contentParts[$i]=array
				(
				 	'id'				=>$id,
					'widget'			=>$record['widget'][$i]['namespace'],
					'label'				=>$record['label'][$i],
					'ref'				=>$ref,
					'config'			=>''
				);
				
				if (isset($record['widget'][$i]['config']))
				{
					$contentParts[$i]['config']=json_encode($record['widget'][$i]['config']);
				}
			}
			unset($record['part_id']);
			unset($record['widget']);
			unset($record['label']);
			return $contentParts;
		}
		
		public function readWithParts($id)
		{
			$query=<<<SQL
			SELECT	content_type.name,
					content_type.icon,
					content_part.id AS content_part_id,
					content_part.label,
					content_part.widget,
					content_part.config
			FROM content_type
			LEFT JOIN content_part ON content_part.content_type_id=content_type.id
			WHERE content_type.id=?
SQL;
			if ($this->db->select($query,array($id)))
			{
				$records=$this->db->result('assoc');
				return isset($records)?$records:false;
			}
			return false;
		}
	}
}
?>