<?php
namespace application\nutsNBolts\controller\rest
{
	use application\nutsNBolts\plugin\auth\Auth;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use application\plugin\rest\RestController;
	use nutshell\Nutshell;
	use nutshell\plugin\session; 

	class User extends RestController
	{
		private $map=array
		(
			''								=>'getAll',
			'search'						=>'search',
			'{int}'							=>'getById',
			'upsert'						=>'upsert',
			'update'						=>'upsert',
			'insert'						=>'upsert',
			'impersonate/{string}/[*]'		=>'impersonate'
		);
		
		/*
		 * sample request: $.getJSON('/rest/user.json?name=Joe');
		 */
		public function getAll()
		{
			$users=$this->model->User->read($this->request->getAll());
			$this->filterUserData($users);
			$this->setResponseCode(200);
			$this->respond(true,'OK',$users);
		}
		
		public function search()
		{
			$search=$this->request->get('query');
			if (is_numeric($search))
			{
				$query='SELECT * FROM user WHERE id=?;';
			}
			else
			{
				$query	='SELECT * FROM user WHERE email LIKE ?;';
				$search	='%'.$search.'%';
			}
			$users=[];
			if ($result=$this->plugin->Db->nutsnbolts->select($query,[$search]))
			{
				$users=$this->plugin->Db->nutsnbolts->result('assoc');
				$this->filterUserData($users);
			}
			$this->setResponseCode(200);
			$this->respond(true,'OK',$users);
		}
		
		private function filterUserData(Array &$users)
		{
			for ($i=0,$j=count($users); $i<$j; $i++)
			{
				if ((int)$users[$i]['id']===Auth::USER_SUPER)
				{
					unset($users[$i]);
					continue;
				}
				unset($users[$i]['password']);
				unset($users[$i]['salt']);
			}
		}
		
		/*
		 * sample request: $.getJSON('/rest/user/1.json');
		 */
		public function getById()
		{
			$userId=$this->getFullRequestPart(2);
			if (isset($userId) && is_numeric($userId))
			{
				$user=$this->model->User->read(['id'=>$userId]);
				$this->filterUserData($user);
				if($user[0])
				{
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$user[0]
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting user id to be an integer.'
				);
			}
		}	
		
		public function upsert()
		{
			if (!count($this->request->getAll()))
			{
				$this->setResponseCode(400);
				$this->respond
				(
					false,
					'Invalid Request'
				);
			}
			$userId=$this->plugin->Auth->getUserId();
			try
			{
				if ($this->request->get('id'))
				{
					//Is the user allowed to update profiles?
					$this->plugin->Auth->can('user.profile.update');
					
					
					//Is the user updating their own profile?
					if ($this->request->get('id')!=$userId)
					{
						$this->plugin->Auth->can('admin.user.update');
					}
					$this->model->User->update
					(
						$this->request->getAll(),
						['id'=>$userId]
					);
					$this->setResponseCode(200);
					$this->respond(true,'OK');
				}
				else
				{
					//Is the user allowed to create users?
					$this->plugin->Auth->can('admin.user.create');
					$user=$this->model->User->create
					(
						$this->request->getAll()
					);
					$this->setResponseCode(200);
					$this->respond(true,'OK',$user);
				}
			}
			catch(AuthException $exception)
			{
				$this->setResponseCode(401);
				$this->respond
				(
					false,
					'Permission denied'
				);
			}
		}
		
		public function impersonate()
		{
			try
			{
				$action=$this->getFullRequestPart(3);
				if ($this->plugin->Auth->isImpersonating() && $action=='stop')
				{
					$this->plugin->Auth->stopImpersonating();
					$this->setResponseCode(200);
					$this->respond(true,'OK');
				}
				else if ($action=='start')
				{
					$this->plugin->Auth->can('admin.user.impersonate');
					$userId=$this->getFullRequestPart(4);
					if ($userId==Auth::USER_SUPER)
					{
						$this->setResponseCode(417);
						$this->respond
						(
							false,
							'You cannot impersonate root!'
						);
					}
					if ($userId==$this->plugin->Auth->getUserId())
					{
						$this->setResponseCode(417);
						$this->respond
						(
							false,
							'You cannot impersonate yourself!'
						);
					}
					if (is_numeric($userId))
					{
						$this->plugin->Auth->startImpersonating($userId);
						$this->setResponseCode(200);
						$this->respond(true,'OK');
					}
					else
					{
						$this->setResponseCode(417);
						$this->respond
						(
							false,
							'Invalid user ID.'
						);
					}
				}
				
			}
			catch(AuthException $exception)
			{
				$this->setResponseCode(401);
				$this->respond
				(
					false,
					'Permission denied'
				);
			}
			
		}
	}
}
?>