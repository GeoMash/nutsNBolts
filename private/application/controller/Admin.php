<?php
namespace application\controller
{
	use application\base\AdminController;
	
	class Admin extends AdminController
	{
		public function index()
		{
			$this->dashboard();
		}
		
		public function dashboard()
		{
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