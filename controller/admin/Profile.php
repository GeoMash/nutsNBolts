<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use nutshell\helper\ObjectHelper;
	
	class Profile extends AdminController
	{
		public function index()
		{
			$userId=$this->plugin->UserAuth->getUserId();
			$this->setContentView('admin/profile');
			$this->view->setVar('userDetails',$this->plugin->Mvc->model->User->read($userId));
			$this->addBreadcrumb('Profile','icon-user','profile');
			$this->view->render();
		}
		
		public function edit()
		{
			if($this->plugin->Mvc->model->User->handleRecord($this->request->getAll()))
			{
				$this->plugin->Notification->setSuccess('Profile updated.');
			}
			else
			{
				$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
			}
			$this->redirect('/admin/profile/');
		}		
	}
}
?>