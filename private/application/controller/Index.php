<?php
namespace application\controller
{
	use application\base\Controller;
	
	class Index extends Controller
	{
		private $validZones=array
		(
			'template',
			'content',
			'nav',
			'widget'
		);
		
		public $page	=null;
		public $content	=null;
		
		public function index()
		{
			$path=$this->getPath();
			$page=$this->model->Page->read(array('url'=>$path));
			if (count($page))
			{
				$this->page		=$page[0];
				$this->content	=$this->model->NodeMap->getNodesForPath($path);
				
				$this->view->setTemplate('site/page/'.$this->page['page_type_id'].'/index');
				$this->view->setVar('NS_ENV',NS_ENV);
				$this->view->setVar('SITEPATH','/sites/1/');
				
				
				$this->view->getContext()
				->registerCallback
				(
					'defineZone',
					function($config)
					{
						print $this->defineZone($config);
					}
				);
				
				$this->view->render();
				exit();
			}
			die('404');
			
			
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
				return implode('/',$nodes).'/';
			}
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
					case 'content':
					{
						if (isset($config['typeConfig']))
						{
							$filteredContent=$this->getContentByType($config['typeConfig']['type']);
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
		
		private function isValidZone($zone)
		{
			return ((isset($zone['type']) && in_array($zone['type'],$this->validZones))
			        && isset($zone['name']));
		}
		
		private function getContentByType($type)
		{
			$return=array();
			for ($i=0,$j=count($this->content); $i<$j; $i++)
			{
				if ($this->content[$i]['content_type_id']=$type)
				{
					$return[]=&$this->content[$i];
				}
			}
			return $return;
		}
	}
}
?>