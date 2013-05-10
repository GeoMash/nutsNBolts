<?php
namespace application\controller
{
	use application\base\AdminController;
	
	class Admin extends AdminController
	{
		public function index()
		{
			$websiteTitle	="Nuts n' Bolts";
			$brandTitle		="Nuts n' Bolts";
			
			$this->view->setTemplate('admin');
			$this->view->setVar('NS_ENV',NS_ENV);
			
			
			$this->view->setVar('websiteTitle',	$websiteTitle);
			$this->view->setVar('brandTitle',	$brandTitle);
			
			$this->view->render();
		}
	}
}
?>