<?php
namespace application\nutsNBolts\plugin\workflow
{
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
	}
}