<?php
namespace application\nutsnbolts\controller\admin
{
	use application\nutsnbolts\base\AdminController;
	
	class Content extends AdminController
	{
		private $typeID=null;
		
		public function view($typeID)
		{
			$this->typeID=$typeID;
			$contentType=$this->model->ContentType->read($this->typeID);
			
			$this->addBreadcrumb('Content','icon-edit');
			$this->addBreadcrumb($contentType[0]['name'],$contentType[0]['icon']);
			$this->setContentView('admin/content/view');
			$this->view->setVar('contentTypeId',$this->typeID);
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
		
		public function add($typeID)
		{
			if (!$this->request->get('title'))
			{
				$this->generateContentParts($typeID);
				$this->setContentView('admin/content/addEdit');
				$this->addBreadcrumb('Add Content','icon-pencil');
				$this->view->render();
			}
			else
			{
				$record=$this->request->getAll();
				
				$record['site_id']			=$this->getSiteId();
				$record['content_type_id']	=$typeID;
				$record['last_user_id']		=$this->getUserId();
				if (!isset($record['id']))
				{
					$record['original_user_id']=$this->getUserId();
				}
				//TODO last_user_id
				$id=$this->model->Node->handleRecord($record);
				$this->redirect('/admin/content/edit/'.$id);
			}
		}
		
		public function edit($id)
		{
			if ($this->request->get('id'))
			{
				$this->model->Node->handleRecord($this->request->getAll());
			}
			$node		=$this->model->Node->read(array('id'=>$id));
			$nodeParts	=$this->model->NodePart->read(array('node_id'=>$id));
			$contentType=$this->model->ContentType->readWithParts($node[0]['content_type_id']);
			$parts		=array();
			
			for ($i=0,$j=count($contentType); $i<$j; $i++)
			{
				$thisNodePart=null;
				for ($k=0,$l=count($nodeParts); $k<$l; $k++)
				{
					if ($nodeParts[$k]['content_part_id']==$contentType[$i]['content_part_id'])
					{
						$thisNodePart=$nodeParts[$k];
					}
				}
				$formElId='node_part_id_'.$contentType[$i]['content_part_id'].'_';
				$formElId.=($thisNodePart)?$thisNodePart['id']:'0';
				
				$widget	=$this->getWidgetInstance($contentType[$i]['widget']);
				$widget->setProperty('name',$formElId);
				$widget->setProperty('value','');
				$input	=$widget->getWidgetHTML($contentType[$i]['config']);
				// var_dump($input);
				// exit();
				
				// $input=str_replace
				// (
				// 	array('{name}','{value}'),
				// 	array($formElId,$thisNodePart?$thisNodePart['value']:''),
				// 	$contentType[$i]['template']
				// );
				
				
				$parts[]=<<<HTML
<div class="control-group">
	<label class="control-label">{$contentType[$i]['label']}</label>
	<div class="controls">
		{$input}
	</div>
</div>
HTML;
			}
			
			$this->view->setVars($node[0]);
			$this->view->setVar('contentType',$contentType[0]['name']);
			$this->view->setVar('contentTypeIcon',$contentType[0]['icon']);
			$this->view->setVar('parts',implode('',$parts));
			
			
			$this->setContentView('admin/content/addEdit');
			$this->addBreadcrumb('Edit Content','icon-pencil');
			$this->view->render();
		}
		
		private function generateContentParts($contentTypeId=null)
		{
			$contentType=$this->model->ContentType->readWithParts($contentTypeId);
			$parts		=array();
			
			for ($i=0,$j=count($contentType); $i<$j; $i++)
			{
				$formElId='node_part_id_'.$contentType[$i]['content_part_id'].'_0';
				$widget	=$this->getWidgetInstance($contentType[$i]['widget']);
				$widget->setProperty('name',$formElId);
				$widget->setProperty('value','');
				$input	=$widget->getWidgetHTML($contentType[$i]['config']);
				// $input=str_replace
				// (
				// 	array('{name}','{value}'),
				// 	array($formElId,''),
				// 	$contentType[$i]['template']
				// );
				$parts[]=<<<HTML
<div class="control-group">
	<label class="control-label">{$contentType[$i]['label']}</label>
	<div class="controls">
		{$input}
	</div>
</div>
HTML;
			}
			$this->view->setVar('contentType',$contentType[0]['name']);
			$this->view->setVar('contentTypeIcon',$contentType[0]['icon']);
			$this->view->setVar('parts',implode('',$parts));
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