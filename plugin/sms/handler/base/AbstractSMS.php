<?php
namespace application\nutsNBolts\plugin\sms\handler\base
{
	use nutshell\Nutshell;
	use nutshell\core\plugin\PluginExtension;
	use nutshell\helper\ObjectHelper;

	abstract class AbstractSMS extends PluginExtension
	{
		/**
		 * @var $mobileNumber
		 *
		 * @type string
		 */
		private $mobileNumber=null;

		/**
		 * @var $message
		 *
		 * @type string
		 */
		private $message=null;

		/**
		 * @var $params
		 *
		 * @type Array
		 */
		private $params=array();
		/**
		 * @var $logger
		 *
		 *Logger
		 *
		 * @type string, initially set as NULL
		 **/
		public $logger		=null;

		/**
		 * @var $SMSHandlerName
		 *
		 * SMSHandlerName
		 *
		 * @type string, initially set as NULL
		 **/
		public $SMSHandlerName=null;

		/**
		 *  send
		 *
		 * @param string $to The Mobile Phone Number
		 * @param string $message The Message to be sent to the $to number.
		 *
		 */
		abstract public function send($to,$message);

		/**
		 * handleResponse
		 *
		 * @param string $address
		 * @param string $response
		 *
		 */
//		abstract public function handleResponse($address,$response);

		/**
		 * @ignore
		 **/
		public function __construct()
		{
			parent::__construct();
			$this->logger		=$this->plugin->Logger('SMSLog');
		}

		/**
		 * log
		 *
		 * @param string $message
		 * @return Mixed
		 */
		public function log($message)
		{
			$this->logger->info('['.$this->SMSHandlerName.'] '.$message);
			return $this;
		}

		/**
		 *  request
		 *
		 * @param string $url
		 * @return Mixed
		 */
		public function request($url)
		{
			$postData=$this->getParams();
//			$stream=fopen
//			(
//				$url,
//				'r',
//				false,
//				stream_context_create
//				(
//					array
//					(
//						'https'=>array
//						(
//							'method'	=>'POST',
//							'header' 	=>'Content-type: application/x-www-form-urlencoded',
//							'content'	=>http_build_query($postData)
//						)
//					)
//				)
//			);
			$this->log('Params:'.print_r($postData,true));

//			$metaData		=stream_get_meta_data($stream);
//			$responseData	=stream_get_contents($stream);

//			$this->log('Response Meta:'.print_r($metaData,true));
//			$this->log('Response Data:'.print_r($responseData,true));

//			var_dump($postData);
//			var_dump($metaData);
//			var_dump($responseData);

//			fclose($stream);


			$response=file_get_contents($url.'?'.http_build_query($postData));
			$this->log('Response:'.print_r($response,true));
			return $response;
		}

		/**
		 * Sets the mobile number which the SMS will be sent to.
		 *
		 * @param $number
		 * @return $this
		 */
		public function setMobileNumber($number)
		{
			$this->mobileNumber=$number;
			return $this;
		}

		/**
		 * Gets the mobile number which was set by self::setMobileNumber.
		 *
		 * @return String The Mobile Number or null if it has not been set.
		 */
		public function getMobileNumber()
		{
			return $this->mobileNumber;
		}

		/**
		 * Gets the message which was set by self::setMessage.
		 *
		 * @return String The message or null if it has not been set.
		 */
		public function getMessage()
		{
			return $this->message;
		}

		/**
		 * Sets the message which will be sent to the SMS number.
		 *
		 * @param $message
		 * @return $this
		 */
		public function setMessage($message)
		{
			$this->message=$message;
			return $this;
		}

		/**
		 * Sets a parameter which will be attached to the request.
		 *
		 * @param Array [key=>val,...]
		 */
		public function setParam()
		{
			$args=func_get_args();
			if (is_null($args[0]))
			{
				return $this;
			}
			if (!is_array($args[0]))
			{
				$args[0]=array($args[0]=>$args[1]);
			}
			foreach ($args[0] as $key=>$value)
			{
				$this->params[$key]=$value;
			}
			return $this;
		}

		/**
		 * Gets all the parameters for use in the request.
		 *
		 * @return $this->params
		 */
		public function getParams()
		{
			return $this->params;
		}
	}
}