<?php
namespace application\nutsNBolts\controller\rest\login
{
	use application\nutsNBolts\base\RestController;
	use nutshell\Nutshell;
	use nutshell\plugin\session; 

	class Index extends RestController
	{
		public function init()
		{
			$this->bindPaths
			(
				array
				(
					'{int}'		=>'checkById'
				)
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/login/1.json');
		 */
		public function checkById()
		{
			$userId=$this->getFullRequestPart(2);
			if(isset($userId) && is_numeric($userId))
			{

				if(Nutshell::getInstance()->plugin->Session->authenticated)
				{
					// logged in
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
					// logged out
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						false
					);								
				}

			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting user id to be integer.'
				);
			}
		}		
	}
}
?>