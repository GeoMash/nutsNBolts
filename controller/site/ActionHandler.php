<?php
namespace application\nutsNBolts\controller\site
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	
	class ActionHandler extends Controller
	{
		public function index()
		{
			$this->route();
		}
		
		private function route()
		{
			$this->MVC=$this->plugin->Mvc;
			switch ($this->request->node(1))
			{
				case 'form':
				{
					$this->routedController=new Form($this->MVC);
					break;
				}
				case 'comment':
				{
					$this->routedController=new Comment($this->MVC);
					break;
				}
				case 'facebookLoginStoreEmail':
				{
					$this->routedController=new Facebook($this->MVC);
					break;
				}
				default:
				{
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
	}
}
?>
