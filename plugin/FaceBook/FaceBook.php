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
		// read the user email
		public function isConnectedUser($email)
		{
			$isUser=FALSE;
			$user =$this->plugin->Mvc->model->Subscriber->read(array('email'=>$email));
			if($user!=0)
			{
				$isUser=TRUE;
				//
			}
			return $isUser;
		}

		public function storeUserData()
		{
			if ($this->getUserProfile())
			{
				$user=$this->getUserProfile();
			 	// print($user);
			 	if(!$this->isConnectedUser($user['email']))
			 	{
			 		$params=array();
			 		// foreach($user as $key)
			 		// {
			 			$params['fb_uid']=$user['id'];
			 			$params['first_name']=$user['first_name'];
			 			$params['last_name']=$user['last_name'];
			 			$params['email']=$user['email'];
			 			$params['gender']=$user['gender'];
			 			foreach ($params as $key =>$value)
			 			{
			 				if(empty($value))
							{
							   unset($params[$key]);
							}
			 			}

			 		// }
			 		$this->plugin->Mvc->model->Subscriber->insert($params);
			 	}
			}
		}

		//params include 'scope', 'redirect_uri' and 'display'
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
			 		return $fb->api('/me?fields=picture,first_name,last_name,email,gender');
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

		public function fbPostNew()
		{
			$fb=$this->facebook;
			$pageId=$fb->getUser();
			$access_token = $fb->getAccessToken();
			if($pageId)
			{
				$attachment =  array(
                              'access_token' => $access_token,
                              'message' => $this->request->get('message'),
                              'name' => $this->request->get('name'),
                              'description' =>$this->request->get('description'),
                              'link' => $this->request->get('link'),
                              'picture' => $this->request->get('picture'),
                              'actions' => array(
                              	'name'=>$this->request->get('action_name'),
                              	'link' => $this->request->get('action_link'))
                          );
					//$me=$fb->getUser();
                  try{
                      	$fb->api(
					  'me/nutsnbolts:published',
					  'POST',
					 $attachment
					);
                  	// $facebook->api("/".$pageId."/feed", "POST", 
                  		// array("link"=>$link, "access_token"=>$page["access_token"]));
                   }catch(impl\FacebookApiException $e){
                      exit($e);
                  }
			}
			// else
			// {
			// 	print_r($fb->getUser());
			// }
		}
		public function fbLogout()
		{
			session_unset();
			// $params = array( 'next' => 'http://bizsmart.dev.lan/' );
			// return  header('Location:'.$this->facebook->getLogoutUrl($params)); // $params is optional. 
		}


	}

}
?>