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
		private $access_token='647631425250102|nMJJvLhliKfu7Z2Ezn93aqDj7tk';

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
  					'cookie'=>TRUE
					)
				);
		}
		public function setAppPerms()
		{

		}

		public function getAppPerms()
		{

		}

		//params include 'scope', 'redirect_uri' and 'display'
		//read the fb dev api docs for more info
		/*
		*works up to the login dialog part, but does not
		*for other apps it should take a scope array and a sring param for the redirect_url
		*/
		public function fbLogin()
		{
			$params=
					array(
						'scope'=>'email,publish_actions',
						'redirect_uri'=>'http://bizsmart.dev.lan/'
						);
			$fb=$this->facebook;
			if(isset($params))
			{
				return header('Location:'.$fb->getLoginUrl($params));
			}
			// else
			// {
			// 	return header('Location:'.$fb->getLoginUrl());
			// }
			
		}

		//we have email and profile picture returned for processing
		public function getUserProfile()
		{
			$fb=$this->facebook;
			$access_token = $fb->getAccessToken();
			if ($fb->getUser()!=0) 
			{
				try
				{
				// Proceed knowing you have a logged in user who's authenticated.
			    $me=$fb->api('/me?fields=picture,first_name,last_name,email,gender');
			    $streamParams = array(
			                          'method' => 'fql.multiquery',
			                          'queries' => $streamQuery
			                   );
			   // return array_merge($fb->api($streamParams),$me);
			    return $me;
			    //return $me;
				}
				catch(impl\FacebookApiException $e)
				{
					exit($e);
				}
			    
			}
			else
			{
				return $this->fbLogin();
			}
		}
		public function fbPostNew()
		{
			if($fb->getUser())
			{

			}
			else
			{
				print_r($fb->getUser());
			}
		}
		public function fbLogout()
		{
			session_unset();
			// $params = array( 'next' => 'http://bizsmart.dev.lan/' );
			// return  header('Location:'.$this->facebook->getLogoutUrl($params)); // $params is optional. 
		}


	}

}