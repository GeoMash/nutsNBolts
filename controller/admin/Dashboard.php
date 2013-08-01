<?php
namespace application\nutsnbolts\controller\admin
{
	use application\nutsnbolts\base\AdminController;
	
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