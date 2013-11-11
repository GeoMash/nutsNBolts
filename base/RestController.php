<?php
namespace application\nutsNBolts\base
{
	use nutshell\core\exception\LoaderException;
	use nutshell\plugin\mvc\Mvc;
	use application\nutsNBolts\base\Controller as BaseController;

	class RestController extends BaseController
	{
		const METHOD_GET	='GET';
		const METHOD_POST	='POST';
		const METHOD_PUT	='PUT';
		const METHOD_DELETE	='DELETE';

		private $codes=array
		(
			100=>'Continue',
			101=>'Switching Protocols',
			200=>'OK',
			201=>'Created',
			202=>'Accepted',
			203=>'Non-Authoritative Information',
			204=>'No Content',
			205=>'Reset Content',
			206=>'Partial Content',
			300=>'Multiple Choices',
			301=>'Moved Permanently',
			302=>'Found',
			303=>'See Other',
			304=>'Not Modified',
			305=>'Use Proxy',
			306=>'(Unused)',
			307=>'Temporary Redirect',
			400=>'Bad Request',
			401=>'Unauthorized',
			402=>'Payment Required',
			403=>'Forbidden',
			404=>'Not Found',
			405=>'Method Not Allowed',
			406=>'Not Acceptable',
			407=>'Proxy Authentication Required',
			408=>'Request Timeout',
			409=>'Conflict',
			410=>'Gone',
			411=>'Length Required',
			412=>'Precondition Failed',
			413=>'Request Entity Too Large',
			414=>'Request-URI Too Long',
			415=>'Unsupported Media Type',
			416=>'Requested Range Not Satisfiable',
			417=>'Expectation Failed',
			500=>'Internal Server Error',
			501=>'Not Implemented',
			502=>'Bad Gateway',
			503=>'Service Unavailable',
			504=>'Gateway Timeout',
			505=>'HTTP Version Not Supported'
		);

		public $config			=null;
		private $subController	=null;
		private $paths			=null;
		private $_request		=null;
		private $format			=null;
		private $method			=null;
		private $contentType	=null;
		private $charset		=null;
		private $responseCode	=null;

		public function __construct(Mvc $MVC,$request,$format)
		{
			parent::__construct($MVC);
			$this->_request=$request;
			$this->format=$format;

			$this->plugin->UserAuth();

			$this->method=$_SERVER['REQUEST_METHOD'];
			if (!empty($_SERVER['HTTP_CONTENT_TYPE']))
			{
				$this->parseContentTypeHeader($_SERVER['HTTP_CONTENT_TYPE']);
			}
			else if (!empty($_SERVER['CONTENT_TYPE']))
			{
				$this->parseContentTypeHeader($_SERVER['CONTENT_TYPE']);
			}
			if (method_exists($this,'init'))
			{
				$this->init();
			}
			$this->execRequest();
		}

		public function getMethod()
		{
			return $this->method;
		}

		public function getRequest()
		{
			return $this->_request;
		}

		public function getFullRequest()
		{
			$nodes=$this->request->getNodes();
			$lastIndex=count($nodes)-1;
			$nodes[$lastIndex]=str_replace('.'.$this->getFormat(),'',$nodes[$lastIndex]);
			return ;
		}

		public function getFullRequestPart($part)
		{
			$part=$this->request->node($part);
			if ($part==$this->request->lastNode())
			{
				$part=str_replace('.'.$this->getFormat(),'',$part);
			}
			return $part;
		}

		public function getFormat()
		{
			return $this->format;
		}

		private function parseContentTypeHeader($contentType)
		{
			$parts=explode(';',$contentType);
			$this->contentType=strtolower(trim($parts[0]));
			for ($i=1,$j=count($parts); $i<$j; $i++)
			{
				$keyVal=explode('=',$parts[$i]);
				if ($keyVal[0]=='charset')
				{
					$this->charset=$keyVal[1];
				}
			}
			return $this;
		}

		public function setResponseCode($code)
		{
			if (isset($this->codes[$code]))
			{
				$this->responseCode=array($code,$this->codes[$code]);
			}
		}

