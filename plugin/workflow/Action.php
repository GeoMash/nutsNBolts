<?php
namespace application\nutsNBolts\plugin\workflow
{
	use nutshell\Nutshell;
	use nutshell\core\plugin\PluginExtension;

	class Action extends PluginExtension
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

		public function setNodeStatus($nodeId,$params)
		{
			if (isset($params['status']) && is_numeric($params['status']))
			{
				$this->model->Node->update(array('status'=>$params['status']),array('id'=>$nodeId));
				return true;
			}
			else
			{
				//TODO: throw exception.
			}
			return false;
		}

		public function sendNotificationToUser($nodeId,$params)
		{
			return $this->plugin->Message->sendMessage
			(
				$params['toId'],
				$params['subject'],
				str_replace('{$nodeId}',$nodeId,$params['message'])
			);
		}

		public function sendNotificationToRole($nodeId,$params)
		{
			$users=$this->model->User->getUsersByRole($params['role']);
			for ($i=0,$j=count($users); $i<$j; $i++)
			{
				$this->plugin->Message->sendMessage
				(
					$users[$i]['id'],
					$params['subject'],
					str_replace('{$nodeId}',$nodeId,$params['message'])
				);
			}
			return true;
		}
	}
}