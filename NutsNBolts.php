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
		const VERSION					='1.5.0';
		const VERSION_MAJOR				=1;
		const VERSION_MINOR				=5;
		const VERSION_MICRO				=0;
		const VERSION_STAGE				='';
		const VERSION_STAGE_NUM			=0;

		/**
		 * @deprecated
		 */
		const USER_SUPER				=-100;//TODO Remove
		
		private $siteBindings			=array();
		private $doneModelBindings		=false;
		
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
			$this->plugin->Mvc('nutsNBolts',true);
			$result=$this->plugin->Mvc->model->Site->read(array('domain'=>$_SERVER['HTTP_HOST']));
			if (!isset($result[0]))
			{
				$msg=<<<MSG
Tried to find a registered site against <strong>{$_SERVER['HTTP_HOST']}</strong>.<br><br>
No website has been registered against this domain.<br><br>
Sites are registered in the <i>site</i> table in your database.<br><br>
Example:<br>
<table>
	<tr>
		<th>id</th>
		<th>ref</th>
		<th>domain</th>
		<th>name</th>
		<th>description</th>
		<th>status</th>
	</tr>
	<tr>
		<td>1</td>
		<td>website</td>
		<td>website.dev.lan</td>
		<td>Website</td>
		<td>My first website!</td>
		<td>1</td>
	</tr>
</table>
MSG;
				die($msg);
			}
			if (!$this->getSiteBinding($result[0]['ref']))
			{
				//Not for me!
//				die('No site bound for this domain! Site should be "'.$this->getSiteRef().'".');
				return;
			}
			
			if (!$this->doneModelBindings)
			{
				$this->bindApplicationModelLoaders();
			}
			
			try
			{
				if (NS_INTERFACE!=Nutshell::INTERFACE_CLI
				&& NS_INTERFACE!= Nutshell::INTERFACE_PHPUNIT)
				{
					header('Content-Type:text/html;');
				}
				if (NS_INTERFACE!= Nutshell::INTERFACE_PHPUNIT)
				{
					$this->plugin->Mvc->initController();
				}
			}
			catch(NutshellException $exception)//TODO: Change this - its not always a 404 (It can sometimes be a 500).
			{
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
			$widget=[];
			foreach ($this->application->getLoaded() as $applicationRef=>$application)
			{
				$folder	=__DIR__._DS_.'widget';
				$folder=str_replace("nutsNBolts", lcfirst($applicationRef), $folder);
				// MD check to see if directory exists before attempting to iterate it
				if(is_dir($folder))
				{
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
			}
			return $widget;
		}
		
		private function bindApplicationModelLoaders()
		{
			foreach ($this->application->getLoaded() as $applicationRef=>$application)
			{
				if ($applicationRef=='NutsNBolts')
				{
					continue;
				}
				$this->plugin->Mvc->getModelLoader()->registerContainer
				(
					'model.'.$applicationRef,
					APP_HOME.lcfirst($applicationRef).'/model/',
					'application\\'.lcfirst($applicationRef).'\\model\\'
				);
			}
			$this->doneModelBindings=true;
		}
		
		public function loadPHPPatches()
		{
			$this->loadAllFilesFromFolder(__DIR__ . _DS_ . 'patch' . _DS_);
		}
	}
}
?>