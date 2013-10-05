<?php
namespace application\nutsNBolts\plugin\workflow
{
	use nutshell\Nutshell;
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;

	class Workflow extends Plugin implements Singleton
	{
		const STEP_DIRECTION_ENTER=0;
		const STEP_DIRECTION_LEAVE=1;

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

		public function getWorkflowOptions($workflowId=0)
		{
			$records=$this->model->Workflow->read(array('status'=>1));
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
WHERE from_step_id=?;
SQL;
			}
			else
			{
				$query	=<<<SQL
SELECT workflow_step_transition.*
FROM workflow_step_transition
LEFT JOIN workflow_transition_role ON workflow_transition_role.transition_id=workflow_step_transition.id
WHERE from_step_id=?
AND workflow_transition_role.role_id IN({$roleIds});
SQL;
			}
			if ($this->db->select($query,array($stepId)))
			{
				$return=$this->db->result('assoc');
			}
			return $return;
		}

		public function doTransition($nodeId,$transitionId)
		{
			//Get the node.
			$node=$this->model->Node->read(array('id'=>$nodeId));
			if (isset($node[0]))
			{
				$node=$node[0];
			}
			else
			{
				//TODO: Throw exception here.
			}
			var_dump($node);exit();
			//Get the transition
			$transition=$this->model->WorkflowStepTransition->read($transitionId);
			if (isset($transition[0]))
			{
				$transition=$transition[0];
			}
			//Perform the actions for leaving the current step.
			$this->performActionsForStep($transition['from_step_id'],self::STEP_DIRECTION_LEAVE,$node);

			//Perform the actions for entering the next step.
			$this->performActionsForStep($transition['to_step_id'],self::STEP_DIRECTION_ENTER,$node);


		}

		public function performActionsForStep($stepId,$direction,$node)
		{
			$actions=$this->getActionsForStep($stepId,$direction,$node);
			var_dump($actions);
		}


		public function getActionsForStep($stepId,$directio,$noden)
		{

		}

	}
}