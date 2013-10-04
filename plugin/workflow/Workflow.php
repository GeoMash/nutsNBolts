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
			$html	=array('<option value="0">No Workflow</option>');
			for ($i=0,$j=count($records); $i<$j; $i++)
			{
				$selected	=($records[$i]['id']==$workflowId)?'selected':'';
				$html[]		='<option value="'.$records[$i]['id'].'" '.$selected.'>'.$records[$i]['name'].'</option>';
			}
			return implode('',$html);
		}

		public function getTransitionsForStep($stepId,$user)
		{
			$return	=array();
			$roleIds=array_column($user['roles'],'id');
			$query	=<<<SQL
SELECT workflow_step_transition.*
FROM workflow_step_transition
LEFT JOIN workflow_transition_role ON workflow_transition_role.transition_id=workflow_step_transition.id
WHERE from_step_id=?
AND workflow_transition_role.role_id IN(?)
SQL;
			if ($this->db->select($query))
			{
				$return=$this->db->result('assoc',array($stepId,$roleIds));
			}
			return $return;
		}
	}
}