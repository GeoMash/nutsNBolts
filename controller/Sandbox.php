<?php
namespace application\nutsnbolts\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	use application\plugin\FaceBook\FaceBook;
	
	class Sandbox extends Controller
	{
		public function index()
		{
			// var_dump($this->plugin->FaceBookPlugin->getUserProfile());
			print($this->plugin->FaceBook->fbLogin());
			// if(isset($_GET['access_token']))
			// {
			// 	$this->plugin->FaceBookPlugin->storeUserData();
			// }
			// print_r($this->plugin->FaceBookPlugin->fbPostNew());
			//$this->plugin->FaceBookPlugin->fbLogout();

			// print $this->plugin->Notification->getSucessesHTML();
			// $this->plugin->Notification->clearAll();
		}
		
		public function testFBPlugin()
		{

		}
		
	}
}
?>