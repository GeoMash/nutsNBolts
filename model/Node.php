<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\Node as NodeBase;
	use nutshell\helper\ArrayHelper;
	use \DateTime;
	
	class Node extends NodeBase	
	{
		const STATUS_SAVED		=0;
		const STATUS_SUBMITTED	=1;
		const STATUS_PUBLISHED	=2;
		const STATUS_DELETED	=3;





		public function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				$nodeParts		=$this->extractContentParts($record);
				$nodeURLs		=$this->extractURLs($record);
				$nodeTags		=$this->extractTags($record);
				$return			=$this->update($this->removeJunk($record),array('id'=>$record['id']));
				//Update parts.
				for ($i=0,$j=count($nodeParts); $i<$j; $i++)
				{
					//For Update
					if ($nodeParts[$i]['id']!==0)
					{
						$this->model->NodePart->update($nodeParts[$i],array('id'=>$nodeParts[$i]['id']));
						$return=true;
					}
					//For Insert
					else
					{
						$this->model->NodePart->insertAssoc($nodeParts[$i]);
					}
				}
				//Update URLs
				$this->model->NodeMap->delete(array('node_id'=>$record['id']));
				for ($i=0,$j=count($nodeURLs); $i<$j; $i++)
				{
					$this->model->NodeMap->insertAssoc($nodeURLs[$i]);
				}
				//Update Tags
				$this->model->NodeTag->delete(array('node_id'=>$record['id']));
				for ($i=0,$j=count($nodeTags); $i<$j; $i++)
				{
					$this->model->NodeTag->insertAssoc($nodeTags[$i]);
				}

				return $return;
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$nodeParts	=$this->extractContentParts($record);
				$nodeURLs	=$this->extractURLs($record);
				$nodeTags	=$this->extractTags($record);
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
		
		private function extractURLs(&$record)
		{
			$urls=array();
			$id=(!empty($record['id']))?$record['id']:0;
			if(isset($record['url']))
			{
				for ($i=0,$j=count($record['url']); $i<$j; $i++)
				{
					$urls[]=array
					(
						'node_id'	=>$id,
						'url'		=>$record['url'][$i]
					);
				}
				unset($record['url']);
			}
			return $urls;
		}
		
		private function extractTags(&$record)
		{
			// foreach ($record AS $key=>$rec)
			// {
			// 	if(preg_match('/$application\/json:/',$rec))	
			// 	{
			// 		$record[$key]=json_decode($rec);
			// 	}
			// }

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
		
		public function getWithParts($whereKeyVals,$fields=array(),$limit=false,$offset=false,$orderBy='order',$order='ASC')
		{
			$where=array();
			foreach ($whereKeyVals as $field=>$value)
			{
				$where[]=<<<SQL_PART
				(
					content_part.ref="{$field}"
					AND
					node_part.value="{$value}"
				)
SQL_PART;
			}
			$where=implode(' AND ',$where);
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
				LEFT JOIN content_part ON node_part.content_part_id=content_part.id
				WHERE {$where}
			)
			ORDER BY node.id ASC;
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
			else
			{
			}
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
			AND node.status=2
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
			if(strlen($category) > 3)
			{
				$where=<<<SQL_PART
				AND node_part.value="{$category}"
SQL_PART;
			}
			if(strlen($min) > 3)
			{
				$where=<<<SQL_PART
				AND node.date_created BETWEEN "{$min}" AND "{$max}"
SQL_PART;
			}
			$query=<<<SQL
			SELECT node.*,content_part.ref,node_part.value,content_type_user.user_id
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			WHERE content_type_user.user_id={$bloggerId}
			AND node.status=2
			{$where}
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
				
	}
}
?>