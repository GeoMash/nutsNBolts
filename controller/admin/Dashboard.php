<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	
	class Dashboard extends AdminController
	{
		public function index()
		{
			$this->setContentView('admin/dashboard');
			$this->view->setVar('navButtons',array());
			$renderRef='index';
			$this->execHook('onBeforeRender',$renderRef);
			$this->view->render();
		}
	}
}
?>