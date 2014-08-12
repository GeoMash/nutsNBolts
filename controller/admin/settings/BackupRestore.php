<?php
namespace application\nutsNBolts\controller\admin\settings
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	
	class BackupRestore extends AdminController
	{
		public function index()
		{
			$canBackup		=false;
			$canRestore		=false;
			$hasPermission	=false;
			try
			{
				$this->plugin->Auth->can('admin.backupRestore.backup');
				$hasPermission	=true;
				$canBackup		=true;
			}
			catch(AuthException $exception){}
			try
			{
				$this->plugin->Auth->can('admin.backupRestore.restore');
				$hasPermission	=true;
				$canRestore		=true;
			}
			catch(AuthException $exception){}
			if (!$hasPermission)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
			else
			{
				$this->view->setVar('canBackup',$canBackup);
				$this->view->setVar('canRestore',$canRestore);
				
				$this->addBreadcrumb('System','icon-wrench','settings');
				$this->addBreadcrumb('Backup / Restore','icon-refresh','backupRestore');
				
				$this->setContentView('admin/settings/backupRestore/index');
				
				$renderRef='backupRestore';
				$this->execHook('onBeforeRender',$renderRef);
				$this->view->render();
			}
		}
		
		public function backup()
		{
			
		}
		
		public function restore()
		{
			$this->plugin->Plupload->setCallback(array($this,'uploadComplete'));
			$this->plugin->Plupload->upload();
			
		}
		
		public function uploadComplete($basename)
		{
			$completedDir=$this->config->plugin->Plupload->completed_dir;
			$result=$this->plugin->Import->fromFile($completedDir.$basename,$this->request->get('dataType'));
			unlink($completedDir.$basename);
			
			return $completedDir.$basename;
		}
	}
}
?>