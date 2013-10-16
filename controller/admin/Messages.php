<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use nutshell\helper\ObjectHelper;
	
	class Messages extends AdminController
	{
		public function init()
		{
			$url=$this->request->getNodes();
			$userId=$this->plugin->UserAuth->getUserId();
			
			switch (count($url))
			{
				case '2':
					$this->viewAll($userId);
				break;
				
				case '4':
					$messageId=$url[3];
					$this->view($userId,$messageId);
				break;
			}
			
		}
		private function view($userId,$messageId)
		{
			$search=array
			(
			 	'id'	=>$messageId
			);
			
			if($record=$this->model->Message->read($search))
			{
				$read=array
				(
				 	'status'	=>1
				);
				$this->model->Message->update($read,$search);
			}
			$this->view->setVar('record',$record);
			$this->setContentView('admin/viewMessage');
			$this->addBreadcrumb('Messages','icon-inbox','messages');
			// $this->addBreadcrumb('Messages','icon-inbox','messages');		
		}
		
		private function viewAll($id)
		{
			$search=array
			(
			 	'to_user_id'	=>$id
			 );
			
			$this->setContentView('admin/messages');
			$this->view->getContext()
				->registerCallback
				(
					'allMessages',
					function()
					{
						print $this->generateMessageRows();
					}
				);			
			$this->addBreadcrumb('Messages','icon-inbox','messages');		
		}
		
		private function generateMessageRows()
		{

			$records=$this->model->Message->read(array('to_user_id'=>$this->plugin->UserAuth->getUserId() ));
			$html	=array();
			for ($i=0,$j=count($records); $i<$j; $i++)
			{
				if($records[$i]['status']==0)
				{
					$html[]=<<<HTML
<tr>
	<td><b><a href="/admin/messages/view/{$records[$i]['id']}">{$records[$i]['subject']}</a></b></td>
	<td><b>{$records[$i]['body']}</b></td>
</tr>
HTML;
				}
				else
				{
					$html[]=<<<HTML
<tr>
	<td><a href="/admin/messages/view/{$records[$i]['id']}">{$records[$i]['subject']}</a></td>
	<td>{$records[$i]['body']}</td>
</tr>
HTML;
			}
			}
			return implode('',$html);
		}
	}
}
?>