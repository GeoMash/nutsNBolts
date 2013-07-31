<?php
namespace application\nutsnbolts
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use \DirectoryIterator;

	/**
	 * Nuts n Bolts
	 * 
	 * @version 1.0.0
	 * @author Timothy Chandler <tim@geomash.com>
	 */
	class NutsNBolts
	{
		const VERSION					='1.0.0-dev';
		const VERSION_MAJOR				=1;
		const VERSION_MINOR				=0;
		const VERSION_MICRO				=0;
		const VERSION_STAGE				='dev';
		const VERSION_STAGE_NUM			=0;

		protected $nutshell				=null;
		
		static public function getInstance()
		{
			if (!isset($GLOBALS['NUTSNBOLTS']))
			{
				$GLOBALS['NUTSNBOLTS']=new self();
			}
			return $GLOBALS['NUTSNBOLTS'];
		}
		
		public function __construct()
		{
			// Set the application path.
			// Nutshell::setAppPath(__DIR__);
			Nutshell::registerApplication('nutsnbolts',__DIR__);

			//get the nutshell instance (create nutshell).
			$this->nutshell	= Nutshell::getInstance();
			
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

			//Load Exception Handlers that belong to the application.
			$this->loadApplicationExceptionHandlers();
		}
		
		public function init()
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
					$this->nutshell->plugin->Mvc();
				}
			}
			catch(NutshellException $exception)//TODO: Change this - its not always a 404 (It can sometimes be a 500).
			{
				if(NS_INTERFACE != Nutshell::INTERFACE_CLI)
				{
					header('HTTP/1.1 404 Controller Not Found');
				}
				if (Nutshell::getInstance()->config->application->mode == 'development')
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
	}	
}
?>