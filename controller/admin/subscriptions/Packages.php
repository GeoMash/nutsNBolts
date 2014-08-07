<?php
namespace application\nutsNBolts\controller\admin\subscriptions
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use nutshell\core\exception\NutshellException;
	
	class Packages extends AdminController
	{
		public function index()
		{
			try
			{
				$this->plugin->Auth->can('admin.subscription.package.create');
				
				$this->addBreadcrumb('Subscriptions','icon-envelope','subscriptions');
				
				$this->setContentView('admin/subscriptions/packages/list');
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
				$this->addBreadcrumb('Subscriptions','icon-envelope','subscriptions');
				$this->addBreadcrumb('Add','icon-plus','add');
				
				$this->plugin->Auth->can('admin.subscription.package.create');
				
				$record=$this->request->getAll();
				
				if ($this->request->get('name'))
				{
					$this->defaultRecordFields($record);
					
					$subscription=$this->model->Subscription->handleRecord($record);
					$this->plugin->Notification->setSuccess('Subscription successfully added. Would you like to <a href="/admin/subscriptions/packages/add/">Add another one?</a>');
					$this->execHook('onAddSubscription',$subscription);
					$this->redirect('/admin/subscriptions/packages/edit/'.$subscription['id']);
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
				$this->addBreadcrumb('Subscriptions','icon-envelope','subscriptions');
				$this->addBreadcrumb('Edit','icon-edit','edit/'.$id);
				
				$this->plugin->Auth	->can('admin.subscription.package.read')
									->can('admin.subscription.package.update');
				
				$record=$this->request->getAll();
				
				if ($this->request->get('name'))
				{
					$this->defaultRecordFields($record);
					
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
				$this->plugin->Auth->can('admin.subscription.package.delete');
				
				if ($this->model->Subscription->delete($id))
				{
					$this->plugin->Notification->setSuccess('Subscription successfully removed.');
					$this->redirect('/admin/subscriptions/packages/');
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
		
		private function defaultRecordFields(&$record)
		{
			//Defaulting
			$record['duration'] = $record['duration'] == 0? null : $record['duration'];
			$record['billing_interval'] = $record['billing_interval'] == 0? null : $record['billing_interval'];
			$record['total_bills'] = $record['total_bills'] == 0? null : $record['total_bills'];
		}
		
		private function setupAddEdit(&$renderRef)
		{
			$this->setContentView('admin/subscriptions/packages/addEdit');
			
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