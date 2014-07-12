<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\NutsNBolts;
	use nutshell\core\exception\NutshellException;
	use application\nutsNBolts\model\base\ContentType as ContentTypeBase;

	class ContentType extends ContentTypeBase	
	{
		public function read($whereKeyVals = array(), $readColumns = array(), $additionalPartSQL='')
		{
			$result=parent::read($whereKeyVals, $readColumns, $additionalPartSQL);
			
			for ($i=0,$j=count($result); $i<$j; $i++)
			{
				$result[$i]['roles']=$this->getRoles($result[$i]['id']);
				$result[$i]['users']=$this->getUsers($result[$i]['id']);
			}
			// var_dump($result);exit();
			return $result;
		}
		
		public function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			//For Updates
			if (!empty($record['id']) && is_numeric($record['id']))
			{
				$return			=false;
				$existingParts	=$this->model->ContentPart->read(array('content_type_id'=>$record['id']));
				$contentParts	=$this->extractContentParts($record);
				$contentRoles	=$this->extractRoles($record);
				$contentUsers	=$this->extractUsers($record);
				$this->update($record,array('id'=>$record['id']));
				//Update parts.
				for ($i=0,$j=count($contentParts); $i<$j; $i++)
				{
					//For Update
					/*
						# MD 8 August 2012
						# bug: the else condition of the statement would not ne true, now checking for empty id as well
					*/
					if ($contentParts[$i]['id']!==0 && !empty($contentParts[$i]['id']))
					{
						// bug fixed by Tim
						$return=$this->model->ContentPart->update($contentParts[$i],array('id'=>$contentParts[$i]['id']));
					}
					//For Insert
					else
					{
						$contentParts[$i]['content_type_id']=$record['id'];
						$return=$this->model->ContentPart->insertAssoc($contentParts[$i]);
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
				//Delete Roles.
				$this->model->ContentTypeRole->delete(array('content_type_id'=>$record['id']));
				//Insert Roles.
				for ($i=0,$j=count($contentRoles); $i<$j; $i++)
				{
					$this->model->ContentTypeRole->insert($contentRoles[$i]);
				}
				//Delete Users.
				$this->model->ContentTypeUser->delete(array('content_type_id'=>$record['id']));
				//Insert Users.
				for ($i=0,$j=count($contentUsers); $i<$j; $i++)
				{
					$this->model->ContentTypeUser->insert($contentUsers[$i]);
				}
				$this->syncContentPartsOnAllNodes($record['id']);
				return $return;
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$contentParts=$this->extractContentParts($record);
				$contentRoles=$this->extractRoles($record);
				$contentUsers=$this->extractUsers($record);
				if ($id=$this->insertAssoc($record))
				{
					for ($i=0,$j=count($contentParts); $i<$j; $i++)
					{
						$contentParts[$i]['content_type_id']=$id;
						$this->model->ContentPart->insertAssoc($contentParts[$i]);
					}
					for ($i=0,$j=count($contentRoles); $i<$j; $i++)
					{
						$contentRoles[$i]['content_type_id']=$id;
						$this->model->ContentTypeRole->insertAssoc($contentRoles[$i]);
					}
					for ($i=0,$j=count($contentUsers); $i<$j; $i++)
					{
						$contentUsers[$i]['content_type_id']=$id;
						$this->model->ContentTypeUser->insertAssoc($contentUsers[$i]);
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
				$id=(!empty($record['part_id'][$i]))?$record['part_id'][$i]:0;
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
					'label'				=>trim($record['label'][$i]),
					'ref'				=>trim($ref),
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
		
		private function extractRoles(&$record)
		{
			if (isset($record['role']))
			{
				$roles=array();
				$id=(!empty($record['id']))?$record['id']:0;
				foreach ($record['role'] as $roleID=>$enabled)
				{
					$roles[]=array
					(
						'content_type_id'	=>$id,
						'role_id'			=>$roleID
					);
				}
				unset($record['role']);
				return $roles;
			}
			return array();
		}
		
		private function extractUsers(&$record)
		{
			if (isset($record['user']))
			{
				$roles=array();
				$id=(!empty($record['id']))?$record['id']:0;
				foreach ($record['user'] as $userID=>$enabled)
				{
					$roles[]=array
					(
						'content_type_id'	=>$id,
						'user_id'			=>$userID
					);
				}
				unset($record['user']);
				return $roles;
			}
			return array();
		}
		
		public function readWithParts($id)
		{
			$contentType=$this->read($id);
			if (isset($contentType[0]))
			{
				$contentType=$contentType[0];
				$query=<<<SQL
				SELECT	content_part.id,
						content_part.label,
						content_part.widget,
						content_part.config
				FROM content_type
				LEFT JOIN content_part ON content_part.content_type_id=content_type.id
				WHERE content_type.id=?
SQL;
				if ($this->db->select($query,array($id)))
				{
					$contentType['parts']=$this->db->result('assoc');
				}
				else
				{
					$contentType['parts']=[];
				}
				return $contentType;
			}
			return false;
		}
		
		public function getRoles($contentTypeId)
		{
			if ($contentTypeId!=NutsNBolts::USER_SUPER)
			{
				$query=<<<SQL
SELECT role.*
FROM content_type_role
LEFT JOIN role ON role.id=content_type_role.role_id
WHERE content_type_id=?
SQL;
				if ($this->db->select($query,array($contentTypeId)))
				{
					$records=$this->db->result('assoc');
					return isset($records)?$records:null;
				}
			}
			else
			{
				$query='SELECT * FROM role WHERE id=-100;';
				if ($this->db->select($query,array($contentTypeId)))
				{
					$records=$this->db->result('assoc');
					return isset($records)?$records:null;
				}
				else
				{
					throw new NutshellException('Ooops! Root role has not been configured.');
				}
			}
			return null;
		}

		public function getUsers($contentTypeId)
		{
			if ($contentTypeId!=NutsNBolts::USER_SUPER)
			{
				$query=<<<SQL
SELECT user.*
FROM content_type_user
LEFT JOIN user ON user.id=content_type_user.user_id
WHERE content_type_id=?
SQL;
				if ($this->db->select($query,array($contentTypeId)))
				{
					$records=$this->db->result('assoc');
					return isset($records)?$records:null;
				}
			}
			else
			{
				$query='SELECT * FROM user WHERE id=-100;';
				if ($this->db->select($query,array($contentTypeId)))
				{
					$records=$this->db->result('assoc');
					return isset($records)?$records:null;
				}
				else
				{
					throw new NutshellException('Ooops! Root user has not been configured.');
				}
			}
			return null;
		}
		
		public function syncContentPartsOnAllNodes($contentTypeId)
		{
			$found			=false;
			$nodeGroups		=[];
			$contentType	=$this->readWithParts($contentTypeId);
			$query			=<<<SQL
			SELECT node.id,
			node_part.id AS node_part_id,
			content_part.id AS content_part_id
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			WHERE node.content_type_id=?
			ORDER BY node.id ASC;
SQL;
			if ($result=$this->plugin->Db->nutsnbolts->select($query,[$contentTypeId]))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!is_array($nodeGroups[$records[$i]['id']]))
					{
						$nodeGroups[$records[$i]['id']]=[];
					}
					$nodeGroups[$records[$i]['id']][]=$records[$i]['content_part_id'];
				}
//				print '<pre>';
//				print_r($nodeGroups);
//				print '</pre>';
//				exit();
				for ($i=0,$j=count($contentType['parts']); $i<$j; $i++)
				{
					foreach ($nodeGroups as $nodeId=>$nodePartIds)
					{
						$found=false;
						for ($k=0,$l=count($nodePartIds); $k<$l; $k++)
						{
							if ($nodePartIds[$k]==$contentType['parts'][$i]['id'])
							{
								$found=true;
							}
						}
						if (!$found)
						{
//							print $contentType['parts'][$i]['id'].'::<br>';
//							print 'NOT FOUND<br>';
							$this->insertNullPart($nodeId,$contentType['parts'][$i]['id']);
						}
					}
					
				}
			}
			return $this;
		}
		
		private function insertNullPart($nodeId,$contentPartId)
		{
			$this->model->NodePart->insertAssoc
			(
				[
					'node_id'			=>$nodeId,
					'content_part_id'	=>$contentPartId,
					'value'				=>null
				]
			);
			return $this;
		}
	}
}
?>