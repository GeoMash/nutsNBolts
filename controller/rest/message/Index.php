<?php
namespace application\nutsNBolts\controller\rest\message
{
	use application\plugin\rest\RestController;

	class Index extends RestController
	{
		private $map=array
		(
			''					=>'getAll',
			'{int}/getAllUser'	=>'getAllUser',
			'{int}'				=>'getById'
		);
		
		/*
		 * sample request: $.getJSON('/rest/message/.json');
		 */
		public function getAll()
		{
			$this->respond
			(
				true,
				'OK',
				$this->model->Message->read([])
			);
		}
		
		/*
		 * sample request: $.getJSON('/rest/message/1.json');
		 */
		public function getById()
		{
			$messageId=$this->getFullRequestPart(2);
			if(isset($messageId) && is_numeric($messageId))
			{
				$message=$this->model->Message->read(['id'=>$messageId]);
				if(count($message))
				{
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$message[0]
					);					
				}
				else
				{
					// no message by that id
					$this->setResponseCode(404);
					$this->respond
					(
						true,
						'Not Found',
						'Message not found'
					);					
				}
			}
			else
			{
				$this->setResponseCode(404);
				$this->respond
				(
					false,
					'Expecting message id to be an integer.'
				);
			}
		}	
		
		/*
		 * sample request: $.getJSON('/rest/message/1/getAllUser/.json');
		 */
		public function getAllUser()
		{		
			$userId=$this->getFullRequestPart(2);		
			if(isset($userId) && is_numeric($userId))
			{
				$message=$this->model->Message->read(['to_user_id'=>$userId]);
				
				if(count($message))
				{
					$this->setResponseCode(200);
					$this->respond
					(
						true,
						'OK',
						$message
					);		
				}
				else
				{
					$this->setResponseCode(404);
					$this->respond
					(
						false,
						'No messages found.'
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