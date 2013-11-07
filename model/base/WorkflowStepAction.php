<?php
/**
 * Database Model for "workflow_step_action"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 30/10/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class WorkflowStepAction extends Base	
	{
		public $name		= 'workflow_step_action';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'transition_id' => 'int(10) NOT NULL ' ,
			'from_step_id' => 'int(10) NOT NULL ' ,
			'to_step_id' => 'int(10) NOT NULL ' 
		);
	}
}
?>