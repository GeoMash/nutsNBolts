<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\policy\exception\PolicyException;
	use application\nutsNBolts\plugin\user\exception\UserException;
	
	class Profile extends AdminController
	{
		public function index()
		{
			$userId=$this->plugin->UserAuth->getUserId();
			$this->setContentView('admin/profile');
			
			if ($this->request->get('name_first'))
			{
				try
				{
					$this->plugin->User->update($this->request->getAll());
					$this->plugin->Notification->setSuccess('Profile updated.');
				}
				catch (UserException $userException)
				{
					$this->plugin->Notification->setError($userException->getMessage());
				}
				catch (PolicyException $policyException)
				{
					$this->plugin->Notification->setError($policyException->getMessage());
				}
			}
			
			$this->view->setVar('userDetails',$this->plugin->Mvc->model->User->read($userId));
			$this->addBreadcrumb('Profile','icon-user','profile');
			$this->view->render();
		}
	}
}
?>