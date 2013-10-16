<?php
namespace application\nutsNBolts\plugin\message
{
	use nutshell\Nutshell;
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;

	class Message extends Plugin implements Singleton
	{
		private $db		=null;
		private $model	=null;
		private $action	=null;

		public function init()
		{
			if ($connection=Nutshell::getInstance()->config->plugin->Mvc->connection)
			{
				$this->db	=$this->plugin->Db->{$connection};
				$this->model=$this->plugin->Mvc->model;
			}
		}

		public function sendMessage($toId,$subject,$body)
		{
			return $this->model->Message->insertAssoc
			(
				array
				(
					'from_user_id'	=>$this->plugin->UserAuth->getUserId(),
					'to_user_id'	=>$toId,
					'subject'		=>$subject,
					'body'			=>$body
				)
			);
		}


	}
}