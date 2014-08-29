<?php
namespace application\nutsNBolts\plugin\workflow
{
	use nutshell\Nutshell;
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;

	class Workflow extends Plugin implements Singleton
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
			$this->action=new Action();
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
				SELECT DISTINCT(workflow_step_transition.id),workflow_step_transition.*,workflow_transition.name,workflow_transition.description
				FROM workflow_transition
				LEFT JOIN workflow_step_transition ON workflow_step_transition.transition_id=workflow_transition.id
				LEFT JOIN workflow_transition_role ON workflow_transition_role.transition_id=workflow_step_transition.transition_id
				WHERE from_step_id=?;
SQL;
			}
			else
			{
				$query	=<<<SQL
				SELECT DISTINCT(workflow_step_transition.id),workflow_step_transition.*,workflow_transition.name,workflow_transition.description
				FROM workflow_transition
				LEFT JOIN workflow_step_transition ON workflow_step_transition.transition_id=workflow_transition.id
				LEFT JOIN workflow_transition_role ON workflow_transition_role.transition_id=workflow_step_transition.transition_id
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
//			$node=$this->model->Node->read(array('id'=>$nodeId));
			if (isset($node[0]))
			{
				$node=$node[0];
			}
			else
			{
				//TODO: Throw exception here.
			}
			//Get the transition
			$transition=$this->model->WorkflowStepTransition->read($transitionId);
			if (isset($transition[0]))
			{
				$transition=$transition[0];
			}

			//Perform the actions for the current step.
			$this->performActionsForTransition($transitionId,$nodeId);

			//Update step.
			$this->model->Node->update(array('workflow_step_id'=>$transition['to_step_id']),array('id'=>$nodeId));
		}

		public function performActionsForTransition($transitionId,$nodeId)
		{
			$actions=$this->getActionsForTransition($transitionId,$nodeId);
			for ($i=0,$j=count($actions); $i<$j; $i++)
			{
				$params=$this->parseActionParams($actions[$i]['params']);
				if (!$this->action->{$actions[$i]['ref']}($nodeId,$params))
				{
					return false;
				}
			}
			return true;
		}


		public function getActionsForTransition($transitionId)
		{
			$return=array();
			$query=<<<SQL
			SELECT *
			FROM workflow_step_transition_action
			LEFT JOIN workflow_action ON workflow_action.id=workflow_step_transition_action.action_id
			WHERE workflow_step_transition_action.step_transition_id=?
			ORDER BY workflow_step_transition_action.order ASC;
SQL;
			if ($this->db->select($query,array($transitionId)))
			{
				$return=$this->db->result('assoc');
			}
			return $return;
		}


		private function parseActionParams($params)
		{
			return json_decode($params,true);
//			$returnParams	=array();
//			$parts			=preg_split('(\n|\r\n)',$params);
//			for ($i=0,$j=count($parts); $i<$j; $i++)
//			{
//				list($key,$value)			=explode('=',$parts[$i]);
//				$returnParams[trim($key)]	=trim($value);
//			}
//			return $returnParams;
		}
	}
}