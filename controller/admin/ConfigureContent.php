<?php
namespace application\nutsnbolts\controller\admin
{
	use application\nutsnbolts\base\AdminController;
	use nutshell\helper\ObjectHelper;
	
	class ConfigureContent extends AdminController
	{
		
		public function index()
		{
			$this->show404();
		}
		
		public function types($action=null,$id=null)
		{
			$this->addBreadcrumb('Configure Content','icon-cogs');
			$this->addBreadcrumb('Types','icon-th');
			switch ($action)
			{
				case 'add':
				{
					$this->addType();
					break;
				}
				case 'edit':
				{
					$this->editType($id);
					break;
				}
				case 'remove':
				{
					$this->removeType($id);
					break;
				}
				default:
				{
					$this->setContentView('admin/configureContent/types');
					$this->view->getContext()
					->registerCallback
					(
						'getContentTypesList',
						function()
						{
							print $this->generateContentTypeList();
						}
					);
				}
			}
			$this->view->render();
		}
		
		private function addType()
		{
			if (!$this->request->get('name'))
			{
				$this->addBreadcrumb('Add Content Type','icon-plus');
				$this->setContentView('admin/configureContent/addEditType');
			}
			else
			{
				$record=$this->request->getAll();
				$record['site_id']=$this->getSiteId();
				
				$id=$this->model->ContentType->handleRecord($record);
				$this->redirect('/admin/configureContent/types/edit/'.$id);
			}
		}
		
		private function editType($id)
		{
			if ($this->request->get('name'))
			{
				$this->model->ContentType->handleRecord($this->request->getAll());
			}
			$this->addBreadcrumb('Edit Content Type','icon-edit');
			$this->setContentView('admin/configureContent/addEditType');
			if ($record=$this->model->ContentType->read($id))
			{
				$record		=$record[0];
				$partHTML	='';
				$this->view->setVars($record);
				$parts=$this->model->ContentPart->read(array('content_type_id'=>$id));
				
				if (count($parts))
				{
				// var_dump($parts);exit();
					$contentWidgets=$this->application->nutsnbolts->getWidgetList();
					for ($i=0,$j=count($parts); $i<$j; $i++)
					{
						 $partHTML.=$this->buildWidgetHTML($contentWidgets,$i,$parts[$i]);
					}
					$scripts=$this->getFormattedJsScriptList();
					$exec=array();
					$execClasses=$this->getJSClassesToExecute();
					if (count($execClasses))
					{
						for ($i=0,$j=count($execClasses); $i<$j; $i++)
						{
							$exec[]='new '.$execClasses[$i].'();';
						}
					}
					$exec=implode("\n",$exec);
					$partHTML.=<<<JS
<script type="text/javascript">
requirejs.config
(
	{
		waitSeconds:	3,
		baseUrl:		'/js',
		paths:
		{
			jskk:				'vendor/jskk/jskk-1.1.0.min',
			'jskk-optional':	'vendor/jskk/jskk-1.1.0-optional.min',
			\$JSKK:				'vendor/jskk'
		}
	}
);
requirejs
(
	[
		'jskk',
		'jskk-optional',
		'nutsnbolts/Application',
		{$scripts}
	],
	function()
	{
		\$JSKK.when
		(
			function()
			{
				return Object.isDefined(window.\$application);
			}
		).isTrue
		(
			function()
			{
				{$exec}
			}
		);
		
	}
);
</script>
JS;
				}
				$this->view->setVar('parts',$partHTML);
			}
			else
			{
				$this->view->setVar('record',array());
			}
		}
		
		public function removeType($id)
		{
			$this->model->ContentType->handleDeleteRecord($id);
			$this->redirect('/admin/configurecontent/types/');
		}
		
		public function widgets()
		{
			$this->view->render();
		}
		
		public function generateContentTypeList()
		{
			$html=array();
			$contentTypes=$this->model->ContentType->read();
			for ($i=0,$j=count($contentTypes); $i<$j; $i++)
			{
				$html[]=<<<HTML
<div class="box-section news with-icons relative">
	<div class="avatar blue"><i class="{$contentTypes[$i]['icon']} icon-2x"></i></div>
	<a href="/admin/configurecontent/types/remove/{$contentTypes[$i]['id']}">
		<span class="triangle-button red"><i class="icon-remove"></i></span>
	</a>
	<div class="news-content">
		<div class="news-title"><a href="/admin/configurecontent/types/edit/{$contentTypes[$i]['id']}">{$contentTypes[$i]['name']}</a></div>
		<div class="news-text">{$contentTypes[$i]['description']}</div>
	</div>
</div>
HTML;
			}
			return implode('',$html);
		}
		
		public function getAddContentTypeWidgetOptions()
		{
			$options=array();
			$contentWidgets=$this->application->nutsnbolts->getWidgetList();
			for ($i=0,$j=count($contentWidgets); $i<$j; $i++)
			{
				$options[]='<option value="'.$contentWidgets[$i]['namespace'].'">'.$contentWidgets[$i]['name'].'</option>';
			}
			$this->plugin	->Responder('html')
							->setData(implode('',$options))
							->send();
		}
		
		public function getConfigForWidget()
		{
			$widgetOptions=$this->getWidgetInstance($this->request->get('widget'))
								->getConfigHTML($this->request->get('index'));
			
			if (empty($widgetOptions))
			{
				$widgetOptions='None';
			}
			$this->plugin	->Responder('html')
							->setData($widgetOptions)
							->send();
		}
	}
}
?>