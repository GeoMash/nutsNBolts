<?php
namespace application\nutsNBolts\controller\rest\user
{
	use application\nutsNBolts\base\RestController;

	class Index extends RestController
	{
		public function init()
		{
			$this->bindPaths
			(
				array
				(
					''			=>'getAll',
					'{int}'		=>'getById'
				)
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/user/.json');
		 */
		public function getAll()
		{
			$this->respond
			(
				true,
				'OK',
				$this->model->User->read([])
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/user/1.json');
		 */
		public function getById()
		{
			$userId=$this->getFullRequestPart(2);
			if(isset($userId) && is_numeric($userId))
			{
				$user=$this->model->User->read(['id'=>$userId]);
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
	}
}
?>