<?php
namespace application\nutsNBolts\plugin\workflow
{
	use nutshell\Nutshell;
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;

	class Workflow extends Plugin implements Singleton
	{
		private $db=null;

		public function init()
		{
			if ($connection=Nutshell::getInstance()->config->plugin->Mvc->connection)
			{
				$this->db=$this->plugin->Db->{$connection};
			}
		}

		public function getWorkflowOptions($workflowId=0)
		{
			$records=$this->plugin->Mvc->model->Workflow->read(array('status'=>1));
			$options=array
			(
				array
				(
					'value'		=>0,
					'label'		=>'No Workflow',
					'selected'	=>($workflowId==0)
				)
			);
			for ($i=0,$j=count($records); $i<$j; $i++)
			{
				$options[]=array
				(
					'value'		=>$records[$i]['id'],
					'label'		=>$records[$i]['name'],
					'selected'	=>($records[$i]['id']==$workflowId)
				);
			}
			return $options;
		}

		public function getTransitionsForStep($stepId)
		{
			$return		=array();
			$roleIds	=implode(',',array_column($this->plugin->UserAuth()->getUser()['roles'],'id'));
			if ($this->plugin->UserAuth->isSuper())
			{
				$query	=<<<SQL
SELECT workflow_step_transition.*
FROM workflow_step_transition
LEFT JOIN workflow_transition_role ON workflow_transition_role.transition_id=workflow_step_transition.id
WHERE from_step_id=?
SQL;
			}
			else
			{
				$query	=<<<SQL
SELECT workflow_step_transition.*
FROM workflow_step_transition
LEFT JOIN workflow_transition_role ON workflow_transition_role.transition_id=workflow_step_transition.id
WHERE from_step_id=?
AND workflow_transition_role.role_id IN({$roleIds})
SQL;
			}
			if ($this->db->select($query,array($stepId)))
			{
				$return=$this->db->result('assoc');
			}
			var_dump($return);exit();
			return $return;
		}
	}
}