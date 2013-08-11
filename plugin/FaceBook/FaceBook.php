<?php
namespace application\nutsnbolts\plugin\FaceBook
{
	use nutshell\core\plugin\Plugin;
	use nutshell\behaviour\Singleton;
	use nutshell\behaviour\Native;
	use \Exception;
	use nutshell\core\exception\NutshellException;
	use application\nutsnbolts\plugin\FaceBook\FaceBookException;
	use application\nutsnbolts\plugin\FaceBook\impl\BaseFacebook;
	use application\nutsnbolts\plugin\FaceBook\impl\facebook as FaceBookBase;

	class FaceBook extends Plugin implements Singleton, Native
	{
		private $facebook;
		private $isLoggedIn=FALSE;
		private $user;
		private $access_token;
		//private $app_access_token='647631425250102|nMJJvLhliKfu7Z2Ezn93aqDj7tk';

		public static function registerBehaviours()
		{

		}

		public function init()
		{
			require_once(__DIR__._DS_.'impl\base_facebook.php');
			$this->facebook=new FaceBookBase(
				array(
					'appId'  => '1376270492601764',
  					'secret' => 'f9a2227c271826b65d04103a78048207',
  					'cookie'=>TRUE
					)
				);
		}
		
		public function storeUserData()
		{
			if ($this->getUserProfile())
			{
			 	# code...
			}
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
						'scope'=>'email,publish_actions,publish_stream',
						'redirect_uri'=>'http://bizsmart.dev.lan/home/'
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
		public function setAccessToken()
		{
			return $this->facebook->setAccessToken(
				$this->facebook->getAccessToken()
				);
		}

		//we have email and profile picture returned for processing
		public function getUserProfile()
		{
			$fb=$this->facebook;
			$access_token =$this->facebook->setAccessToken(
				$this->facebook->getAccessToken()
				);
			if ($fb->getUser()!=0) 
			{
				try
				{
				// Proceed knowing you have a logged in user who's authenticated.
			    $me=$fb->api('/me?fields=picture,first_name,last_name,email,gender');
			    // $streamParams = array(
			    //                       'method' => 'fql.multiquery',
			    //                       'queries' => $streamQuery
			    //                );
			    //return ($fb->api($streamParams));
			    // store the values in the db
			   if(isset(is_array($me)))
			   {
				   	foreach ($me as $key => $value)
				   	{
				   		
				   	}

			   }
			    //return $me;
				}
				catch(impl\FacebookApiException $e)
				{
					exit($e->getResult());
				}
			    
			}
			else
			{
				   return $this->fbLogin();
			}
		}
		public function fbPostToUserFeed()
		{
			$data2 = array();
		    $data2['access_token'] = $usersPermission; // from database

		    $data2['message'] = 'test message';

		    $facebook->api('/'.$facebook_user_id.'/feed/', 'post', $data2);
		}

		public function fbPostNew()
		{
			$fb=$this->facebook;
			$access_token = $fb->getAccessToken();
			if($fb->getUser())
			{
				$attachment =  array(
                              'access_token' => $access_token,
                              'message' => "Hi test",
                              'name' => "test post to wall",
                              'description' => "testing 1...2....3....",
                              'link' => "http://stackoverflow.com/questions/13023170/facebook-php-sdk-for-wall-post",
                              'picture' => "http://stackoverflow.com/questions/13023170/facebook-php-sdk-for-wall-post",
                              'actions' => array('name'=>'Try it now', 'link' => "http://stackoverflow.com/questions/13023170/facebook-php-sdk-for-wall-post")
                          );

                  try{
                      $fb->api("me/feed","POST",$attachment);
                   }catch(impl\FacebookApiException $e){
                      exit($e);
                  }
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