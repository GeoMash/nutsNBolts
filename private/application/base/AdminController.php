<?php
namespace application\base
{
	use nutshell\plugin\mvc\Controller;
	
	class AdminController extends Controller
	{
		public function index()
		{
			$websiteTitle	="Nuts n' Bolts";
			$brandTitle		="Nuts n' Bolts";
			
			$this->view->setTemplate('index');
			$this->view->setVar('NS_ENV',NS_ENV);
			
			
			$this->view->setVar('websiteTitle',	$websiteTitle);
			$this->view->setVar('brandTitle',	$brandTitle);
			
			$this->view->render();
		}
	}
}
?>