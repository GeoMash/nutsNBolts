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
			$SMS=$this->plugin->Sms('M3Tech');
			$SMS->setMobileNumber('60172359029')
				->setMessage('Test test test')
				->send();
		}
	}
}
?>