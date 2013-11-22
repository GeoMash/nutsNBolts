<?php
namespace application\nutsNBolts\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;

	class Sandbox extends Controller
	{
		public function index()
		{
			$mobileNumber	='0172359029';
			$message		='WTP SMS Test 000001';
			$SMS			=$this->plugin->Sms('M3Tech');
			$SMS->setMobileNumber($mobileNumber)
				->setMessage($message)
				->send();
			$this->model->Sms->insertAssoc
			(
				array
				(
					'bar_id'=>175,
					'user_id'=>1,
					'message'=>$message
				)
			);
		}
	}
}
?>