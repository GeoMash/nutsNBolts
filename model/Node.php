<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\Node as NodeBase;
	use nutshell\helper\ArrayHelper;
	use \DateTime;
	
	class Node extends NodeBase	
	{
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
					// die($key);
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
			for ($i=0,$j=count($record['url']); $i<$j; $i++)
			{
				$urls[]=array
				(
					'node_id'	=>$id,
					'url'		=>$record['url'][$i]
				);
			}
			unset($record['url']);
			return $urls;
		}
		
		private function extractTags(&$record)
		{
			$id		=(!empty($record['id']))?$record['id']:0;
			$tags	=explode(',',$record['tags']);
			$return	=array();
			for ($i=0,$j=count($tags); $i<$j; $i++)
			{
				$return[]=array
				(
					'node_id'	=>$id,
					'tag'		=>$tags[$i]
				);
			}
			unset($record['tags']);
			return $return;
		}
		
		
		
		public function getWithParts($whereKeyVals,$fields=array(),$limit=false,$offset=false,$orderBy='order',$order='ASC')
		{
			$where=array();
			foreach ($whereKeyVals as $field=>$value)
			{
				$where[]=<<<SQL
				(
					content_part.ref="{$field}"
					AND
					node_part.value="{$value}"
				)
SQL;
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
			AND node.status=1

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
			SELECT node.*,content_part.label,content_part.ref,node_part.value,content_type_user.user_id
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			WHERE node.id={$id}
			AND node.status=1
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
				AND node.status=1
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
				AND node.status=1
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

		public function getBlogsByBlogger($bloggerId, $category)
		{
			if(strlen($category) > 3)
			{
				$where=<<<SQL
				AND node_part.value="{$category}"
SQL;
			}
			else
			{
				$where="";
			}
			$query=<<<SQL
			SELECT node.*,content_part.label,content_part.ref,node_part.value,content_type_user.user_id
			FROM node
			LEFT JOIN node_part ON node.id=node_part.node_id
			LEFT JOIN content_part ON node_part.content_part_id=content_part.id
			LEFT JOIN content_type_user ON node.content_type_id=content_type_user.content_type_id
			WHERE content_type_user.user_id={$bloggerId}
			AND node.status=1
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
			AND node.status=1
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
			AND node.status=1
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
	}
}
?>