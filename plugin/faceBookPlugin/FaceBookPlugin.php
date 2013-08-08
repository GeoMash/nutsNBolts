<?php
namespace application\nutsnbolts\plugin\FaceBookPlugin
{
	use nutshell\behaviour\Native;
	use nutshell\core\plugin\Plugin;
	use nutshell\behaviour\Singleton;
	use \Exception;
	use nutshell\core\exception\NutshellException;
	use application\nutsnbolts\plugin\FaceBookPlugin\FaceBookException;
	use application\nutsnbolts\plugin\FaceBookPlugin\impl\BaseFacebook;
	use application\nutsnbolts\plugin\FaceBookPlugin\impl\Facebook;

	class FaceBookPlugin extends Plugin implements Singleton, Native
	{
		private $facebook;
		private $isLoggedIn=FALSE;
		private $user;

		public static function registerBehaviours()
		{

		}

		public function init()
		{
			require_once(__DIR__._DS_.'impl\base_facebook.php');
			$this->facebook=new Facebook(
				array(
					'appId'  => '407520512686092',
  					'secret' => 'a7368dfd49ac3a66d1dd6881c7b032e3',
					)
				);
		}

		public function getAppPerms()
		{

		}

		public function fbLogin()
		{
			print('login');
		}

		public function getUserProfile()
		{
			$fb=$this->facebook;
			if ($fb->getUser()!=0) 
			{
			  try
			  {
			    // Proceed knowing you have a logged in user who's authenticated.
			    return $fb->api('/me');
			  } 
			  catch (Exception $e)
			  {
			   // error_log($e);
			   exit('bla');
			  }
				// print('Hi');
			}
			else
			{
				return $this->fbLogin();
			}
		}

		public function fbLogout()
		{
			// $logoutUrl = $facebook->getLogoutUrl();
		}


	}

}