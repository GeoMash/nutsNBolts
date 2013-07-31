<?php
namespace application\controller\admin
{
	use application\base\AdminController;
	use application\controller\admin\ConfigureContent;
	use application\controller\admin\Content;
	use application\controller\admin\Dashboard;
	
	class Index extends AdminController
	{
		private $routedController	=null;
		
		public function index()
		{
			$this->route();
			// $this->dashboard();
		}
		
		private function route()
		{
			$this->MVC=$this->plugin->Mvc;
			switch ($this->request->node(1))
			{
				case 'dashboard':
				{
					$this->routedController=new Dashboard($this->MVC);
					break;
				}
				case 'content':
				{
					$this->routedController=new Content($this->MVC);
					break;
				}
				case 'configurepages':
				{
					$this->routedController=new ConfigurePages($this->MVC);
					break;
				}
				case 'configurecontent':
				{
					$this->routedController=new ConfigureContent($this->MVC);
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
		
		public function dashboard()
		{
			$this->setContentView('admin/dashboard');
			$this->addBreadcrumb('Dashboard','icon-dashboard');
			$this->view->render();
		}
		
		public function articles()
		{
			$this->addBreadcrumb('Articles','icon-list');
			
			$this->view->render();
		}
	}
}
?>