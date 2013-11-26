<?php
namespace application\nutsNBolts\plugin\sms\handler
{
	use application\nutsNBolts\plugin\sms\handler\base\AbstractSMS;
	use nutshell\Nutshell;
	use \DateTime;
	use \DateTimeZone;

	class M3Tech extends AbstractSMS
	{
		public $SMSHandlerName='M3Tech';

		public function send()
		{
			$time=new DateTime('now',new DateTimeZone($this->config->timezone));
//			print($this->config->M3Tech->prettyPrint());
			$this->setParam
			(
				array
				(
					'UserKey'	=>$this->config->M3Tech->UserKey,
					'Password'	=>$this->config->M3Tech->Password,
					'MsgId'		=>uniqid(),
					'TimeStamp'	=>$time->format('dmYGis'),
					'ServiceId'	=>$this->config->M3Tech->ServiceId,
					'aSource'	=>$this->config->M3Tech->aSource,
					'aMSG'		=>$this->getMessage(),
					'Mobile'	=>$this->getMobileNumber(),
					'MCN'		=>$this->config->M3Tech->MCN
				)
			);
			$this->request($this->config->M3Tech->serviceURL);
		}
	}
}