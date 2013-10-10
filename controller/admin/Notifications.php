<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use nutshell\helper\ObjectHelper;
	
	class Notifications extends AdminController
	{
		public function init()
		{
			$action=$this->request->node(2);
			
			switch ($action)
			{
				case 'viewList':
					$this->viewList();
				break;
				
				case 'view':
					$this->view($id);
				break;
			}
			
		}
		private function view()
		{			
		}
		
		private function viewAll($id)
		{
		}		
	}
}
?>