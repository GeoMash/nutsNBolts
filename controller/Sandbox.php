<?php
namespace application\nutsNBolts\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	use application\plugin\FaceBook\FaceBook;
	
	class Sandbox extends Controller
	{
		public function index()
		{
			$this->plugin->FaceBook->storeUserData();
			var_dump( $this->plugin->FaceBook->getUserProfile());
			// print($this->plugin->FaceBook->fbLogin());
			// if(isset($_GET['access_token']))
			// {
			// 	$this->plugin->FaceBook->storeUserData();
			// }
			// $this->plugin->FaceBook->fbPostNew();
			// print_r($this->plugin->FaceBook->fbLogout());

			// print $this->plugin->Notification->getSucessesHTML();
			// $this->plugin->Notification->clearAll();
		}
		
		public function testFBPlugin()
		{

		}
		
	}
}
?>
