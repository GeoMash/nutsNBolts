<?php
namespace application\nutsNBolts\controller\admin\configureContent
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\Auth;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use application\nutsNBolts\plugin\plupload\ThumbnailMaker;
	use application\nutsNBolts\plugin\policy\exception\PolicyException;
	use application\nutsNBolts\plugin\user\exception\UserException;
	use nutshell\core\exception\NutshellException;
	use nutshell\helper\ObjectHelper;
	
	class Subscriptions extends AdminController
	{
		public function index()
		{
			try
			{
				$this->plugin->Auth->can('admin.content.subscription.read');
				
				$this->addBreadcrumb('Configure Content','icon-cogs','configurecontent');
				$this->addBreadcrumb('Subscriptions','icon-envelope','subscriptions');
				
				$this->setContentView('admin/configureContent/subscriptions/list');
				$this->view->getContext()
				->registerCallback
				(
					'getSubscriptions',
					function()
					{
						return $this->getSubscriptions();
					}
				);
				
				$renderRef='subscriptions';
				$this->view->setVar('extraOptions',array());
				$this->execHook('onBeforeRender',$renderRef);
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
			}
			$this->view->render();
		}
		
		public function add()
		{
			try
			{
				$this->addBreadcrumb('Configure Content','icon-cogs','configurecontent');
				$this->addBreadcrumb('Subscriptions','icon-envelope','subscriptions');
				$this->addBreadcrumb('Add','icon-plus','add');
				
				$this->plugin->Auth->can('admin.content.subscription.create');
				
				$record=$this->request->getAll();
				
				if ($this->request->get('name'))
				{
					$subscription=$this->model->Subscription->handleRecord($record);
					$this->plugin->Notification->setSuccess('Subscription successfully added. Would you like to <a href="/admin/configurecontent/subscriptions/add/">Add another one?</a>');
					$this->execHook('onAddSubscription',$subscription);
					$this->redirect('/admin/configurecontent/subscriptions/edit/'.$subscription['id']);
				}
			}
			catch (AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
			}
			catch (NutshellException $exception)
			{
				$this->plugin->Notification->setError('Internal nuts n bolts error. Check the logs.');
			}
			$this->execHook('onBeforeAddUser',$record);
			$this->view->setVars($record);
			$renderRef='subscriptions/add';
			$this->setupAddEdit($renderRef);
		}
		
		public function edit($id)
		{
			try
			{
				$this->addBreadcrumb('Configure Content','icon-cogs','configurecontent');
				$this->addBreadcrumb('Subscriptions','icon-envelope','subscriptions');
				$this->addBreadcrumb('Edit','icon-edit','edit/'.$id);
				
				$this->plugin->Auth	->can('admin.content.subscription.read')
									->can('admin.content.subscription.update');
				
				$record=$this->request->getAll();
				
				if ($this->request->get('name'))
				{
					$subscription=$this->model->Subscription->handleRecord($record);
					$this->plugin->Notification->setSuccess('Subscription successfully updated.');
					$this->execHook('onEditSubscription',$subscription);
				}
			}
			catch (AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
			}
			catch (NutshellException $exception)
			{
				$this->plugin->Notification->setError('Internal nuts n bolts error. Check the logs.');
			}
			if ($record=$this->model->Subscription->read($id))
			{
					$this->view->setVars($record[0]);
				}
				else
				{
					$this->view->setVar('record',array());
				}
			$renderRef='subscriptions/edit';
			$this->setupAddEdit($renderRef);
		}
		
		public function remove($id)
		{
			try
			{
				$this->plugin->Auth->can('admin.content.subscription.delete');
				
				if ($this->model->Subscription->delete($id))
				{
					$this->plugin->Notification->setSuccess('Subscription successfully removed.');
					$this->redirect('/admin/configurecontent/subscriptions/');
				}
				else
				{
					$this->plugin->Notification->setError('Oops! Something went wrong, and this is a terrible error message!');
				}
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		private function setupAddEdit(&$renderRef)
		{
			$this->setContentView('admin/configureContent/subscriptions/addEdit');
			
			$this->view->setVar('extraOptions',array());
			$this->execHook('onBeforeRender',$renderRef);
			$this->view->render();
		}
		
		
		public function getSubscriptions()
		{
			return $this->model->Subscription->read();
		}
		
	}
}
?>