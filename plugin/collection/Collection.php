<?php
namespace application\nutsNBolts\plugin\collection
{
	use nutshell\Nutshell;
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;

	class Collection extends Plugin implements Singleton
	{
		private $db		=null;
		private $model	=null;

		public function init()
		{
			if ($connection=Nutshell::getInstance()->config->plugin->Mvc->connection)
			{
				$this->db	=$this->plugin->Db->{$connection};
				$this->model=$this->plugin->Mvc->model;
			}
		}

		public function getCollections()
		{
			$return		=array();
			$userId		=$this->plugin->UserAuth->getUserId();
			$collections=$this->model->Collection->read();
			if ($this->plugin->UserAuth->isSuper())
			{
				for ($i=0,$j=count($collections); $i<$j; $i++)
				{
					$result=$this->model->CollectionUser->read
					(
						array
						(
							'collection_id'	=>$collections[$i]['id']
						)
					);
					if (count($result))
					{
						$userResult=$this->model->User->read($result[0]['user_id']);
						if (isset($userResult[0]))
						{
							$collections[$i]['name'].=' ('.$userResult[0]['name_first'].' '.$userResult[0]['name_last'].')';
						}
					}
					$return[]=$collections[$i];
				}
			}
			else
			{
				for ($i=0,$j=count($collections); $i<$j; $i++)
				{
					$result=$this->model->CollectionUser->read
					(
						array
						(
							'collection_id'	=>$collections[$i]['id'],
							'user_id'		=>$userId
						)
					);
					if (count($result))
					{
						$return[]=$collections[$i];
					}
				}
			}
			return $return;
		}
	}
}