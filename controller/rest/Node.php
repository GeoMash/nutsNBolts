<?php
namespace application\nutsNBolts\controller\rest
{
	use application\plugin\rest\RestController;
	use nutshell\Nutshell;
	use nutshell\plugin\session; 
	use \DateTime;

	class Node extends RestController
	{
		private $map=array
		(
			'facetedSearch'				=>'search'
		);

		public function search()
		{
			$facets=$this->request->get('facets');
			if(count($facets))
			{
				
			}
			
			$this->setResponseCode(200);
			$this->respond
			(
				true,
				'OK HaHa',
				$facets
			);			
		}
		
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
				$dateTime=new DateTime();
				if (isset($this->request->get['email']))
				{
					$this->plugin->Mvc->model->User->update(array('date_lastlogin'=> $dateTime->format('Y-m-d H:i:s')),array('email'=>$user['email']));
				}
				else if (isset($this->request->get['phone']))
				{
					$this->plugin->Mvc->model->User->update(array('date_lastlogin'=> $dateTime->format('Y-m-d H:i:s')),array('phone'=>$user['phone']));
				}
				$session=Nutshell::getInstance()->plugin->Session;
				$session->email=$user['email'];
				$session->phone=$user['phone'];
				$session->userId=$user['id'];
				$session->authenticated=true;
				
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					true
				);
			}
			else
			{
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					false
				);
			}
		}
	}
}
?>