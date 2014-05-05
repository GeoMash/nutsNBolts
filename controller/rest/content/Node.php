<?php
namespace application\nutsNBolts\controller\rest\content
{
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
			'{int}'							=>'getById',
			'getByTag/{string}'				=>'getByTag'
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
	}
}
?>