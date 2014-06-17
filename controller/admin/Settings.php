<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use application\nutsNBolts\plugin\plupload\ThumbnailMaker;
	use nutshell\core\exception\NutshellException;
	use nutshell\helper\ObjectHelper;
	
	class Settings extends AdminController
	{
		private $collectionID=null;
		
		public function index()
		{
			$this->show404();
			$renderRef='index';
			$this->execHook('onBeforeRender',$renderRef);
			$this->view->render();
		}
		
		public function nnb()
		{
			try
			{
				$this->plugin->Auth	->can('admin.setting.nutsNBolts.create')
									->can('admin.setting.nutsNBolts.read');
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Nuts n Bolts Settings','icon-circle-blank','nnb');
				
				$renderRef='nnb';
				$this->view->setVar('extraOptions',array());
				$this->execHook('onBeforeRender',$renderRef);
				$this->view->render();
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		public function site()
		{
			try
			{
				$this->plugin->Auth	->can('admin.setting.site.create')
									->can('admin.setting.site.read')
									->can('admin.setting.site.update')
									->can('admin.setting.site.delete');
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Site Settings','icon-circle','site');
				$this->setContentView('admin/settings/siteSettings');
				
				if ($this->request->get('key'))
				{
					$keys	=$this->request->get('key');
					$values	=$this->request->get('value');
					$records=[];
					for ($i=0,$j=count($keys); $i<$j; $i++)
					{
						$records[]=
						[
							'label'	=>$keys[$i],
							'key'	=>$keys[$i],
							'value'	=>$values[$i]
						];
					}
					$this->model->SiteSettings->insertAll($records);
					$this->plugin->Notification->setSuccess('Settings saved.');
				}
				
				$settings=$this->model->SiteSettings->read();
				
				$this->view->setVar('settings',$settings);
				$renderRef='site';
				$this->view->setVar('extraOptions',array());
				$this->execHook('onBeforeRender',$renderRef);
				$this->view->render();
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
	}
}
?>