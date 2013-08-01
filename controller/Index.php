<?php
namespace application\nutsnbolts\controller
{
	use application\nutsnbolts\base\Controller;
	
	class Index extends Controller
	{
		private $validZones=array
		(
			'template',
			'node',
			'nav',
			'widget'
		);
		
		public $page	=null;
		public $nodes	=null;
		
		public function index()
		{
			$path=$this->getPath();
			$page=$this->model->Page->read(array('url'=>$path));
			if (count($page))
			{
				$this->page		=$page[0];
				$this->nodes	=$this->model->NodeMap->getNodesForPath($path);
				
				$this->view->setTemplate('site/page/'.$this->page['page_type_id'].'/index');
				$this->view->setVar('NS_ENV',NS_ENV);
				$this->view->setVar('SITEPATH','/sites/1/');
				$this->view->setVar('node',$this->nodes);
				
				$this->view->getContext()
				->registerCallback
				(
					'defineZone',
					function($config)
					{
						print $this->defineZone($config);
					}
				)->registerCallback
				(
					'getNode',
					function($filter)
					{
						return $this->getNode($filter);
					}
				);
			}
			else
			{
				$this->view->setTemplate('site/404');
			}
			$this->view->render();
			exit();
		}
		
		public function defineZone($config)
		{
			if ($this->isValidZone($config))
			{
				switch ($config['type'])
				{
					case 'template':
					{
						list($scope,$template)=explode('.',$config['template']);
						if ($scope=='local')
						{
							$this->view->getContext()->loadView
							(
								'site/page/'.$this->page['page_type_id'].'/block/'.$template
							);
						}
						else if ($scope=='global')
						{
							$this->view->getContext()->loadView
							(
								'site/block/'.$template
							);
						}
						else
						{
							return 'INVALID TEMPLATE SCOPE';
						}
						
						break;
					}
					case 'node':
					{
						if (isset($config['typeConfig']))
						{
							$filteredContent=$this->getNodesByContentType($config['typeConfig']['type']);
							//Multiple of the same content type.
							if ($config['typeConfig']['multiple'])
							{
								$content=$filteredContent;
							}
							//Singular item.
							else
							{
								$content=array();
								//If an ID is set, return that if it exists.
								if (isset($config['typeConfig']['id']))
								{
									for ($i=0,$j=count($filteredContent); $i<$j; $i++)
									{
										if ($filteredContent[$i]['id']==$config['typeConfig']['id'])
										{
											$content[]=$filteredContent[$i];
											break;
										}
									}
								}
								//Else return the first.
								else
								{
									$content[]=$filteredContent[0];
								}
								if (!count($content))
								{
									return 'INVALID ZONE - CONTENT ITEM COULD NOT BE FOUND';
								}
							}
						}
						else
						{
							return 'INVALID ZONE - NO TEMPLATE TYPE CONFIG FOR TYPE "content"';
						}
						list($scope,$template)=explode('.',$config['template']);
						if ($scope=='local')
						{
							$template='site/page/'.$this->page['page_type_id'].'/block/'.$template;
						}
						else if ($scope=='global')
						{
							$template='site/block/'.$template;
						}
						else
						{
							return 'INVALID TEMPLATE SCOPE';
						}
						for ($i=0,$j=count($content); $i<$j; $i++)
						{
							$this->view->getContext()->loadView
							(
								$template,
								$content[$i]
							);
						}
						break;
					}
					case 'nav':
					{
						
						break;
					}
					case 'widget':
					{
						
						break;
					}
				}
			}
			else
			{
				return 'INVALID ZONE TYPE';
			}
		}
		
		private function getPath()
		{
			$nodes=$this->request->getNodes();
			if (count($nodes)===1 && empty($nodes[0]))
			{
				return '/';
			}
			else
			{
				return '/'.implode('/',$nodes).'/';
			}
		}
		
		private function isValidZone($zone)
		{
			return ((isset($zone['type']) && in_array($zone['type'],$this->validZones))
					&& isset($zone['name']));
		}
		
		private function getNodesByContentType($type)
		{
			$return=array();
			for ($i=0,$j=count($this->nodes); $i<$j; $i++)
			{
				if ($this->nodes[$i]['content_type_id']==$type)
				{
					$return[]=&$this->nodes[$i];
				}
			}
			return $return;
		}
		
		public function getNode($filter)
		{
			//ID
			if (is_numeric($filter))
			{
				$return=array();
				for ($i=0,$j=count($this->nodes); $i<$j; $i++)
				{
					if ($this->nodes[$i]['id']==$filter)
					{
						return $this->nodes[$i];
					}
				}
			}
			//Search
			else if (is_array($filter))
			{
				$matches=$this->nodes;
				
				foreach ($filter as $key=>$value)
				{
					for ($i=0,$j=count($matches); $i<$j; $i++)
					{
						if (!isset($this->nodes[$i][$key])
						|| $this->nodes[$i][$key]!=$value)
						{
							unset($matches[$i]);
						}
					}
					sort($matches);
				}
				if (isset($matches[0]))
				{
					return $matches[0];
				}
			}
			return false;
		}
	}
}
?>