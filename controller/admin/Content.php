<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	
	class Content extends AdminController
	{
		private $typeID		=null;
		private $contentType=null;
		
		public function init()
		{
			switch ($this->request->node(2))
			{
				case 'view':
				case 'add':
				{
					$this->typeID	=$this->request->lastNode();
					break;
				}
				case 'edit':
				{
					$node			=$this->model->Node->read(array('id'=>$this->request->lastNode()));
					$this->typeID	=$node[0]['content_type_id'];
					break;
				}
			}
			$this->contentType	=$this->model->ContentType->read($this->typeID)[0];
		}
		
		public function index()
		{
			$this->show404();
			$this->view->render();
		}
		
		public function view($typeID)
		{
			$this->typeID=$typeID;

			$contentType=$this->model->ContentType->read($this->typeID);
			
			$this->addBreadcrumb('Content','icon-edit','content');
			$this->addBreadcrumb($contentType[0]['name'],$contentType[0]['icon'],'view/'.$contentType[0]['id']);
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
			if (!$this->userCanAccessContentType($contentType[0]))
			{
				$this->view->setVar('canAddContent',false);
				$this->plugin->Notification->setError('You don\'t have permission to view this!');
			}
			else
			{
				$this->view->setVar('canAddContent',true);
			}
			$this->view->render();
		}
		
		public function add($typeID)
		{
			$contentType=$this->model->ContentType->read($this->request->node(3));
			if (!$this->userCanAccessContentType($contentType[0]))
			{
				$this->plugin->Notification->setError('You don\'t have permission to add content to this!');
				$this->redirect('/admin/content/view/'.$typeID);
			}
			if (!$this->request->get('title'))
			{
				$this->generateContentParts($typeID);
				$this->setContentView('admin/content/addEdit');
				$this->addBreadcrumb('Content','icon-edit','content');
				$this->addBreadcrumb($contentType[0]['name'],$contentType[0]['icon'],'view/'.$typeID);
				$this->addBreadcrumb('Add Content','icon-pencil','add/'.$typeID);
				$this->view->setVar('contentTypeId',$typeID);
				$this->view->render();
			}
			else
			{
				$record=$this->request->getAll();				
				foreach ($record AS $key=>$rec)
				{
					// checking to see if an array is passed, and converting it to a json object
					if(is_array($rec))
					{
						$record[$key]='application/json: '.json_encode($rec);
					}
				}

				$record['site_id']			=$this->getSiteId();
				$record['content_type_id']	=$typeID;
				$record['last_user_id']		=$this->getUserId();
				if (!isset($record['id']))
				{
					$record['original_user_id']=$this->getUserId();
				}
				//TODO last_user_id

				$id=$this->model->Node->handleRecord($record);				
				if (is_numeric($id))
				{
					$this->plugin->Notification->setSuccess('Content successfully added. Would you like to <a href="/admin/content/add/'.$typeID.'">Add another one?</a>');
					$this->redirect('/admin/content/edit/'.$id);
				}
				else
				{
					$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
				}
			}
		}
		
		public function edit($id)
		{
			$node		=$this->model->Node->read(array('id'=>$id));
			$nodeParts	=$this->model->NodePart->read(array('node_id'=>$id));
			$nodeURLs	=$this->model->NodeMap->read(array('node_id'=>$id));
			$nodeTags	=array_column($this->model->NodeTag->read(array('node_id'=>$id),array('tag')),'tag');


			$contentTypeForPermCheck=$this->model->ContentType->read($node[0]['content_type_id']);
			if (!$this->userCanAccessContentType($contentTypeForPermCheck[0]))
			{
				$this->plugin->Notification->setError('You don\'t have permission to edit this content item!');
				$this->redirect('/admin/dashboard/');
			}
			unset($this->plugin->Session->returnToAction);
		
			if ($this->request->get('id'))
			{
				foreach ($this->request->getAll() AS $key=>$rec)
				{
					// checking to see if an array is passed, and converting it to a json object
					if(is_array($rec))
					{
						$record[$key]='application/json: '.json_encode($rec);
					}
					else
					{
						$record[$key]=$rec;
					}
				}	
	
				if (!$this->contentType['workflow_id'])
				{
					if ($this->model->Node->handleRecord($record))
					{
						$this->plugin->Notification->setSuccess('Content successfully edited.');
					}
					else
					{
						$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
					}
				}
				else
				{
					$this->plugin->Workflow->doTransition($this->request->get('id'),$this->request->get('transition_id'));
				}
			}

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
				if (!is_null($contentType[$i]['widget']))
				{
					$widget	=$this->getWidgetInstance($contentType[$i]['widget']);
					$widget->setProperty('name',$formElId);
					$widget->setProperty('value',$thisNodePart['value']);
					$input	=$widget->getWidgetHTML($contentType[$i]['content_part_id'],$contentType[$i]['config']);
					$parts[]=<<<HTML
<div class="control-group">
	<label class="control-label">{$contentType[$i]['label']}</label>
	<div class="controls">
		{$input}
	</div>
</div>
HTML;
					if ($widget->hasWidgetJS())
					{
						$exec=strtolower(str_replace
						(
							array('application\\','\\'),
							array('','.'),
							$contentType[$i]['widget']
						)).'.Main('.$contentType[$i]['content_part_id'].')';
						$this->JSLoader->loadScript('/admin/script/widget/main/'.$contentType[$i]['widget'],$exec);
					}
				}
			}
			$parts[]=$this->JSLoader->getLoaderHTML();
			$this->view->setVars($node[0]);
			$this->view->setVar('contentType',		$contentType[0]['name']);
			$this->view->setVar('contentTypeIcon',	$contentType[0]['icon']);
			$this->view->setVar('nodeURLs',			$nodeURLs);
			$this->view->setVar('nodeTags',			implode(',',$nodeTags));
			$this->view->setVar('parts',			implode('',$parts));
			$this->view->setVar('contentTypeId',	$node[0]['content_type_id']);
			$this->view->setVar('hasWorkflow',		(bool)$contentType[0]['workflow_id']);
			$this->view->getContext()
				->registerCallback
				(
					'getWorkflowTransitions',
					function() use ($node)
					{
						$transitions=$this->plugin->Workflow->getTransitionsForStep($node[0]['workflow_step_id']);
						$html		=array();
						for ($i=0,$j=count($transitions); $i<$j; $i++)
						{
							$html[]='<button data-action="doWorkflowTransition"'
											.' data-transition="'.$transitions[$i]['id'].'"'
											.' type="button"'
											.' class="btn btn-blue"'
											.' title="'.$transitions[$i]['description'].'">'.$transitions[$i]['name'].'</button>&nbsp;';
						}
						print implode('',$html);
					}
				);
			$this->setContentView('admin/content/addEdit');
			$this->addBreadcrumb('Content','icon-edit','content');
			$this->addBreadcrumb($contentType[0]['name'],$contentType[0]['icon'],'view/'.$id);
			$this->addBreadcrumb('Edit Content','icon-pencil','edit/'.$id);
			$this->view->render();
		}
		
		private function generateContentParts($contentTypeId=null)
		{
			$contentType=$this->model->ContentType->readWithParts($contentTypeId);
			$parts		=array();
			
			for ($i=0,$j=count($contentType); $i<$j; $i++)
			{
				$formElId='node_part_id_'.$contentType[$i]['content_part_id'].'_0';
				if (!is_null($contentType[$i]['widget']))
				{
					$widget	=$this->getWidgetInstance($contentType[$i]['widget']);
					$widget->setProperty('name',$formElId);
					$widget->setProperty('value','');
					$input	=$widget->getWidgetHTML($contentType[$i]['content_part_id'],$contentType[$i]['config']);
					$parts[]=<<<HTML
<div class="control-group">
	<label class="control-label">{$contentType[$i]['label']}</label>
	<div class="controls">
		{$input}
	</div>
</div>
HTML;
					if ($widget->hasWidgetJS())
					{
						$exec=strtolower(str_replace
						(
							array('application\\','\\'),
							array('','.'),
							$contentType[$i]['widget']
						)).'.Main('.$contentType[$i]['content_part_id'].')';
						$this->JSLoader->loadScript('/admin/script/widget/main/'.$contentType[$i]['widget'],$exec);
					}
					else
					{

					}
				}
			}
			$parts[]=$this->JSLoader->getLoaderHTML();
			$this->view->setVar('contentType',$contentType[0]['name']);
			$this->view->setVar('contentTypeIcon',$contentType[0]['icon']);
			$this->view->setVar('parts',implode('',$parts));
		}
		
		public function generateContentRows()
		{
			if ($this->canAccessContentType())
			{
				$records=$this->model->Node->read(array('content_type_id'=>$this->typeID));
				$html	=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					$html[]=<<<HTML
<tr>
	<td><a href="/admin/content/edit/{$records[$i]['id']}">{$records[$i]['title']}</a></td>
	<td>{$records[$i]['date_created']}</td>
	<td>{$records[$i]['last_user_id']}</td>
	<td>{$records[$i]['status']}</td>
</tr>
HTML;
				}
				return implode('',$html);
			}
			return '';
		}
		
		private function canAccessContentType()
		{
			return $this->challangeRole($this->contentType['roles']);
		}
	}
}
?>