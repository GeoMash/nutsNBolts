<?php
namespace application\nutsNBolts\controller
{
	use application\nutsNBolts\base\Controller;
	use nutshell\helper\ObjectHelper;
	use \DateTime;
	
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
		public $cache		=true;
		public function index()
		{
			$binding		=$this->application->NutsNBolts->getSiteBinding($this->getSiteRef());
			$applicationName=strtolower(ObjectHelper::getBaseClassName(get_class($binding['application'])));
			$path			=$this->getPath();
			$page			=$this->model->PageMap->getPageFromPath($path);


			if(isset($page['ref']))
			{
				$pageRef=str_replace('/', '_', $page['ref']);
				$this->loadHooks($pageRef);
				$this->loadCustomWidgets($pageRef);
			}
			

			if ($page)
			{
				$this->page		=$page;
				$this->pageType	=$this->model->PageType->read(array('id'=>$this->page['page_type_id']))[0];
				$this->nodes	=$this->model->NodeMap->getNodesForPath($path);
				$this->viewPath	='..'._DS_.'..'._DS_.$applicationName._DS_.'view'._DS_;

				$this->execHook('onInitPage',$this->page);
				$this->view->setTemplate($this->viewPath.'page'._DS_.$this->pageType['ref']._DS_.'index');
				$this->view->setVar('page',$this->page);
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
				)->registerCallback
				(
					'getComments',
					function($nodeId,$limit)
					{
						return $this->getComments($nodeId,$limit);
					}
				)->registerCallback
				(
					'getTags',
					function($nodeId)
					{
						return $this->getTags($nodeId);
					}
				)->registerCallback
				(
					'getNodesByTag',
					function($tags,$limit)
					{
						return $this->getNodesByTag($tags,$limit);
					}
				)->registerCallback
				(
					'getFacebookConfig',
					function()
					{
						return $this->getFacebookConfig();
					}
				)->registerCallback
				(
					'getBlogArticle',
					function($id)
					{
						return $this->plugin->Blog->getBlogArticle($id);
					}
				)->registerCallback
				(
					'getNextBlogArticle',
					function($blogId)
					{
						return $this->plugin->Blog->getNextBlogArticle($blogId);
					}
				)->registerCallback
				(
					'getPreviousBlogArticle',
					function($blogId)
					{
						return $this->plugin->Blog->getPreviousBlogArticle($blogId);
					}
				)->registerCallback
				(
					'getBlogger',
					function($id)
					{
						return $this->plugin->Blog->getBlogger($id);
					}
				)->registerCallback
				(
					'getBloggerCategories',
					function($id)
					{
						return $this->plugin->Blog->getBloggerCategories($id);
					}
				)->registerCallback
				(
					'getRecent',
					function($id,$limit)
					{
						return $this->plugin->Blog->getRecent($id,$limit);
					}
				)->registerCallback
				(
					'getBlogsByBlogger',
					function($bloggerId, $category, $min, $max)
					{
						return $this->plugin->Blog->getBlogsByBlogger($bloggerId, $category, $min, $max);
					}
				)->registerCallback
				(
					'countAllBlogs',
					function($id)
					{
						return $this->plugin->Blog->countAllBlogs($id);
					}
				)->registerCallback
				(
					'getAllDates',
					function($id)
					{
						return $this->getAllDates($id);
					}
				)->registerCallback
				(
				 	'getHomeTiles',
				 	function()
				 	{
				 		return $this->getHomeTiles();
				 	}
				 )->registerCallback
				 (
					'isAuthenticated',
					function()
					{
						return $this->plugin->UserAuth->isAuthenticated();
					}
				);

				$context=$this->view->getContext();
				$this->execHook('bindViewCallbacks',$context);
			}
			else
			{
				$this->view->setTemplate('site/404');
			}
			$requestVars=$this->request->getAll();
			if (count($requestVars))
			{
				$this->execHook('onFormSubmit',$requestVars,$this->page,$this->view);
			}
			$this->view->render();
			exit();
		}
		
		public function defineZone($config)
		{
			if(isset($config['typeConfig']['cache']))
			{
				$cache=false;
			}
			else
			{
				$cache=true;
			}
			if(!isset($config['typeConfig']['filter']))
			{
				$filter=false;
			}
			else
			{
				$filter=$config['typeConfig']['filter'];
			}
			if ($this->isValidZone($config))
			{
				//$config['typeConfig']['filter'] = true;
				switch ($config['type'])
				{
					case 'template':
					{
						list($scope,$template)=explode('.',$config['template']);
						print "\n<!-- [NB::START TEMPLATE::{$config['template']}] -->\n";
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
						print "\n<!-- [NB::END TEMPLATE::{$config['template']}] -->\n";
						break;
					}
					/**
					 * 
					 * Type Config:
					 * 
					 * query	- Ignores all other parameters and runs the SQL query.
					 * cache	- True by default. If false, ignores the cached $this->nodes array.
					 * filter	- 
					 * fields	- 
					 * orderBy	- 
					 * order	- 
					 * limit	- 
					 */
					case 'node':
					{
						$content=array();
						if (isset($config['typeConfig']))
						{
							//If query is set, ignore all other parameters.
							if (!empty($config['typeConfig']['query']))
							{
								if ($result=$this->plugin->db->nutsnbolts->select($config['typeConfig']['query']))
								{
									$content=$this->plugin->db->nutsnbolts->result('assoc');

									//$content=implode(',', $content);
									foreach ($content AS $tags)
									{
										$pageContent[]=$tags['tag'];
									}

									$content=$pageContent;
									//$content = implode(',', $pageContent);

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
									print "\n<!-- [NB::START TEMPLATE::{$config['template']}] -->\n";

									$this->view->getContext()->loadView
									(
										$template,
										$content
									);

									print "\n<!-- [NB::END TEMPLATE::{$config['template']}] -->\n";									
								}
							}
							//Else construct our own query.
							else
							{
								//Ignore cache.
								if (!isset($cache) || $cache==false)
								{
									$content=$this->model->Node->getWithParts($filter);

								}
								//Pull from cache.
								else
								{

									//TODO: get the id from the ref.

									// check to see if pagination has been set for this page
									if(isset($config['typeConfig']['paginate']))
									{ 
										$category		=null;
										$min			=null;
										$max			=null;
										// cehck to see if any extra options are set
										if(isset($config['typeConfig']['paginate']['options']))
										{
											// $category=null;
											$bloggerId=null;
											if (isset($config['typeConfig']['paginate']['options']['bloggerId']))
											{
												$bloggerId=$config['typeConfig']['paginate']['options']['bloggerId'];
											}
											if (isset($config['typeConfig']['paginate']['options']['category']))
											{
												$category=$config['typeConfig']['paginate']['options']['category'];
											}
											if (isset($config['typeConfig']['paginate']['options']['dateRange']))
											{
												$min=$config['typeConfig']['paginate']['options']['dateRange']['min'];
												$max=$config['typeConfig']['paginate']['options']['dateRange']['max'];
											}
										}
										// grab all the nodes in the zone
										if($allContent=$this->plugin->Blog->getBlogsByBlogger($bloggerId, $category, $min, $max))
										{
											// get the latest first
											$allContent=array_reverse($allContent);
										}
										else
										{
											$allContent=array();
										}
										// create an empty array to dump the paginated data in
										$filteredContent=array();
										// read the limit (the number of items per page)
										$limit=$config['typeConfig']['paginate']['limit'];
										// get the page number, we deduct 1 from the number to keep it in sync with the array index starting from 0
										$page=$config['typeConfig']['paginate']['page']-1;
										// get the low range of the array
										$low=$page*$limit;
										// get the high range of the array
										$high=$low+$limit-1;
										// get the total number of results
										$total=count($allContent);
										for ($i=$low;$i<=$high; $i++)
										{
											if(isset($allContent[$i]))
											{
												if($total<=$high)
												{
													// should not show the paginator links
													$filteredContent[]=$allContent[$i];	
													end($filteredContent);
													$key=key($filteredContent);
													$filteredContent[$key]=array_merge($filteredContent[$key],array(
														'showPaginate'		=> 'no'
													));													
												}
												else
												{
													// there are more results and it is safe to show the paginator links
													$filteredContent[]=$allContent[$i];
													end($filteredContent);
													$key=key($filteredContent);
													$filteredContent[$key]=array_merge($filteredContent[$key],array(
														'showPaginate'		=> 'yes'
													));
												}
											}
										}
									}
									else
									{
										$filteredContent=$this->getNodesByContentTypeRef($config['typeConfig']['ref']);	
									}
									if (!count($filteredContent))
									{	
										return '';
									}
									//Multiple of the same content type.
									if ( isset($config['typeConfig']['limit']) && $config['typeConfig']['limit'] > 0)
									{
										$content=$this->getNodesByContentTypeRefLimit($config['typeConfig']['ref'], $config['typeConfig']['limit']);
									}
									//Singular item.
									else
									{
										$content=$filteredContent;
										// $content=array();
										//If an ID is set, return that if it exists.
										// if (isset($config['typeConfig']['id']))
										// {

											// for ($i=0,$j=count($filteredContent); $i<$j; $i++)
											// {
											// 	if ($filteredContent[$i]['ref']==$config['typeConfig']['ref'])
											// 	{
											// 		$content[]=$filteredContent[$i];
											// 		break;
											// 	}
											// }
										// }
										//Else return the first.
										// else
										// {
										// 	$content[]=$filteredContent[0];
										// }
										if (!count($content))
										{
											return 'INVALID ZONE - CONTENT ITEM COULD NOT BE FOUND';
										}
									}
									
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
								print "\n<!-- [NB::START TEMPLATE::{$config['template']}] -->\n";
								for ($i=0,$j=count($content); $i<$j; $i++)
								{
									$this->view->getContext()->loadView
									(
										$template,
										$content[$i]
									);
								}
								print "\n<!-- [NB::END TEMPLATE::{$config['template']}] -->\n";
							}
						}
						else
						{
							return 'INVALID ZONE - NO TEMPLATE TYPE CONFIG FOR TYPE "'.$config['typeConfig'].'"';
						}

						// for ($i=0,$j=count($content); $i<$j; $i++)
						// {
						// 	$this->view->getContext()->loadView
						// 	(
						// 		$template,
						// 		$content[$i]
						// 	);
						// }
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
			$result=$this->model->ContentType->read(array('ref'=>$ref,'status'=>1));
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

		private function getNodesByContentTypeRefLimit($ref, $limit)
		{
			$return=array();
			$thisLimit=1;
			$result=$this->model->ContentType->read(array('ref'=>$ref,'status'=>1));

			if (!isset($result[0]))
			{
				return $return;
			}
			for ($i=0,$j=count($this->nodes); $i<$j; $i++)
			{
				if ($this->nodes[$i]['content_type_id']==$result[0]['id'])
				{
					if ($thisLimit <= $limit)
					{
						$return[]=&$this->nodes[$i];		
						$thisLimit++;
					}
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
		
		public function getComments($nodeId,$limit)
		{
			$comments=$this->model->NodeComment->read(array('node_id'=>$nodeId),array(),'LIMIT '.$limit);
			for ($i=0,$j=count($comments); $i<$j; $i++)
			{
				$comments[$i]['date_created']=new DateTime($comments[$i]['date_created']);
			}
			return $comments;
		}

		public function getTags($nodeId)
		{
			$tags=$this->model->NodeTag->read(array('node_id'=>$nodeId));
			return $tags;
		}

		public function getNodesByTag($tags,$limit)
		{
			$tags=$this->model->NodeTag->read(array('tag'=>$tags),array(),'LIMIT '.$limit);
			return $tags;
		}

		public function getFacebookConfig()
		{
			return $this->config->plugin->FaceBook->app_id;
		}

		public function getAllDates($id)
		{
			$countedDates=null;
			$dates=$this->plugin->Blog->getAllDates($id);
			if($dates)
			{
				$dates=array_reverse($dates);
				$allDates=array();
				$countedDates=array();
				foreach($dates AS $key=>$date)
				{
					$allDates[]=$newDate=$date['date_created']->format("F Y");
				}

				foreach($allDates AS $key=>$secondDate)
				{
					if (isset($countedDates[$secondDate]))
					{
						$countedDates[$secondDate]+=1;
					}
					else
					{
						$countedDates[$secondDate]=1;
					}
				}				
			}

			return $countedDates;
		}



		/*
		 * Hooks - WIP
		 */

		private $hookContainers=array();

		public function loadHooks($ref=null)
		{
			$ref=ucfirst($ref);
			foreach ($this->application->getLoaded() as $applicationRef=>$application)
			{
				$this->loadGlobalHooks($applicationRef);
				$this->loadFormHooks($applicationRef);
				if (!$ref)continue;
				$className=$this->application->getNamespace($applicationRef).'\hook\\'.$ref;
				$path=APP_HOME.lcfirst($applicationRef)._DS_.'hook'._DS_.$ref.'.php';
				if (is_file($path))
				{
					require_once($path);
					if (class_exists($className))
					{
						if (!is_array($this->hookContainers[$applicationRef]))
						{
							$this->hookContainers[$applicationRef]=array();
						}
						$this->hookContainers[$applicationRef][$ref]=new $className($this->model,$this->view);
					}
					else
					{
						//TODO: Throw exception re bad hook class name
					}
				}
			}
		}

		private function loadGlobalHooks($applicationRef)
		{
			$className=$this->application->getNamespace($applicationRef).'\hook\_Global';
			$path=APP_HOME.lcfirst($applicationRef)._DS_.'hook'._DS_.'_Global.php';
			if (is_file($path))
			{
				require_once($path);
				if (class_exists($className))
				{
					if ( isset($this->hookContainers[$applicationRef]) && !is_array($this->hookContainers[$applicationRef]))
					{
						$this->hookContainers[$applicationRef]=array();
					}
					$this->hookContainers[$applicationRef]['_Global']=new $className($this->model,$this->view);
				}
				else
				{
					//TODO: Throw exception re bad hook class name
				}
			}
		}



		private function loadFormHooks($applicationRef)
		{
			$className=$this->application->getNamespace($applicationRef).'\hook\_Forms';
			$path=APP_HOME.lcfirst($applicationRef)._DS_.'hook'._DS_.'_Forms.php';
			if (is_file($path))
			{
				require_once($path);
				if (class_exists($className))
				{
					if ( isset($this->hookContainers[$applicationRef]) && !is_array($this->hookContainers[$applicationRef]))
					{
						$this->hookContainers[$applicationRef]=array();
					}
					$this->hookContainers[$applicationRef]['_Forms']=new $className($this->model,$this->view);
				}
				else
				{
					//TODO: Throw exception re bad hook class name
				}
			}
		}

		public function execHook($hook,&$a=null,&$b=null,&$c=null,&$d=null,&$e=null,&$f=null,&$g=null,&$h=null,&$i=null,&$j=null,&$k=null)//($hook,&$args)
		{
			$table	=array_merge(array('hook'),range('a','k'));
			$xargs	=array();
			$args	=func_get_args();
			for ($ci=1,$cj=func_num_args(); $ci<$cj; $ci++)
			{
				$xargs[]=&$args[$ci];
			}
			foreach ($this->application->getLoaded() as $applicationRef=>$application)
			{
				if (!isset($this->hookContainers[$applicationRef]))
				{
					continue;
				}
				foreach ($this->hookContainers[$applicationRef] as $container)
				{
					if (method_exists($container,$hook))
					{
						call_user_func_array(array($container,$hook),$xargs);
					}
				}
			}
			for ($ci=1,$cj=func_num_args(); $ci<$cj; $ci++)
			{
				${$table[$ci]}=$xargs[$ci-1];
			}
		}
		
		/*
			Loading custom application widgets
		*/
		
		// create empty array	
		private $widgetContainers=array();
		public function loadCustomWidgets($ref=null)
		{	
			
			$ref=ucfirst($ref);
			foreach ($this->application->getLoaded() as $applicationRef=>$application)
			{
				if($applicationRef != "NutsNBolts")
				{
					if (!$ref)continue;
					$className=$this->application->getNamespace($applicationRef).'\widget\\'.strtolower($ref).'\\'.$ref;
					$path=APP_HOME.lcfirst($applicationRef)._DS_.'widget'._DS_.strtolower($ref)._DS_.$ref.'.php';
					if (is_file($path))
					{
						require_once($path);	
						if (class_exists($className))
						{
							if (!is_array($this->widgetContainers))
							{
								$this->widgetContainers[$applicationRef]=array();
							}
							$this->widgetContainers[$applicationRef][$ref]=new $className($this->model,$this->view);
						}
						else
						{
							//TODO: Throw exception re bad widget class name
						}
					}					
				}

			}
		}			
		
		public function getHomeTiles()
		{
			$tiles=$this->model->Node->getHomeTiles();
			return $tiles;
		}		
	}
}
?>