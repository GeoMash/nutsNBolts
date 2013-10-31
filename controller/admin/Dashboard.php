<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	
	class Dashboard extends AdminController
	{
		public function index()
		{
			$this->setContentView('admin/dashboard');
			$renderRef='index';
			$this->execHook('onBeforeRender',$renderRef);
			$this->view->render();
		}
	}
}
?>