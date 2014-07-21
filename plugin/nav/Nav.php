<?php
namespace application\nutsNBolts\plugin\nav
{
	use nutshell\Nutshell;
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;

	class Nav extends Plugin implements Singleton
	{
		private $model	=null;

		public function init()
		{
			if ($connection=Nutshell::getInstance()->config->plugin->Mvc->connection)
			{
				$this->model=$this->plugin->Mvc->model;
			}
		}
		
		public function getById($id)
		{
			$nav=$this->model->Nav->getWithParts($id);
			if (is_array($nav))
			{
				for ($i=0,$j=count($nav['items']); $i<$j; $i++)
				{
					$originalURL=$nav['items'][$i]['url'];
					unset($nav['items'][$i]['url']);
					$nav['items'][$i]['url']=[];
					if (!empty($nav['items'][$i]['page_id']))
					{
						$page=$this->model->Page->read(['id'=>$nav['items'][$i]['page_id']]);
						if (isset($page[0]))
						{
							$URLs=$this->model->PageMap->read(['page_id'=>$page[0]['id']]);
							for ($k=0,$l=count($URLs); $k<$l; $k++)
							{
								$nav['items'][$i]['url'][]=$URLs[$k]['url'];
							}
						}
						else
						{
							$nav['items'][$i]['url'][]='#';
						}
					}
					else if (!empty($nav['items'][$i]['node_id']))
					{
						$node=$this->model->Page->read(['id'=>$nav['items'][$i]['node_id']]);
						if (isset($node[0]))
						{
							$URLs=$this->model->NodeMap->read(['node_id'=>$node[0]['id']]);
							for ($k=0,$l=count($URLs); $k<$l; $k++)
							{
								$nav['items'][$i]['url'][]=$URLs[$k]['url'];
							}
						}
						else
						{
							$nav['items'][$i]['url'][]='#';
						}
					}
					elseif (!empty($originalURL))
					{
						$nav['items'][$i]['url'][]=$originalURL;
					}
					else
					{
						$nav['items'][$i]['url'][]='#';
					}
				}
				return $nav;
			}
			return false;
		}
		
		public function getByRef($ref)
		{
			return $this->getById($ref);
		}
	}
}