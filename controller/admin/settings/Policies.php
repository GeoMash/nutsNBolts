<?php
namespace application\nutsNBolts\controller\admin\settings
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;

	/**
	 * Class Policies
	 * @package application\nutsNBolts\controller\admin\settings
	 * 
	 * Passwords
	 * -----------------
	 * * Enforce Random Password
	 * * Salt Passwords
	 * * Minimum length
	 * * Maximum Length
	 * 
	 * 
	 * 
	 * Logging
	 * -------
	 * 
	 */
	class Policies extends AdminController
	{
		public function index()
		{
			try
			{
				$this->plugin->Auth	->can('admin.policies.update')
									->can('admin.policies.read');
				
				$this->addBreadcrumb('System Settings','icon-wrench','settings');
				$this->addBreadcrumb('Policies','icon-lock','policies');
				
				$this->setContentView('admin/settings/policies');
				
				if ($this->request->get('_'))
				{
					$this->model->Policy->handleRecord($this->request->getAll());
					$this->plugin->Notification->setSuccess('Policies Updated.');
				}
				
				$policy=$this->model->Policy->read();
				if (isset($policy[0]))
				{
					$policy=$policy[0];
					$this->view->setVar('record',$policy);
				}
				
				$this->view->getContext()
					->registerCallback
					(
						'isChecked',
						function($key) use ($policy)
						{
							if (!is_null($policy[$key]))
							{
								print 'checked';
							}
						}
					);
				
				$renderRef='policies';
				$this->view->setVar('extraOptions',array());
				$this->execHook('onBeforeRender',$renderRef);
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
			}
			$this->view->render();
		}
	}
}
?>