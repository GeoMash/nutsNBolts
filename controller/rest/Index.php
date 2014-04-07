<?php
namespace application\nutsNBolts\controller\rest
{
	use application\nutsNBolts\base\Controller;

	class Index extends Controller
	{
		const FORMAT_DEFAULT='html';

		private $restController=null;

		public function index()
		{
			$request		=$this->request->getNodes();
			$lastIndex		=count($request)-1;
			$latNodeParts	=explode('.',$request[$lastIndex]);
			if (isset($latNodeParts[1]) && $latNodeParts[1]!=$request[$lastIndex])
			{
				$format=$latNodeParts[1];
				$request[$lastIndex]=str_replace('.'.$format,'',$request[$lastIndex]);
			}
			else
			{
				$format=self::FORMAT_DEFAULT;
			}
			if (isset($request[1]))
			{
				$className=$this->getController($request[1]);
				if (!empty($className))
				{
					array_shift($request);
					array_shift($request);
					$this->restController=new $className($this->MVC,$request,$format);
					exit();
				}
				else
				{
					//TODO: Handle properly.
					die('INVALID REQUEST');
				}
			}
			//TODO: Handle properly.
			die('INVALID REQUEST');
		}

		private function getController($serviceNode)
		{
			$return		=null;
			foreach ($this->application->getLoaded() as $applicationRef=>$application)
			{
				$basePath=APP_HOME.lcfirst($applicationRef)._DS_.'controller'._DS_.'rest'._DS_;
				if (is_file($basePath.ucfirst($serviceNode).'.php'))
				{
					$return=$this->application->getNamespace($applicationRef).'\controller\rest\\'.ucfirst($serviceNode);
				}
				else if (is_file($basePath.lcfirst($serviceNode)._DS_.'Index.php'))
				{
					$return=$this->application->getNamespace($applicationRef).'\controller\rest\\'.lcfirst($serviceNode).'\\'.'Index';
				}
			}
			return $return;
		}
	}
}
?>