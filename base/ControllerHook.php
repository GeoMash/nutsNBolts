<?php
namespace application\nutsNBolts\base
{
	// use nutshell\behaviour\Loadable;

	use nutshell\Nutshell;
	use nutshell\core\exception\ApplicationException;
	use nutshell\core\exception\NutshellException;
	use nutshell\core\Component;
	use nutshell\core\config\Config;
	use nutshell\helper\ObjectHelper;
	
	
	class ControllerHook extends Component 
	{
		public $model			=null;
		public $view			=null;
		public $hookContainers =null;

		public function __construct($model,$view)
		{
			parent::__construct();
			// $applicationName	=ObjectHelper::getBaseClassName(get_called_class());
			// $applicationRef		=lcfirst($applicationName);
			$this->nutshell		=Nutshell::getInstance();
			// $this->config		=$this->nutshell->{$applicationName}->config;
			$this->application	=Nutshell::getInstance()->application;
			$this->request		=Nutshell::getInstance()->request;
			$this->model		=$model;
			$this->view			=$view;
			
			if (method_exists($this,'init'))
			{
				$this->init();
			}
		}

		public function __get($key)
		{
			switch ($key)
			{
				case 'plugin':
				{
					$this->nutshell->setConfigPointer('NutsNBolts');
					return $this->nutshell->plugin;
				}
				default:
				{
					throw new NutshellException(NutshellException::INVALID_PROPERTY, 'Attempted to get invalid property "'.$key.'" from application.');
				}
			}
		}
	}
}
?>