<?php
namespace application\nutsNBolts\base
{
	use nutshell\behaviour\Native;
	use nutshell\core\plugin\Plugin as NutshellPlugin;
	use nutshell\Nutshell;
	use application\nutsNBolts\NutsNBolts;

	class Plugin extends NutshellPlugin implements Native
	{
		public static function registerBehaviours(){}
		
		public $model=null;
		
		public function __construct()
		{
			parent::__construct();
			$this->model=$this->plugin->Mvc->model;
		}
	}
}