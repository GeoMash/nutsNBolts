<?php
namespace application\nutsNBolts\widget\base
{
	use nutshell\behaviour\Loadable;

	use nutshell\Nutshell;
	use nutshell\core\exception\ApplicationException;
	use nutshell\core\exception\NutshellException;
	use nutshell\core\Component;
	use nutshell\core\config\Config;
	use nutshell\helper\ObjectHelper;
	
	abstract class Widget extends Component implements Loadable
	{
		public $mainTemplateFile	='widget.tpl.php';
		public $configTemplateFile	='config.tpl.php';
		public $widgetJSFile		='Main.js';
		public $configJSFile		='Config.js';
		
		private $properties=array();
		
		public function __construct()
		{
			$this->nutshell		=Nutshell::getInstance();
			$this->application	=$this->nutshell->application->nutsnbolts;
			// $this->config		=$this->nutshell->applicationConfig->nutsnbolts->widget;
			
			if (method_exists($this,'init'))
			{
				$this->init();
			}
		}
		
		public static function getInstance(Array $args=array())
		{
			
		}
		
		public function setProperty($key,$val)
		{
			$this->properties[$key]=$val;
			return $this;
		}
		
		public function setProperties($keyVals)
		{
			foreach ($keyvals as $key=>$val)
			{
				$this->setProperty($key,$val);
			}
			return $this;
		}
		
		public function getProperty($key)
		{
			return $this->properties[$key];
		}
		
		public function getProperties()
		{
			return $this->properties;
		}
		
		public function getWidgetHTML($id,$config=array())
		{
			$template=$this->plugin->Template(ObjectHelper::getClassPath($this).'assets'._DS_.$this->mainTemplateFile);
			
			if (is_string($config))
			{
				$config=json_decode($config);
			}
			if (!is_null($config) && count($config))
			{
				$config=array_merge((array)$config,$this->getProperties());
			}
			else
			{
				$config=$this->getProperties();
			}
			$template->setKeyVal('dataId',$id);
			$template->setKeyValArray($config);
			$template->compile();
			return $template->getCompiled();
		} 
		
		public function getConfigHTML($widgetIndex='',$config=array())
		{
			$filePath=ObjectHelper::getClassPath($this).'assets'._DS_.$this->configTemplateFile;
			if (is_file($filePath))
			{
				$template=$this->plugin->Template($filePath);
				$template->setKeyVal('widgetIndex',$widgetIndex);
				if (!empty($config) && count($config))
				{
					$config=(array)json_decode($config);
				}
				else
				{
					$config=array();
				}
				$template->setKeyValArray($config);
				$template->compile();
				return $template->getCompiled();
			}
			return false;
		}
		
		public function getConfigJS()
		{
			$filePath=ObjectHelper::getClassPath($this).'assets'._DS_.$this->configJSFile;
			if (is_file($filePath))
			{
				return file_get_contents($filePath);
			}
			return false;
		}
		
		public function getWidgetJS()
		{
			$filePath=ObjectHelper::getClassPath($this).'assets'._DS_.$this->widgetJSFile;
			if (is_file($filePath))
			{
				return file_get_contents($filePath);
			}
			return false;
		}
		
		public function __get($key)
		{
			switch ($key)
			{
				case 'plugin':	return $this->application->plugin;
				default:
				{
					throw new NutshellException(NutshellException::INVALID_PROPERTY, 'Attempted to get invalid property "'.$key.'" from widget.');
				}
			}
		}
	}
}