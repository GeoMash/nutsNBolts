<?php
namespace application\nutsNBolts
{
	use nutshell\Nutshell;
	use nutshell\core\loader\Loader;
	use nutshell\core\application\Application;
	use nutshell\core\exception\NutshellException;
	use \DirectoryIterator;

	/**
	 * Nuts n Bolts
	 * 
	 * @version 1.0.0
	 * @author Timothy Chandler <tim@geomash.com>
	 */
	class NutsNBolts extends Application
	{
		const VERSION					='1.1.0-dev-4';
		const VERSION_MAJOR				=1;
		const VERSION_MINOR				=0;
		const VERSION_MICRO				=0;
		const VERSION_STAGE				='dev';
		const VERSION_STAGE_NUM			=4;
		
		const USER_SUPER				=-100;
		
		private $siteBindings=array();
		
		public $widget=null;
		
		public function init()
		{
			// print_r(Nutshell::getInstance()->config);exit();
			// Set the application path.
			// Nutshell::setAppPath(__DIR__);
			// Nutshell::registerApplication('nutsnbolts',__DIR__);

			//get the nutshell instance (create nutshell).
			$this->nutshell	= Nutshell::getInstance();
			
			$this->widget=new Loader();
			$this->widget->registerContainer('widget',__DIR__._DS_.'widget'._DS_,'application\nutsnbolts\widget\\');
			
			if (defined('TESTRUNNER') && TESTRUNNER && NS_INTERFACE!==Nutshell::INTERFACE_PHPUNIT)
			{
				throw new Exception('Nuts n Bolts testrunner interface can only be used with PHP Unit.');
			}
			else if (NS_INTERFACE!==Nutshell::INTERFACE_PHPUNIT)
			{
				header('X-NutsNBolts-Version:'.self::VERSION);
				header('X-Nutshell-Version:'.Nutshell::VERSION);
			}
			
			//Load helpers that belong to the application.
			$this->loadApplicationHelpers();
			
			$this->loadPHPPatches();

			//Load Exception Handlers that belong to the application.
			// $this->loadApplicationExceptionHandlers();
		}
		
		public function exec()
		{
			//Initiate the MVC.
			try
			{
				if (NS_INTERFACE!=Nutshell::INTERFACE_CLI
				&& NS_INTERFACE!= Nutshell::INTERFACE_PHPUNIT)
				{
					header('Content-Type:text/html;');
				}
				if (NS_INTERFACE!= Nutshell::INTERFACE_PHPUNIT)
				{
					$this->plugin->Mvc('nutsNBolts');
				}
			}
			catch(NutshellException $exception)//TODO: Change this - its not always a 404 (It can sometimes be a 500).
			{
				//exit('mvc failed');
				if(NS_INTERFACE != Nutshell::INTERFACE_CLI)
				{
					header('HTTP/1.1 404 Controller Not Found');
				}
				if ($this->config->application->mode == 'development')
				{
					throw $exception;
				}
			}
		}
		
		/**
		 * This method runs a "require_once" for each file in the folder.
		 * @param string $folder
		 */
		public static function loadAllFilesFromFolder($folder)
		{
			if (is_dir($folder))
			{
				foreach (new DirectoryIterator($folder) as $iteration)
				{
					//We don't load folders or files from within folders.
					if ($iteration->isFile() && !$iteration->isDot())
					{
						require_once($iteration->getPathname());
					}
				}
			}
		}
		
		/**
		 * This method loads all application helpers.
		 */
		private function loadApplicationHelpers()
		{
			$this->loadAllFilesFromFolder(__DIR__ . _DS_ . 'helper' . _DS_);
		}

		/**
		 * This method loads all application Exception Handlers.
		 */
		private function loadApplicationExceptionHandlers()
		{
			$this->loadAllFilesFromFolder(__DIR__ . _DS_ . 'exception' . _DS_);
		}

		/**
		 * This method loads all patches.
		 */
		private function loadPatches()
		{
			$this->loadAllFilesFromFolder(__DIR__ . _DS_ . 'patches' . _DS_);
		}
		
		public function bindToSite($application,$site)
		{
			$this->siteBindings[]=array
			(
				'application'	=>$application,
				'site'			=>$site
			);
			return $this;
		}
		
		public function getSiteBinding($site)
		{
			for($i=0,$j=count($this->siteBindings); $i<$j; $i++)
			{
				if ($this->siteBindings[$i]['site']==$site)
				{
					return $this->siteBindings[$i];
				}
			}
			return false;
		}
		
		public function getWidgetList()
		{
			foreach ($this->application->getLoaded() as $applicationRef=>$application)
			{
				$folder	=__DIR__._DS_.'widget';
				$folder=str_replace("nutsNBolts", lcfirst($applicationRef), $folder);
				foreach (new DirectoryIterator($folder) as $iteration)
				{
					//We don't load folders or files from within folders.
					if ($iteration->isDir() && !$iteration->isDot()
					&& $iteration->getFilename()!='base')
					{
						$widget[$applicationRef][]=array
						(
							'namespace'		=>'application\\'.lcfirst($applicationRef).'\\widget\\'.$iteration->getFilename(),
							'name'			=>ucwords($iteration->getFilename()),
							'application'	=>$applicationRef
						);
					}
				}						
			}
			return $widget;
		}
		
		public function loadPHPPatches()
		{
			$this->loadAllFilesFromFolder(__DIR__ . _DS_ . 'patch' . _DS_);
		}
	}
}
?>