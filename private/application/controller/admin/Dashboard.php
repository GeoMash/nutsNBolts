<?php
namespace application\controller\admin
{
	use application\base\AdminController;
	
	class Dashboard extends AdminController
	{
		public function index()
		{
			$this->setContentView('admin/dashboard');
			$this->addBreadcrumb('Dashboard','icon-dashboard');
			$this->view->render();
		}
	}
}
?>