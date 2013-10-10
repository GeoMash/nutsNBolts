<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use nutshell\helper\ObjectHelper;
	
	class Messages extends AdminController
	{
		public function init()
		{
			$url=$this->request->getNodes();
			echo count($url);
			die();
			$userId=$this->plugin->UserAuth->getUserId();
			
			switch ($action)
			{
				case 'viewList':
					$this->viewList($userId);
				break;
				
				case 'view':
					$this->view($userId);
				break;
			}
			
		}
		private function view()
		{			
		}
		
		private function viewAll($id)
		{
			$this->setContentView('admin/messages');
			$this->view->setVar('messages',$this->plugin->Mvc->model->User->read($userId));
			$this->addBreadcrumb('Messages','icon-inbox','messages');
			$this->view->render();			
		}		
	}
}
?>