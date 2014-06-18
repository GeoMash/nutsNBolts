<?php
namespace application\nutsNBolts\controller\admin\settings
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\Auth;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use application\nutsNBolts\plugin\plupload\ThumbnailMaker;
	use nutshell\core\exception\NutshellException;
	use nutshell\helper\ObjectHelper;

	/**
	 * Class Policies
	 * @package application\nutsNBolts\controller\admin\settings
	 * 
	 * Password Policies
	 * -----------------
	 * * Minimum length
	 * 
	 */
	class Policies extends AdminController
	{
		public function index()
		{
			try
			{
				$this->plugin->Auth	->can('admin.policies.create')
									->can('admin.policies.read');
				
				$this->addBreadcrumb('System Settings','icon-wrench','settings');
				$this->addBreadcrumb('Policies','icon-lock','policies');
				
				$this->setContentView('admin/settings/policies');
				
				$renderRef='policies';
				$this->view->setVar('extraOptions',array());
				$this->execHook('onBeforeRender',$renderRef);
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
			}
			$this->view->render();
		}
	}
}
?>