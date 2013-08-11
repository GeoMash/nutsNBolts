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
		public $pageType	=null;
		public $viewPath	=null;
		public $nodes		=null;
		private $site		=null;
		
		public function index()
		{
			$binding		=$this->application->nutsnbolts->getSiteBinding($this->getSiteRef());
			$applicationName=strtolower(ObjectHelper::getBaseClassName(get_class($binding['application'])));
			$path			=$this->getPath();
			$page			=$this->model->PageMap->getPageFromPath($path);
			if ($page)
			{
				$this->page		=$page;
				$this->pageType	=$this->model->PageType->read(array('id'=>$this->page['page_type_id']))[0];
				$this->nodes	=$this->model->NodeMap->getNodesForPath($path);
				$this->viewPath	='..'._DS_.'..'._DS_.$applicationName._DS_.'view'._DS_;
				
				$this->view->setTemplate($this->viewPath.'page'._DS_.$this->pageType['ref']._DS_.'index');
				$this->view->setVar('NS_ENV',NS_ENV);
				$this->view->setVar('SITEPATH','/sites/'.$this->getSite()['ref'].'/');
				$this->view->setVar('URI',$this->request->getNodes());
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
								'page'._DS_.$this->pageType['ref'].'/block/'.$template
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
							//TODO: get the id from the ref.
							$filteredContent=$this->getNodesByContentTypeRef($config['typeConfig']['ref']);
							if (!count($filteredContent))
							{
								return '';
							}
							//Multiple of the same content type.
							if (isset($config['typeConfig']['multiple']) && $config['typeConfig']['multiple'])
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
										if ($filteredContent[$i]['ref']==$config['typeConfig']['ref'])
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
							return 'INVALID ZONE - NO TEMPLATE TYPE CONFIG FOR TYPE "'.$config['typeConfig'].'"';
						}
						list($scope,$template)=explode('.',$config['template']);
						if ($scope=='local')
						{
							$template='page/'.$this->pageType['ref'].'/block/'.$template;
						}
						else if ($scope=='global')
						{
							$template='block/'.$template;
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
		
		private function getNodesByContentTypeRef($ref)
		{
			$return=array();
			$result=$this->model->contentType->read(array('ref'=>$ref));
			if (!isset($result[0]))
			{
				return $return;
			}
			for ($i=0,$j=count($this->nodes); $i<$j; $i++)
			{
				if ($this->nodes[$i]['content_type_id']==$result[0]['id'])
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
				//Fallback to DB
				if ($record=$this->model->Node->read($filter))
				{
					return $record[0];
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
				//Fallback to DB
				
			}
			return false;
		}
	}
}
?>