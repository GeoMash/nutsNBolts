<?php
namespace application\nutsnbolts\plugin\responder
{
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;
	use nutshell\core\exception\NutshellException;
	use application\nutsnbolts\helper\MimeHelper;
	use application\nutsnbolts\plugin\responder\ResponderException;

	class Responder extends Plugin implements Singleton
	{
		const DEFAULT_TYPE		='txt';
		const DEFAULT_HANDLER	='txt';
		const HANDLER_PREFIX	='handler_';
		
		private $type		=null;
		private $mimeType	=null;
		private $headers	=array();
		private $data	=null;
		
		public function init($type=self::DEFAULT_TYPE)
		{
// 			if (!method_exists($this,$method))
// 			{
// 				throw new Exception('Response type "'.$type.'" is not supported.');
// 			}
			
			require_once(__DIR__._DS_.'ResponderException.php');
			$this->setType($type);
		}
		
		public function setType($type=self::DEFAULT_TYPE)
		{
			$this->type	=$type;
			$mimeType	=MimeHelper::getMimeTypeFromExtension($type);
			$this->addHeader('content-type',$mimeType);
		}
		
		public function setData($data=null)
		{
// 			if (is_null($data) || is_array($data) || is_object($data))
// 			{
// 				$this->response=$data;
// 			}
// 			else
// 			{
// 				throw new Exception('Invalid response type given. "'.gettype($data).'" given, expecting null, array or object.');
// 			}
			$this->data=$data;
			return $this;
		}
		
		private function handler_txt()
		{
			print $this->data;
		}
		
		private function handler_json()
		{
			print json_encode($this->data);
		}
		
		private function handler_debug()
		{
			if(NS_ENV!='dev') throw new ResponderException(ResponderException::CANNOT_DEBUG_IN_PRODUCTION, $this->data);
			var_dump($this->data);
		}
		
		public function send()
		{
			foreach ($this->headers as $key=>$val)
			{
				header($key.':'.$val.';');
			}
			$method=self::HANDLER_PREFIX.$this->type;
			if (method_exists($this,$method))
			{
				$this->{$method}();
			}
			else
			{
				$this->{self::HANDLER_PREFIX.self::DEFAULT_HANDLER}();
			}
			return $this;
		}
		
		public function addHeader($key,$val)
		{
			$this->headers[$key]=$val;
			return $this;
		}
	}
}