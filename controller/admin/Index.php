<?php
namespace application\nutsnbolts\controller\admin
{
	use application\nutsnbolts\base\AdminController;
	use nutshell\helper\ArrayHelper;
	// use application\nutsnbolts\controller\admin\ConfigureContent;
	// use application\nutsnbolts\controller\admin\Content;
	// use application\nutsnbolts\controller\admin\Dashboard;
	
	class Index extends AdminController
	{
		private $routedController	=null;
		
		public function index()
		{
			$this->route();
		}
		
		private function route()
		{
			$this->MVC=$this->plugin->Mvc;
			switch ($this->request->node(1))
			{
				case 'script':
				{
					$this->routedController=new Script($this->MVC);
					break;
				}
				case 'template':
				{
					$this->template();
					return;
				}
				case 'content':
				{
					$this->routedController=new Content($this->MVC);
					break;
				}
				case 'configurePages':
				case 'configurepages':
				{
					$this->routedController=new ConfigurePages($this->MVC);
					break;
				}
				case 'configureContent':
				case 'configurecontent':
				{
					$this->routedController=new ConfigureContent($this->MVC);
					break;
				}
				case 'fileManager':
				case 'filemanager':
				{
					$this->routedController=new FileManager($this->MVC);
					break;
				}
				case 'settings':
				{
					$this->routedController=new Settings($this->MVC);
					break;
				}
				case '':
				case 'dashboard':
				{
					$this->routedController=new Dashboard($this->MVC);
					break;
				}
				default:
				{
					$this->view->render();
					exit();
				}
			}
			//Chek for action.
			$action	=$this->request->node(2);
			$args	=array();
			$node	=3;
			//Check for args.
			if (!is_null($action))
			{
				while (true)
				{
					//grab the next node
					$arg = $this->request->node($node++);
					
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
				array($this->routedController,$action),
				$args
			);
		}
		
		public function template()
		{
			$nodes=$this->request->getNodes();
			ArrayHelper::without
			(
				$nodes,
				array
				(
					$this->request->node(0),
					$this->request->node(1)
				)
			);
			$templatePath	='admin/'.implode('/',$nodes);
			// $template		=$this->plugin->Template();
			// $template->setTemplate($this->view->buildViewPath($templatePath));
			
			switch ($templatePath)
			{
				case 'admin/configureContent/addWidgetSelection':
				{
					$html=$this->buildWidgetHTML
					(
						$this->application->nutsnbolts->getWidgetList(),
						$this->request->get('index')
					);
					break;
				}
				case 'admin/fileManager/collections':
				{
					$template=$this->plugin->Template();
					$template->setTemplate($this->view->buildViewPath('admin/fileManager/collections'));
					$template->setKeyVal('collections',$this->model->Collection->read());
					$html=$template->compile();
					break;
				}
				case 'admin/fileManager/files':
				{
					$template=$this->plugin->Template();
					$template->setTemplate($this->view->buildViewPath('admin/fileManager/files'));
					
					$collection=$this->model->Collection->read($this->request->get('id'))[0];
					$template->setKeyVal('collectionName',$collection['name']);
					
					$fileList=$this->plugin->FileSystem->getFileListFromCollection($this->request->get('id'));
					
					$template->setKeyVal('files',$fileList);
					
					$html=$template->compile();
					break;
				}
			}
			$this->plugin	->Responder('html')
							->setData($html)
							->send();
		}
	}
}
?>