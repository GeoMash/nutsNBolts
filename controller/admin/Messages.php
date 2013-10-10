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
			$userId=$this->plugin->UserAuth->getUserId();
			
			switch (count($url))
			{
				case '2':
					$this->viewAll($userId);
				break;
				
				case '3':
					$messageId=$url[2];
					$this->view($userId,$messageId);
				break;
			}
			
		}
		private function view()
		{			
		}
		
		private function viewAll($id)
		{
			$search=array
			(
			 	'to_user_id'	=>$id
			 );
			$this->setContentView('admin/messages');
			$this->view->setVar('messages',$this->plugin->Mvc->model->Message->read($search));
			$this->addBreadcrumb('Messages','icon-inbox','messages');
			$this->view->render();			
		}		
	}
}
?>