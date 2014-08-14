<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\Node as NodeBase;
	use nutshell\core\exception\NutshellException;
	use nutshell\helper\ArrayHelper;
	use \DateTime;
	use nutshell\plugin\mvc\exception\MvcException;

	class Node extends NodeBase	
	{
		const STATUS_SAVED		=0;
		const STATUS_SUBMITTED	=1;
		const STATUS_PUBLISHED	=2;
		const STATUS_ARCHIVED	=3;

		public function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				$nodeParts		=$this->extractContentParts($record);
				$nodeURLs		=$this->extractURLs($record,'node_id');
				$nodeTags		=$this->extractTags($record);
				$userAccess		=$this->extractUserAccess($record);
				$return			=$this->update($this->removeJunk($record),['id'=>$record['id']]);
				//Update parts.
				for ($i=0,$j=count($nodeParts); $i<$j; $i++)
				{
					//For Update
					if ($nodeParts[$i]['id']!==0)
					{
						$this->model->NodePart->update($nodeParts[$i],['id'=>$nodeParts[$i]['id']]);
						$return=true;
					}
					//For Insert
					else
					{
						$this->model->NodePart->insertAssoc($nodeParts[$i]);
					}
				}
				//Update URLs
				$this->model->NodeMap->delete(['node_id'=>$record['id']]);
				for ($i=0,$j=count($nodeURLs); $i<$j; $i++)
				{
					$this->model->NodeMap->insertAssoc($nodeURLs[$i]);
				}
				//Update Tags
				$this->model->NodeTag->delete(['node_id'=>$record['id']]);
				for ($i=0,$j=count($nodeTags); $i<$j; $i++)
				{
					$this->model->NodeTag->insertAssoc($nodeTags[$i]);
				}
				//Update User Access
				$this->model->PermissionNode->delete(['node_id'=>$record['id']]);
				for ($i=0,$j=count($userAccess); $i<$j; $i++)
				{
					$this->model->PermissionNode->insertAssoc($userAccess[$i]);
				}

				if ($return!==false)
				{
					return $this->getWithParts($record['id'])[0];
				}
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$nodeParts	=$this->extractContentParts($record);
				$nodeURLs	=$this->extractURLs($record,'node_id');
				$nodeTags	=$this->extractTags($record);
				$userAccess	=$this->extractUserAccess($record);
				if ($id=$this->insertAssoc($this->removeJunk($record)))
				{
					for ($i=0,$j=count($nodeParts); $i<$j; $i++)
					{
						$nodeParts[$i]['node_id']=$id;
						$this->model->NodePart->insertAssoc($nodeParts[$i]);
					}
					for ($i=0,$j=count($nodeURLs); $i<$j; $i++)
					{
						$nodeURLs[$i]['node_id']=$id;
						$this->model->NodeMap->insertAssoc($nodeURLs[$i]);
					}
					for ($i=0,$j=count($nodeTags); $i<$j; $i++)
					{
						$nodeTags[$i]['node_id']=$id;
						$this->model->NodeTag->insertAssoc($nodeTags[$i]);
					}
					for ($i=0,$j=count($userAccess); $i<$j; $i++)
					{
						$this->model->PermissionNode->insertAssoc($userAccess[$i]);
					}
					return $id;
				}
			}
			return false;
		}
		
		private function extractContentParts(&$record)
		{
			$nodeParts=array();
			$id=(!empty($record['id']))?$record['id']:0;
			foreach ($record as $key=>$val)
			{
				if (strstr($key,'node_part_id_'))
				{
					list($contentPartId,$nodePartId)=explode('_',str_replace('node_part_id_','',$key));
					$contentPartId=(int)$contentPartId;
					$nodePartId=(int)$nodePartId;
					if (empty($nodePartId))
					{
						$nodePartId=0;
					}
					$nodeParts[]=array
					(
					 	'id'				=>$nodePartId,
						'node_id'			=>$id,
						'content_part_id'	=>$contentPartId,
						'value'				=>$val
					);
					unset($record[$key]);
				}
			}
			return $nodeParts;
		}
		
		private function extractTags(&$record)
		{
			$id		=(!empty($record['id']))?$record['id']:0;
			$return	=array();
			if(isset($record['tags']))
			{
				$tags	=explode(',',$record['tags']);	
				
				for ($i=0,$j=count($tags); $i<$j; $i++)
				{
					$return[]=array
					(
						'node_id'	=>$id,
						'tag'		=>$tags[$i]
					);
				}
				unset($record['tags']);				
			}
			return $return;
		}
		
		public function extractUserAccess(&$record)
		{
			$id		=(!empty($record['id']))?$record['id']:0;
			$return	=array();
			if (isset($record['userAccess']))
			{
				$baseline=null;
				foreach (['read','update','delete'] as $variable)
				{
					if (isset($record['userAccess'][$variable]))
					{
						$baseline=$variable;
						break;
					}
				}
				if (!$baseline)
				{
					return $return;
				}
				foreach ($record['userAccess'][$baseline] as $userId=>$value)
				{
					$return[]=array
					(
						'node_id'	=>$id,
						'user_id'	=>$userId,
						'read'		=>isset($record['userAccess']['read'][$userId]),
						'update'	=>isset($record['userAccess']['update'][$userId]),
						'delete'	=>isset($record['userAccess']['delete'][$userId])
					);
				}
				unset($record['userAccess']);				
			}
			return $return;
		}
		
		private function syncPartsOnNode($nodeId)
		{
			
		}
		
		public function getCount($contentTypeId)
		{
			$query=<<<SQL
			SELECT count(id) as total
			FROM node
			WHERE content_type_id=?;
SQL;
			if ($result=$this->plugin->Db->nutsnbolts->select($query,array($contentTypeId)))
			{
				$record=$this->plugin->Db->nutsnbolts->result('assoc');
				return $record[0]['total'];
			}
			return 0;
		}

		public function getWithoutParts($contentTypeId,$excludeDeleted=true)
		{
			if ($excludeDeleted)
			{
				$status='AND status != '.self::STATUS_ARCHIVED;
			}
			else
			{
				$status='';
			}
			$query=<<<SQL
			SELECT *
			FROM node
			WHERE content_type_id=?
			{$status}
			ORDER BY node.date_updated ASC;
SQL;
			if ($this->plugin->Db->nutsnbolts->select($query,array($contentTypeId)))
			{
				return $this->plugin->Db->nutsnbolts->result('assoc');
			}
			return null;
		}
		
		public function getWithParts($whereKeyVals=array(),$offset=null,$limit=null,$status=self::STATUS_PUBLISHED)
		{
			$where=	array();
			$or		=array();
			$read	='';
			if (count($whereKeyVals))
			{
				if (is_numeric($whereKeyVals))
				{
					$where[]='node.id="'.$whereKeyVals.'"';
				}
				else if (is_array($whereKeyVals))
				{
					foreach ($whereKeyVals as $field=>$value)
					{
						switch ($field)
						{
							case 'id':
							{
								if(is_array($value))
								{
									for($i=0,$j=count($value);$i<$j;$i++)
									{
										$or[]='node.id="'.addslashes($value[$i]).'"';
									}
								}
								else
								{
									$where[]='node.id="'.addslashes($value).'"';
								}
								
								break;
							}
							case 'read':
							{
								if ($value===true)
								{
									$read='WHERE `read`';
								}
								else if ($value===false)
								{
									$read='WHERE NOT `read`';
								}
								else if (is_array($value))
								{
									$read='WHERE `read`'.$value[0].$value[1];
								}
								else
								{
									$read='WHERE `read`='.$value;
								}
								break;
							}
							case 'content_type_id':
							{
								$where[]='node.content_type_id="'.addslashes($value).'"';
								break;
							}
							default:
							{
								$where[]=<<<SQL_PART
								(
									content_part.ref="{$field}"
									AND
									node_part.value="{$value}"
								)
SQL_PART;
							}
						}
					}
				}
			}
			if ($where)
			{
				$where='WHERE '.implode(' AND ',$where);
			}
			else
			{
				$where='';
			}
			if ($or)
			{
				$or='WHERE '.implode(' OR ',$or);
			}
			else
			{
				$or='';
			}
			$userId=$this->plugin->Auth->getUserId();
			if(is_numeric($offset) && is_numeric($limit))
            {
                $limitSql=<<<SQL_PART
             	LIMIT {$offset},{$limit}
SQL_PART;
            }
            else
            {
                $limitSql='';
            }
			if (!empty($status))
			{
				$status=' AND node.status='.(int)$status;
			}
			$query=<<<SQL
			SELECT *
			FROM
			(
				SELECT `node`.*,
				`content_part`.`label`,
				`content_part`.`ref`,
				`node_part`.`value`,
				(
					SELECT COUNT(*)
					FROM `node_read`
					WHERE node_id=`node`.`id`
					AND `user_id`={$userId}
					LIMIT 1
				) AS `read`
				FROM `node`
				LEFT JOIN `node_part` ON `node`.`id`=`node_part`.`node_id`
				LEFT JOIN `content_part` ON `node_part`.`content_part_id`=`content_part`.`id`
				LEFT JOIN `content_type_user` ON `node`.`content_type_id`=`content_type_user`.`content_type_id`
				WHERE node.id IN
				(
					SELECT `node`.`id`
					FROM `node`
					LEFT JOIN `node_part` ON `node`.`id`=`node_part`.`node_id`
					LEFT JOIN `content_part` ON `node_part`.`content_part_id`=`content_part`.`id`
					{$where}
					{$or}
				)
				{$status}
				ORDER BY `node`.`id` ASC
				{$limitSql}
			) AS `read`
			{$read}
SQL;
			die($query);
			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status',
								'ref',
								'value'
							)
						);
						$nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
						$nodes[$records[$i]['id']]['date_published']=new DateTime($nodes[$records[$i]['id']]['date_published']);
						$nodes[$records[$i]['id']]['date_updated']	=new DateTime($nodes[$records[$i]['id']]['date_updated']);
					}
					$nodes[$records[$i]['id']][$records[$i]['ref']]=$records[$i]['value'];
				}
				//Reset index.
				sort($nodes);
				return $nodes;
			}
			return null;
		}
		
		public function getByTags($tags,$offset=null,$limit=null,$status=self::STATUS_PUBLISHED)
		{
			if (!is_array($tags))
			{
				$tags=[$tags];
			}
			if (!count($tags))
			{
				return [];
			}
			
			$tags='"'.implode('","',$tags).'"';
			
			if($offset && $limit)
            {
                $limitSql=<<<SQL_PART
             	LIMIT {$offset},{$limit}
SQL_PART;
            }
            else
            {
                $limitSql='';
            }
			$query=<<<SQL
			SELECT node.*,content_part.label,content_part.ref,node_part.value, content_type_user.*
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			WHERE node.id IN
			(
				SELECT node.id
				FROM node
				LEFT JOIN node_part ON node.id=node_part.node_id
				LEFT JOIN node_tag ON node.id=node_tag.node_id
				LEFT JOIN content_part ON node_part.content_part_id=content_part.id
				WHERE node_tag.tag IN ({$tags})
			)
			AND node.status={$status}
			ORDER BY node.id ASC
			{$limitSql}
			;
SQL;
			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status'
							)
						);
						$nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
						$nodes[$records[$i]['id']]['date_published']=new DateTime($nodes[$records[$i]['id']]['date_published']);
						$nodes[$records[$i]['id']]['date_updated']	=new DateTime($nodes[$records[$i]['id']]['date_updated']);
					}
					$nodes[$records[$i]['id']][$records[$i]['ref']]=$records[$i]['value'];
				}
				//Reset index.
				sort($nodes);
				return $nodes;
			}
			return [];
		}

		public function getBlog($id)
		{
			$query=<<<SQL
			SELECT node.*,content_part.ref,node_part.value,content_type_user.user_id
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			WHERE node.id={$id}
			AND node.status=2;
SQL;
			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status'
							)
						);
						// $nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
						// $nodes[$records[$i]['id']]['date_published']=new DateTime($nodes[$records[$i]['id']]['date_published']);
						// $nodes[$records[$i]['id']]['date_updated']	=new DateTime($nodes[$records[$i]['id']]['date_updated']);
					}
					$nodes[$records[$i]['id']][$records[$i]['ref']]=$records[$i]['value'];
				}
				//Reset index.
				sort($nodes);
				return $nodes;
			}
		}

		public function getBlogger($id)
		{
			$query=<<<SQL
			SELECT *
			FROM user
			WHERE 
			id={$id}
SQL;
			$record=null;
			if($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$record=$this->plugin->Db->nutsnbolts->result('assoc');	
			}
			return $record;
			
		}

		/*
		 thisBlogDate should be in the timestamp format
		 direction can be ASC or DESC
		 */
		public function getNextBlogArticle($userId, $thisBlogDate, $blogId, $direction)
		{
			if($direction == 'ASC')
			{
				$query=<<<SQL
				SELECT node.*,content_part.label,content_part.ref,node_part.value,content_type_user.user_id
				FROM node
				LEFT JOIN node_part ON node.id=node_part.node_id
				LEFT JOIN content_part ON node_part.content_part_id=content_part.id
				LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
				WHERE content_type_user.user_id=?
				AND node.status=2
				AND node.id <> ?
				AND node.date_created > ?
				GROUP BY (node.id)
				ORDER BY node.date_created ASC
				LIMIT 1
SQL;
			}
			else
			{
				$query=<<<SQL
				SELECT node.*,content_part.label,content_part.ref,node_part.value,content_type_user.user_id
				FROM node
				LEFT JOIN node_part ON node.id=node_part.node_id
				LEFT JOIN content_part ON node_part.content_part_id=content_part.id
				LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
				WHERE content_type_user.user_id=?
				AND node.status=2
				AND node.id <> ?
				AND node.date_created < ?
				GROUP BY (node.id)
				ORDER BY node.date_created DESC
				LIMIT 1
SQL;
			}

			if ($result=$this->plugin->Db->nutsnbolts->select($query,array($userId,$blogId, $thisBlogDate)))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status'
							)
						);
						$nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
						$nodes[$records[$i]['id']]['date_published']=new DateTime($nodes[$records[$i]['id']]['date_published']);
						$nodes[$records[$i]['id']]['date_updated']	=new DateTime($nodes[$records[$i]['id']]['date_updated']);
					}
					$nodes[$records[$i]['id']][$records[$i]['ref']]=$records[$i]['value'];
				}
				//Reset index.

				sort($nodes);			
				return $nodes;
			}
		}

		public function getBlogsByBlogger($bloggerId, $category, $min, $max)
		{
			$where=null;
			if(strlen($category) > 0)
			{
				$query=<<<SQL
				SELECT node.*,content_part.ref,node_part.value,content_type_user.user_id
				FROM node
				LEFT JOIN node_part ON node.id=node_part.node_id
				LEFT JOIN content_part ON node_part.content_part_id=content_part.id
				LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
				WHERE content_type_user.user_id={$bloggerId}
				AND node.status=2
SQL;
			}
			elseif(strlen($min) > 3)
			{
				$query=<<<SQL
				SELECT node.*,content_part.ref,node_part.value,content_type_user.user_id
				FROM node
				LEFT JOIN node_part ON node.id=node_part.node_id
				LEFT JOIN content_part ON node_part.content_part_id=content_part.id
				LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
				WHERE content_type_user.user_id={$bloggerId}
				AND node.status=2
				AND node.date_created BETWEEN "{$min}" AND "{$max}";	
SQL;
			}
			else
			{
				$query=<<<SQL
				SELECT node.*,content_part.ref,node_part.value,content_type_user.user_id
				FROM node
				LEFT JOIN node_part ON node.id=node_part.node_id
				LEFT JOIN content_part ON node_part.content_part_id=content_part.id
				LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
				WHERE content_type_user.user_id={$bloggerId}
				AND node.status=2
SQL;
			}

			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status'
							)
						);
						$nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
						$nodes[$records[$i]['id']]['date_published']=new DateTime($nodes[$records[$i]['id']]['date_published']);
						$nodes[$records[$i]['id']]['date_updated']	=new DateTime($nodes[$records[$i]['id']]['date_updated']);
					}
					$nodes[$records[$i]['id']][$records[$i]['ref']]=$records[$i]['value'];
				}
				if(strlen($category) > 0)
				{
					$newNodes=$nodes;
					unset($nodes);
					foreach($newNodes AS $node)
					{
						// print_r($newNodes);
						// die();
						foreach ($node AS $key=>$value)
						{
							if($key=='category' && $value==$category)
							{
								$nodes[]=$node;
							}							
						}

					}
				}
				//Reset index.
				sort($nodes);
				return $nodes;
			}
		}
		
		public function getBloggerCategories($id)
		{
			$query=<<<SQL
			SELECT DISTINCT node_part.value
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			WHERE content_type_user.user_id={$id}
			AND node.status=2
			AND content_part.ref="category"
			ORDER BY node.date_created DESC
SQL;

			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				return $records;
			}
		}	

		// need to modify this script and add the limit to it sometime in the future
		public function getRecent($bloggerId,$limit)
		{
			$query=<<<SQL
			SELECT node.*,content_part.label,content_part.ref,node_part.value,content_type_user.user_id
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			WHERE content_type_user.user_id={$bloggerId}
			AND node.status=2
			ORDER BY node.date_created DESC
SQL;

			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status'
							)
						);
						$nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
						$nodes[$records[$i]['id']]['date_published']=new DateTime($nodes[$records[$i]['id']]['date_published']);
						$nodes[$records[$i]['id']]['date_updated']	=new DateTime($nodes[$records[$i]['id']]['date_updated']);
					}
					$nodes[$records[$i]['id']][$records[$i]['ref']]=$records[$i]['value'];
				}
				//Reset index.
				sort($nodes);
				$nodes=array_reverse($nodes);
				return $nodes;
			}

		}

		public function getAllDates($bloggerId)
		{
			$query=<<<SQL
			SELECT node.date_created,node.id,node.site_id,node.status
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			WHERE content_type_user.user_id={$bloggerId}
			AND node.status=2
			ORDER BY node.date_created DESC
SQL;
			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status'
							)
						);
						$nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
					}
				}
				//Reset index.
				sort($nodes);
				return $nodes;
			}
		}
			
		public function countAllBlogs($bloggerId)
		{
			$query=<<<SQL
			SELECT node.date_created,node.id,node.site_id,node.status
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			WHERE content_type_user.user_id={$bloggerId}
			AND node.status=2
			ORDER BY node.date_created DESC
SQL;
			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status'
							)
						);
						$nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
					}
				}
				//Reset index.
				sort($nodes);
				return $nodes;
			}			
		}
		
		public function getAllBlogs()
		{
			$query=<<<SQL
			SELECT node.*,content_part.label,content_part.ref,node_part.value,content_type_user.user_id
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			WHERE content_part.ref='is_a_blog'
SQL;
			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status'
							)
						);
						// $nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
						// $nodes[$records[$i]['id']]['date_published']=new DateTime($nodes[$records[$i]['id']]['date_published']);
						// $nodes[$records[$i]['id']]['date_updated']	=new DateTime($nodes[$records[$i]['id']]['date_updated']);
					}
					$nodes[$records[$i]['id']][$records[$i]['ref']]=$records[$i]['value'];
				}
				//Reset index.
				sort($nodes);
				return $nodes;
			}	
		}
		
		public function getSpecificContentType($ref)
		{
			$query=<<<SQL
			SELECT node.*,content_part.label,content_part.ref,node_part.value,content_type_user.user_id,content_type.ref
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			LEFT JOIN content_type ON content_type.id=node.content_type_id
			WHERE content_type.ref='{$ref}'
SQL;
			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status'
							)
						);
						// $nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
						// $nodes[$records[$i]['id']]['date_published']=new DateTime($nodes[$records[$i]['id']]['date_published']);
						// $nodes[$records[$i]['id']]['date_updated']	=new DateTime($nodes[$records[$i]['id']]['date_updated']);
					}
					$nodes[$records[$i]['id']][$records[$i]['ref']]=$records[$i]['value'];
				}
				//Reset index.
				sort($nodes);
				return $nodes;
			}	
		}		
		
		public function getHomeTiles()
		{
			$query=<<<SQL
			SELECT node.*,content_part.label,content_part.ref,node_part.value,content_type_user.user_id,content_type.ref
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			LEFT JOIN content_type ON content_type.id=node.content_type_id
			WHERE content_type.ref='HOMESCREEN_MANAGEMENT'
SQL;
			if ($result=$this->plugin->Db->nutsnbolts->select($query))
			{
				$records=$this->plugin->Db->nutsnbolts->result('assoc');
				
				$nodes=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					if (!isset($nodes[$records[$i]['id']]))
					{
						$nodes[$records[$i]['id']]=ArrayHelper::withoutKey
						(
							$records[$i],
							array
							(
								'site_id',
								'status'
							)
						);
						// $nodes[$records[$i]['id']]['date_created']	=new DateTime($nodes[$records[$i]['id']]['date_created']);
						// $nodes[$records[$i]['id']]['date_published']=new DateTime($nodes[$records[$i]['id']]['date_published']);
						// $nodes[$records[$i]['id']]['date_updated']	=new DateTime($nodes[$records[$i]['id']]['date_updated']);
					}
					$nodes[$records[$i]['id']][$records[$i]['ref']]=$records[$i]['value'];
				}
				//Reset index.
				sort($nodes);
				return $nodes;
			}	
		}
		
		public function getSpecificNodesAndParts($array)
		{
			$records=$this->model->Node->read($array);
			$this->attachParts($records);
			return $records;
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
		
		public function markAsRead($nodeId,$userId)
		{
			$node=$this->read(['id'=>$nodeId]);
			if (isset($node[0]))
			{
				$user=$this->model->User->read(['id'=>$userId]);
				if (isset($user[0]))
				{
					$this->model->NodeRead->insertAssoc
					(
						[
							'node_id'			=>$nodeId,
							'user_id'			=>$userId,
							'content_type_id'	=>$node[0]['content_type_id']
						]
					);
					return true;
				}
				else
				{
					throw new MvcException('Cannot mark node as read. Could not find user.');
				}
			}
			else
			{
				throw new MvcException('Cannot mark node as read. Could not find node.');
			}
		}
		
		public function rate($nodeId,$userId,$rating)
		{
			$node=$this->read(['id'=>$nodeId]);
			if (isset($node[0]))
			{
				$user=$this->model->User->read(['id'=>$userId]);
				if (isset($user[0]))
				{
					$query=
					[
						'node_id'			=>$nodeId,
						'user_id'			=>$userId
					];
					$result=$this->model->NodeRating->read($query);
					if (!isset($result[0]))
					{
						$query['rating']=$rating;
						$this->model->NodeRating->insert($query);
						return true;
					}
					else
					{
						throw new NutshellException('A user cannot rate a node more than once.');
					}
				}
				else
				{
					throw new MvcException('Cannot rate node. Could not find user.');
				}
			}
			else
			{
				throw new MvcException('Cannot rate node. Could not find node.');
			}
		}
				
	}
}
?>