		public function respond($success,$message,$data=null)
		{
			header('HTTP/1.1 '.$this->responseCode[0].' '.$this->responseCode[1]);
			switch ($this->format)
			{
				case 'html':
				{
					if (is_array($data) && count($data))
					{
						if (isset($data[0]['id'])
						&& (isset($data[0]['name']) || isset($data[0]['title'])))
						{
							$ref='id';
							if (isset($data[0]['name']))
							{
								$ref='name';
							}
							else if (isset($data[0]['title']))
							{
								$ref='title';
							}
							$HTML='<ul>';
							for ($i=0,$j=count($data); $i<$j; $i++)
							{
								$HTML.='<li><a href="'.$data[$i]['id'].'">'.$data[$i][$ref].'</a></li>';
							}
						}
						else
						{
							$HTML=$this->arrayToList($data);
						}
					}
					else if (is_string($data))
					{
						$HTML=$data;
					}
					else
					{
						$HTML='Unable to render response as HTML.';
					}
					$success=($success)?'true':'false';
					$data=<<<HTML
<h1>{$this->responseCode[0]} {$this->responseCode[1]}</h1>
<h3>Success: {$success}</h3>
<h3>Message: {$message}</h3>
<hr>
<h3>Data</h3>
{$HTML}
HTML;

					$this->plugin	->Responder('html')
									->setData($data)
									->send();
					exit();
				}
				case 'json':
				case 'jsonp':
				{

					break;
				}
				case 'xml':
			}
			$this->plugin	->Responder($this->format)
							->setData
							(
								array
								(
									'success'	=>$success,
									'message'	=>$message,
									'data'		=>$data
								)
							)
							->send();
			exit();
		}

		private function arrayToList(Array $array)
		{
			$HTML='<ul>';
			foreach ($array as $key=>$val)
			{
				if (is_array($val))
				{
					$val=$this->arrayToList($val);
				}
				elseif (is_object($val))
				{
					$val=$this->arrayToList((array)$val);
				}
				$HTML.='<li>'.$key.' = '.$val.'</li>';
			}
			$HTML.='</ul>';
			return $HTML;
		}

		public function bindPaths($paths)
		{
			foreach ($paths as $path=>$action)
			{
				if (empty($path))
				{
					$this->paths[$path]=$action;
					continue;
				}
				$path='/^('.str_replace
				(
					array('{int}','{string}','/'),
					array('\d*','\w*','\/'),
					$path
				).')(.*)$/';
				$this->paths[$path]=$action;
			}
			return $this;
		}

		public function getPaths()
		{
			return $this->paths;
		}

		public function execAction($action,$request)
		{
			if (method_exists($this,$action))
			{
				$node	=0;
				$args	=array();
				while (true)
				{
					//grab the next node
					$arg=(isset($request[$node]))?$request[$node++]:null;
					if(is_null($arg))
					{
						break;
					}
					//append to the args array
					$args[]=$arg;
				}
				call_user_func_array
				(
					array($this,$action),
					$args
				);
			}
		}

		public function execRequest()
		{
			$request		=$this->getRequest();
			$joinedRequest	=implode('/',$request);
			$action		=null;
			foreach ($this->paths as $path=>$pathAction)
			{
				if ($joinedRequest==$path)
				{
					if (method_exists($this,$pathAction))
					{
						$this->execAction($pathAction,$request);
					}
				}
				else if (preg_match($path,$joinedRequest)===1)
				{
					if (method_exists($this,$pathAction))
					{
						$this->execAction($pathAction,$request);
					}
					else try
					{
						$request=explode('/',trim(preg_replace($path,'$2',$joinedRequest),'/'));
						if (class_exists($pathAction,true))
						{
							$this->subController=new $pathAction($this->MVC,$request,$this->getFormat());
						}
					}
					catch (LoaderException $exception)
					{
						//TODO: Handle properly.
						die('INVALID REQUEST 1');
					}
				}
			}
			//TODO: Handle properly.
			die('INVALID REQUEST 2');
		}
	}
}
?>