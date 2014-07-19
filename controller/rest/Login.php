<?php
namespace application\nutsNBolts\controller\rest
{
	use application\plugin\rest\RestController;
	use nutshell\Nutshell;
	use nutshell\plugin\session; 
	use \DateTime;

	class Login extends RestController
	{
		private $map=array
		(
			'loginByEmail'				=>'loginByEmail'
		);
		
		/*
		 * sample request: $.getJSON('/rest/login/loginByEmail/xxx/***.json');
		 */
		public function loginByEmail()
		{
			
			$user=$this->plugin->UserAuth->authenticate
			(
				[
					'email'=>$this->request->get('email')
				],
				$this->request->get('password')
			);
			if ($user)
			{
				$data=true;
				if (!empty($this->plugin->Session->returnURL))
				{
					$data=$this->plugin->Session->returnURL;
				}
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					$data
				);
			}
			else
			{
				$this->setResponseCode(200);
				$this->respond
				(
					false,
					'OK',
					false
				);
			}
		}
	}
}
?>