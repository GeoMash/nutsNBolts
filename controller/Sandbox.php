<?php
namespace application\nutsnbolts\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	use application\plugin\FaceBookPlugin\FaceBookPlugin;
	
	class Sandbox extends Controller
	{
		public function index()
		{
			$this->plugin->FaceBookPlugin->fbLogin();
			// print $this->plugin->Notification->getSucessesHTML();
			// $this->plugin->Notification->clearAll();
		}
		
		public function testFBPlugin()
		{

		}
		
	}
}
?>