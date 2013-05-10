<?php
namespace application\controller\admin
{
	use application\base\AdminController;
	
	class Index extends AdminController
	{
		public function index()
		{
			$this->dashboard();
		}
		
		public function dashboard()
		{
			$this->setContentView('admin/dashboard');
			$this->addBreadcrumb('Dashboard','icon-dashboard');
			$this->view->render();
		}
		
		public function articles()
		{
			$this->addBreadcrumb('Articles','icon-list');
			
			$this->view->render();
		}
	}
}
?>