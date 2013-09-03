<?php
namespace application\nutsNBolts\plugin\faceBook
{
	use nutshell\core\plugin\Plugin;
	use nutshell\behaviour\Singleton;
	// use nutshell\behaviour\Native;
	use \Exception;
	use nutshell\core\exception\NutshellException;
	use application\nutsNBolts\plugin\faceBook\FacebookException;
	use application\nutsNBolts\plugin\faceBook\impl\BaseFacebook;
	use application\nutsNBolts\plugin\faceBook\impl\facebook as FacebookBase;

	class FaceBook extends Plugin implements Singleton
	{
		private $facebook;
		private $isLoggedIn=FALSE;
		private $appId='576839855687694';
		private $secret='efeb1366341aceaace574ca42291bae3';
		private $user;
		private $access_token;

		public static function registerBehaviours()
		{

		}

		public function init()
		{
			require_once(__DIR__._DS_.'impl/base_facebook.php');
			$this->facebook=new FaceBookBase(
				array(
					'appId'  => $this->config->app_id,
  					'secret' => $this->config->app_secret,
  					'cookie'=>TRUE
					)
				);
		}

		// function checks to see if the logged user is saved in the database or not
		public function isConnectedUser($email)
		{
			$isUser=FALSE;
			$user =$this->plugin->Mvc->model->Facebook->read(array('email'=>$email));

			if(count($user) > 0)
			{
				$isUser=TRUE;
			}
			return $isUser;
		}

		public function storeUserData()
		{
			if ($this->getUserProfile())
			{
				$user=$this->getUserProfile();
			 	if(!$this->isConnectedUser($user['email']))
			 	{
			 		$params=array();
			 			$params['fb_uid']=$user['id'];
			 			$params['email']=$user['email'];
			 			$params['firstName']=$user['first_name'];
			 			$params['lastName']=$user['last_name'];
			 			$params['gender']=$user['gender'];
			 			foreach ($params as $key =>$value)
			 			{
			 				if(empty($value))
							{
							   unset($params[$key]);
							}
			 			}
			 		$this->plugin->Mvc->model->Facebook->insert($params);
			 	}
			}
		}

		// this is the login function, can define the redict url and also scope of permissions
		public function fbLogin($location)
		{
			// location is passed dynamically from the view, since we want to load the same article after successful login.
			$url = "http://".$_SERVER['HTTP_HOST']."/".$location;
			$params=
					array(
						'scope'=>'email,publish_stream',
						'redirect_uri'=>$url
						);
					// $this->accessToken();
			$fb=$this->facebook;
			if(isset($params))
			{
				return $fb->getLoginUrl($params);
			}
		}

		//pass in location as a parameter for redirecting back to the article page
		public function getUserProfile($location='')
		{

			$fb=$this->facebook;
			if ($fb->getUser()!=0) 
			{
				// user is logged in

				try
				{
			 		return $fb->api('/me');
				}
				catch(FacebookException $e)
				{
					exit($e->getResult());
				}
			}
			else
			{

				// send the login URL to the view
				return $this->fbLogin($location);
			}
		}

		// check to see if the user is logged in, return boolean
		public function checkLogged()
		{
			$fb=$this->facebook;

			if ($fb->getUser()!=0) 
			{
				// call the storeUserData method to save the user data
				$this->storeUserData();
				// the user is logged in
				return true;
				
				try
				{
			 
				}
				catch(FacebookException $e)
				{
					exit($e->getResult());
				}
			}
			else
			{
				// user isn't logged in
				return false;
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
				try
				{
					$fb->api(
						'me/nutsnbolts:published',
						'POST',
						$attachment
					);
				}
				catch(FacebookException $e)
				{
					exit($e->getResult());
				}
			}
		}


		public function fbLogout($url="/")
		{
			$urlArray=explode('/', $url);
			if($urlArray[0]=='facebookLogout')
			{
				array_shift($urlArray);
				$urlArray=implode('/', $urlArray);
			}

			print_r($urlArray);
			// die();
			$_SESSION = array();    //clear session array
			session_destroy();
			header('Location: /'.$urlArray);
			die();
		}


	}

}
?>