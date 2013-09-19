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

										// grab all the nodes in the zone
										$allContent=$this->getNodesByContentTypeRef($config['typeConfig']['ref']);	
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
	}
}
?>