<?php
namespace application\nutsnbolts\controller
{
	use application\nutsnbolts\base\Controller;
	use nutshell\helper\ObjectHelper;
	
	class Index extends Controller
	{
		private $validZones=array
		(
			'template',
			'node',
			'nav',
			'widget'
		);
		
		public $page		=null;
		public $viewPath	=null;
		public $nodes		=null;
		private $site		=null;
		
		public function index()
		{
			$result=$this->model->site->read(array('domain'=>$_SERVER['HTTP_HOST']));
			if (isset($result[0]))
			{
				$this->site=$result[0];
			}
			else
			{
				die('No site registered for this domain!');
			}
			$binding=$this->application->nutsnbolts->getSiteBinding($this->site['ref']);
			if (!$binding)
			{
				die('No site bound for this domain!');
			}
			$applicationName=strtolower(ObjectHelper::getBaseClassName(get_class($binding['application'])));
			$path			=$this->getPath();
			$page			=$this->model->Page->read(array('url'=>$path));
			
			if (count($page))
			{
				$this->page		=$page[0];
				$this->nodes	=$this->model->NodeMap->getNodesForPath($path);
				$this->viewPath	='..'._DS_.'..'._DS_.$applicationName._DS_.'view'._DS_;
				
				$this->view->setTemplate($this->viewPath.'page'._DS_.$this->page['ref']._DS_.'index');
				$this->view->setVar('NS_ENV',NS_ENV);
				$this->view->setVar('SITEPATH','/sites/'.$this->site['ref'].'/');
				$this->view->setVar('node',$this->nodes);
				
				$scope		=$this->view;
				$viewPath	=$this->viewPath;
				$this->view->getContext()->registerCallback
				(
					'loadView',
					function($viewName,$viewKeyVals=array(),$print=true) use ($scope,$viewPath)
					{
						$template=$scope->plugin->Template($scope->buildViewPath($viewPath.$viewName));
						$scope->templateContext->setKeyValArray($viewKeyVals);
						$template->setContext($scope->templateContext);
						if ($print)
						{
							print $template->compile();
						}
						else
						{
							return $template->compile();
						}
					}
				)->registerCallback
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
								'page'._DS_.$this->page['ref'].'/block/'.$template
							);
						}
						else if ($scope=='global')
						{
							$this->view->getContext()->loadView
							(
								'block'._DS_.$template
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