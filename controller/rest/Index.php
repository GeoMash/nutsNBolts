<?php
namespace application\nutsNBolts\controller\rest
{
	use application\nutsNBolts\base\Controller;
	use RecursiveDirectoryIterator;
	use RecursiveIteratorIterator;
	use RegexIterator;
	use RecursiveRegexIterator;
	use ReflectionClass;
	use ReflectionException;

	class Index extends Controller
	{
		const CACHE_FILE	='/../../cache/nodeMap.json';
		const FORMAT_DEFAULT='html';

		private $restController=null;
		private $pathVars=
		[
			'{*}',
			'[*]',
			'[...]',
			'{string}',
			'[string]',
			'{int}',
			'[int]'
		];

		public function index()
		{
			$request		=$this->request->getNodes();
			$lastIndex		=count($request)-1;
			$lastNodeParts	=explode('.',$request[$lastIndex]);
			if (isset($lastNodeParts[1]) && $lastNodeParts[1]!=$request[$lastIndex])
			{
				$format=$lastNodeParts[1];
				$request[$lastIndex]=str_replace('.'.$format,'',$request[$lastIndex]);
			}
			else
			{
				$format=self::FORMAT_DEFAULT;
			}
			if (isset($request[1]))
			{
				$classMap=$this->getRestMap();
				$fail		=false;
				foreach ($classMap as $path=>$mapItem)
				{
					for ($i=0,$j=count($request),$l=count($mapItem['nodes']); ($i<$j || $i<$l); $i++)
					{
						if (!isset($request[$i]) || (!isset($mapItem['nodes'][$i]) || $request[$i]!=$mapItem['nodes'][$i]))
						{
							$fail=!$this->parsePathVar($request,$mapItem['nodes'],$i);
						}
						if ($fail)
						{
							unset($classMap[$path]);
							$fail=false;
							break;
						}
					}
				}
				$numResults=count($classMap);
				if ($numResults>1)
				{
					die('Relevance checking has not yet been implemented for multiple mapped results.');
				}
				elseif ($numResults===1)
				{
					$mapItem=array_values($classMap)[0];
				}
				else
				{
					die('No service handler found for this URL.');
				}
				
				$params=$this->extractParams($mapItem,$request);
				
				$this->resultController=new $mapItem['class']($this->MVC,$request,$format);
				
				call_user_func_array(array($this->resultController,$mapItem['method']),$params);
				exit();
//				$className=$this->getController($request[1]);
//				if (!empty($className))
//				{
//					array_shift($request);
//					array_shift($request);
//					$this->restController=new $className($this->MVC,$request,$format);
//					exit();
//				}
//				else
//				{
//					//TODO: Handle properly.
//					die('INVALID REQUEST');
//				}
			}
			//TODO: Handle properly.
			die('INVALID REQUEST');
		}
		
		private function extractParams($restDef,$request)
		{
			$params=[];
			for ($i=0,$j=count($request),$l=count($restDef['nodes']); ($i<$j || $i<$l); $i++)
			{
				if (in_array($restDef['nodes'][$i],$this->pathVars))
				{
					$params[]=$request[$i];
				}
			}
			return $params;
		}
		
		private function parsePathVar($request,$nodes,$index)
		{
			$nodeCount=count($nodes);
			if (isset($nodes[$index]))
			{
				$parseVar=$nodes[$index];
			}
			else
			{
				$parseVar=null;
			}
			if ($parseVar=='[...]'
			|| (count($request)>$nodeCount && $index>=$nodeCount))
			{
				if ($parseVar=='[...]' && empty($request[$index]))
				{
					return true;
				}
				else if (in_array('[...]',$nodes))
				{
					$parseVar=$nodes[$nodeCount-2];
					if ($parseVar=='[...]')//Cannot have more than 1 of these. Invalid path!!!
					{
						return false;
					}
				}
			}
			switch ($parseVar)
			{
				case '{*}':
				{
					if (empty($request[$index]))
					{
						return false;
					}
					break;
				}
				case '[*]':
				{
					break;
				}
				case '{int}':
				{
					if (!isset($request[$index]) || !is_numeric($request[$index]))
					{
						return false;
					}
					break;
				}
				case '[int]':
				{
					if (isset($request[$index]) && !is_numeric($request[$index]))
					{
						return false;
					}
					break;
				}
				case '{string}':
				{
					if (!isset($request[$index]) || !is_string($request[$index]))
					{
						return false;
					}
					break;
				}
				case '[string]':
				{
					if (isset($request[$index]) && !is_string($request[$index]))
					{
						return false;
					}
					break;
				}
				default:
				{
					return false;
				}
			}
			return true;
		}
		
		private function getRestMap()
		{
			$cacheFile=__DIR__.self::CACHE_FILE;
//			if (!is_file($cacheFile))
//			{
//				$this->buildCacheFile();
//			}
			$this->buildCacheFile();
			return json_decode(file_get_contents($cacheFile),true);
		}
		
		private function buildCacheFile()
		{
			$cacheFile	=__DIR__.self::CACHE_FILE;
			$fullMap	=array();
			$iterator	=new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__));
			$regex		=new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
			foreach ($regex as $iteration)
			{
				require_once($iteration[0]);
				$fileInfo	=pathinfo($iteration[0]);
				$namespace	=str_replace(APP_HOME,'application\\',$fileInfo['dirname']);
				$namespace	=str_replace('/','\\',$namespace);
				$className	=$namespace.'\\'.$fileInfo['filename'];
				$reflection	=new ReflectionClass($className);
				$properties	=$reflection->getDefaultProperties();
				if (isset($properties['map']))
				{
					$mapPath=str_replace(array(APP_HOME,'nutsNBolts/controller'),'',$fileInfo['dirname']).'/';
					foreach ($properties['map'] as $pathNode=>$method)
					{
						$nodes=explode('/',$mapPath.lcfirst($fileInfo['filename']).'/'.$pathNode);
						if (!end($nodes))	array_pop($nodes);
						if (!reset($nodes))	array_shift($nodes);
						$fullMap[$mapPath.lcfirst($fileInfo['filename']).'/'.$pathNode]=
						[
							'nodes'	=>$nodes,
							'class'	=>$className,
							'method'=>$method
						];
					}
				}
			}
			file_put_contents($cacheFile,json_encode($fullMap));
			return $this;
		}
	}
}
?>