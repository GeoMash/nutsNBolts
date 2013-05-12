<?php
namespace application\controller\admin
{
	use application\base\AdminController;
	
	class Content extends AdminController
	{
		private $typeID=null;
		
		public function view($id)
		{
			$this->typeID=$id;
			$contentType=$this->model->ContentType->read($id);
			
			$this->addBreadcrumb('Content','icon-edit');
			$this->addBreadcrumb($contentType[0]['name'],$contentType[0]['icon']);
			$this->setContentView('admin/content/view');
			$this->view->setVar('tableHeaderText',$contentType[0]['name']);
			
			$this->view->getContext()
				->registerCallback
				(
					'records',
					function()
					{
						print $this->generateContentRows();
					}
				);
			
			$this->view->render();
		}
		
		public function edit()
		{
			$this->view->render();
		}
		
		public function generateContentRows()
		{
			$records=$this->model->Node->read(array('content_type_id'=>$this->typeID));
			$html	=array();
			for ($i=0,$j=count($records); $i<$j; $i++)
			{
				$html[]=<<<HTML
<tr>
	<td class=""><a href="/admin/content/edit/{$records[$i]['id']}">{$records[$i]['title']}</a></td>
	<td class="">{$records[$i]['date_created']}</td>
	<td class="">{$records[$i]['last_user_id']}</td>
	<td class="">{$records[$i]['status']}</td>
</tr>
HTML;
			}
			return implode('',$html);
		}
	}
}
?>