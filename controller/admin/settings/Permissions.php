<?php
namespace application\nutsNBolts\controller\admin\settings
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\plupload\ThumbnailMaker;
	use nutshell\core\exception\NutshellException;
	use nutshell\helper\ObjectHelper;
	
	class Permissions extends AdminController
	{
		public function index()
		{
			$this->addBreadcrumb('System','icon-wrench','settings');
			$this->addBreadcrumb('Permissions','icon-bolt','permissions');
			
			$this->setContentView('admin/settings/permissions/roles');
			
			$this->view->getContext()
				->registerCallback
				(
					'getRoles',
					function()
					{
						return $this->getRoles(null);
					}
				);
			
			$renderRef='permissions';
			$this->execHook('onBeforeRender',$renderRef);
			$this->view->render();
		}
		
		public function addRole($id)
		{
			$this->addBreadcrumb('System','icon-wrench','settings');
			$this->addBreadcrumb('Permissions','icon-bolt','permissions');
			$this->addBreadcrumb('Add Role','icon-pencil','editRole');
			
			
			$renderRef='editRole';
			$this->execHook('onBeforeRender',$renderRef);
			$this->view->render();
		}
		
		public function editRole($id)
		{
			$this->addBreadcrumb('System','icon-wrench','settings');
			$this->addBreadcrumb('Permissions','icon-bolt','permissions');
			$this->addBreadcrumb('Edit Role','icon-pencil','editRole');
			
			$this->setContentView('admin/settings/permissions/addEditRole');
			
			$this->view->getContext()
				->registerCallback
				(
					'getPermissionTable',
					function()
					{
						return $this->getPermissionTable(null);
					}
				);
			
			
			$renderRef='editRole';
			$this->execHook('onBeforeRender',$renderRef);
			$this->view->render();
		}
		
		
		
		public function getRoles()
		{
			return $this->model->Role->read();
		}
		
		public function getPermissionTable()
		{
			return $this->model->Permission->read();
		}
	}
}
?>