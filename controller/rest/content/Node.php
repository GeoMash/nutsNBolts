<?php
namespace application\nutsNBolts\controller\rest\content
{
	use application\nutsNBolts\model\Node as NodeModel;
	use application\plugin\rest\RestController;

	class Node extends RestController
	{
		private $map=array
		(
//			'test1/{*}'						=>'test1',
//			'test2/[*]'						=>'test2',
//			'test3/{*}/[...]'				=>'test3',
//			'test4/{int}/[...]'				=>'test4',
//			'test5/{string}/[...]'			=>'test5',
//			'test6/[string]'				=>'test6',
//			'test7/[string]/[...]'			=>'test7',
//			'test8/{string}/{int}'			=>'test8',
//			'test9/{string}/{int}/[...]'	=>'test9',
			''								=>'getAll',
			'getWithParts/{int}/{int}/[*]'	=>'getWithParts',
			'getCount'						=>'getCount',
			'{int}'							=>'getById',
			'getByTag/{string}'				=>'getByTag',
			'search'						=>'search'
		);

		public function getById($id)
		{
			$node=$this->model->Node->getWithParts(array('id'=>$id));
			if (isset($node[0]))
			{
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					$node[0]
				);
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Node not found.'
				);
			}
		}
		
		public function getCount()
		{
			$this->setResponseCode(200);
			$this->respond
			(
				true,
				'OK',
				$this->model->Node->count($this->request->getAll())
			);
			return;
		}
		
		public function getByTag($tags)
		{
			$tags=explode(',',$tags);
			$this->setResponseCode(200);
			$this->respond
			(
				true,
				'OK',
				$this->model->Node->getByTags($tags)
			);
		}

		public function getAll()
		{
			$this->setResponseCode(200);
			$this->respond
			(
				true,
				'OK',
				$this->model->Node->getWithParts($this->request->getAll())
			);
			return;
			//Check for action.
			$action	=isset($request[2])?$request[2]:null;
			$args	=array();
			$node	=3;
			//Check for args.
			if (!is_null($action))
			{
				while (true)
				{
					//grab the next node
					$arg = $request[$node++];

					if(is_null($arg))
					{
						break;
					}
					//append to the args array
					$args[] = $arg;
				}
			}
			else
			{
				$action='index';
			}
			call_user_func_array
			(
				array($this->restController,$action),
				$args
			);


			$filter=array();
			if (!empty($contentType))
			{
				if (is_numeric($contentType))
				{
					$filter['content_type_id']=$contentType;
				}
				else if (is_string($contentType))
				{
					$filter['ref']=$contentType;
				}
				else
				{
					$this->setResponseCode(417);
					$this->respond
					(
						false,
						'Expected Content Type ID or REF.'
					);
				}
				die('222');
				try
				{
					$content=$this->model->Node->getWithParts($filter);
				}
				catch (\Exception $e)
				{
					var_dump($e);
					exit();
				}
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					$content
				);
			}
			else
			{
				$this->setResponseCode(417);
				$this->respond
				(
					false,
					'Expected Content Type ID or REF.'
				);
			}
		}
		
		public function getWithParts($offset=null,$limit=null,$status=NodeModel::STATUS_PUBLISHED)
		{
			$this->setResponseCode(200);
			$this->respond
			(
				true,
				'OK',
				$this->model->Node->getWithParts
				(
					$this->request->getAll(),
					$offset,
					$limit,
					$status
				)
			);
			return;
		}
		
		public function search()
		{
			$json=json_decode($this->request->getRaw(),true);
			if (!is_null($json))
			{
				if (isset($json['contentType']))
				{
					$search=$this->plugin->Search($json['contentType']);
					if (isset($json['filter']))
					{
						$search->addFilter($json['filter']);
					}
					if (isset($json['limit']))
					{
						$search->limit($json['limit']);
					}
					if (isset($json['offset']))
					{
						$search->offset($json['offset']);
					}
					if (isset($json['orderBy']))
					{
						$search->orderBy($json['orderBy']);
					}
					if (isset($json['joins']))
					{
						foreach ($json['joins'] as $contentType=>$column)
						{
							$search->joinWithContentType($contentType,$column);
						}
					}
					$search->clearCache();
					$this->respond
					(
						true,
						'OK',
						$search->execute()
					);
				}
				else
				{
					$this->setResponseCode(417);
					$this->respond
					(
						false,
						'Expected Content Type ID or REF.'
					);
				}
			}
			else
			{
				//TODO - Non-JSON request
				$this->setResponseCode(501);
				$this->respond
				(
					false,
					'Not implemented. Use a raw JSON request instead.'
				);
				exit();
				$contentType=$this->request->get('contentType');
				if ($contentType)
				{
					$search			=$this->plugin->Search($contentType);
					$filter			=$this->request->get('filter');
					$limit			=$this->request->get('limit');
					$offset			=$this->request->get('offset');
					$orderBy		=$this->request->get('orderBy');
					$orderDirection	=$this->request->get('orderBy');
					$joins			=$this->request->get('joins');
					if ($filter)
					{
						
					}
					if ($limit)
					{
						$search->limit($limit);
					}
					if ($offset)
					{
						$search->offset($offset);
					}
					if ($orderBy)
					{
						if (!$orderDirection)
						{
							$orderDirection='ASC';
						}
						$search->orderBy($orderBy,$orderDirection);
					}
					if ($joins)
					{
						
					}
					
				}
				else
				{
					$this->setResponseCode(417);
					$this->respond
					(
						false,
						'Expected Content Type ID or REF.'
					);
				}
			}
		}
	}
}
?>