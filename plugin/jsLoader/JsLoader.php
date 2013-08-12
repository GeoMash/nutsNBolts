<?php
namespace application\nutsNBolts\plugin\jsLoader
{
	use nutshell\behaviour\Factory;
	use nutshell\core\plugin\Plugin;
	use nutshell\core\exception\NutshellException;
	use nutshell\helper\ObjectHelper;

	class JsLoader extends Plugin implements Factory
	{
		
		private $scripts		=array();
		private $executables	=array();
		
		public function init()
		{
			
		}
		
		public function loadScript($classPath,$exec=false)
		{
			$this->scripts[]=str_replace('\\','.',$classPath);
			if ($exec)
			{
				$this->executables[]=$exec;
			}
			return $this;
		}
		
		private function getFormattedList()
		{
			return "'".implode("','",$this->scripts)."'";
		}
		
		public function getLoaderHTML()
		{
			$filePath=ObjectHelper::getClassPath($this)._DS_.'script.tpl.php';
			if (is_file($filePath))
			{
				$exec=array();
				if (count($this->executables))
				{
					for ($i=0,$j=count($this->executables); $i<$j; $i++)
					{
						if (substr($this->executables[$i],-1)!=')')
						{
							$this->executables[$i].='()';
						}
						$exec[]='new '.$this->executables[$i].';';
					}
				}
				$exec=implode("\n",$exec);
				
				$template=$this->plugin->Template($filePath);
				$template->setKeyVal('scripts',$this->getFormattedList());
				$template->setKeyVal('exec',$exec);
				$template->compile();
				return $template->getCompiled();
			}
			else
			{
				throw new NutshellException('Unable to load script template.');
			}
		}
	}
}