<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;

	class Dashboard extends AdminController
	{
		public function index()
		{
			try
			{
				$this->plugin->Auth->can('admin.dashboard.read');
				$this->setContentView('admin/dashboard');
				$this->view->setVar('navButtons',array());
				$renderRef='index';
				$this->execHook('onBeforeRender',$renderRef);
				$this->view->render();
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
	}
}
?>