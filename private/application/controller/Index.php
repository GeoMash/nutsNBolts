<?php
namespace application\controller
{
	use nutshell\plugin\mvc\Controller;
	
	class Index extends Controller
	{
		public function index()
		{
			$this->view->setTemplate('index');
			$this->view->setVar('NS_ENV',NS_ENV);
			
			
			$this->view->setVar('websiteTitle',	$websiteTitle);
			$this->view->setVar('brandTitle',	$brandTitle);
			
			$this->view->render();
		}
	}
}
?>