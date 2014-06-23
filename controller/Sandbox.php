<?php
namespace application\nutsNBolts\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	use application\nutsNBolts\Encoding;

	class Sandbox extends Controller
	{
		public function index()
		{
			//$date = new \DateTime('now');
			//echo $date->format('Y-m-d');
			$authorizeNet = $this->plugin->Payment('AuthorizeNet');
			$response = $authorizeNet->deleteRecurringSubscription('2114398');
			//$response = $authorizeNet->createRecurringSubscription('200.00','4012888818888','124','10/16');
			//$response = $authorizeNet->chargeCard('4012888818888',null,'10/16','20.00','hasan.baidoun@geomash.com');
			var_dump($response);
		}
		
//		public function index()
//		{
//
//            $string = "ABC [Test1] and your pin ins [123456]";
//            echo preg_replace('/\[.*?\]/', '', $string);
//
//			$mobileNumber	='0172359029';
//			$message		='WTP SMS Test 000001';
//			$SMS			=$this->plugin->Sms('M3Tech');
//			$SMS->setMobileNumber($mobileNumber)
//				->setMessage($message)
//				->send();
//			$this->model->Sms->insertAssoc
//			(
//				array
//				(
//					'bar_id'=>175,
//					'user_id'=>1,
//					'message'=>$message
//				)
//			);
//		}
	}
}
?>