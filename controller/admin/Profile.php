<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use nutshell\helper\ObjectHelper;
	
	class Profile extends AdminController
	{
		private $collectionID=null;
		public function init()
		{
			if(strlen($this->request->node(2))>0)
			{
				$action=$this->request->node(2);
			}
			else
			{
				$action='view';
			}
			
			switch ($action)
			{
				case 'view':
					$this->view();
				break;
				
				case 'edit':
					$this->edit();
				break;
			}
			
		}
		private function view()
		{
			$userId=$this->plugin->UserAuth->getUserId();
			$this->setContentView('admin/profile');
			$this->view->setVar('userDetails',$this->plugin->Mvc->model->User->read($userId));
			$this->addBreadcrumb('Profile','icon-edit','profile');
			// $this->view->render();			
		}
		
		private function edit()
		{
			$userId=$this->plugin->UserAuth->getUserId();
			if ($userId)
			{
				$record=$this->request->getAll();
				if($this->plugin->Mvc->model->User->handleRecord($record))
				{
					$this->view();	
				}
				
			}
			else
			{
				$this->view();
			}
		}		
	}
}
?>