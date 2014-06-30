<?php
namespace application\nutsNBolts\controller\admin\subscriptions {
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use nutshell\core\exception\ApplicationException;
	use nutshell\core\exception\NutshellException;

	class Subscribers extends AdminController
	{
		public function index()
		{
			try {
				$this->plugin->Auth->can('admin.subscription.subscriber.read');

				$this->addBreadcrumb('Subscriptions', 'icon-envelope', 'subscriptions');

				$this->setContentView('admin/subscriptions/subscribers/list');
				$this->view->getContext()
					->registerCallback
					(
						'getSubscribers',
						function () {
							return $this->getSubscribers();
						}
					);
				$this->view->getContext()
					->registerCallback(
						'getUserSubscriptions',
						function ($userId) {
							return $this->getUserSubscriptions($userId);
						}
					);
				$this->view->getContext()
					->registerCallback(
						'formatSubscriptionStatus',
						function ($status) {
							return $this->formatSubscriptionStatus($status);
						}
					);
				$this->view->getContext()
					->registerCallback(
						'suspend',
						function ($userSubscriptionId) {
							return $this->suspend($userSubscriptionId);
						}
					);

				$renderRef = 'subscriptions';
				$this->view->setVar('extraOptions', array());
				$this->execHook('onBeforeRender', $renderRef);
			} catch (AuthException $exception) {
				$this->setContentView('admin/noPermission');
			}
			$this->view->render();
		}

		public function edit($userSubscriberId)
		{
			throw new ApplicationException(0, "Not Yet Implemented");
		}

		public function suspend($userSubscriptionId)
		{
			try
			{
				$this->plugin->Auth->can('admin.subscription.subscriber.update');
				if ($this->plugin->Subscription->suspendManual($userSubscriptionId))
				{
					$this->plugin->Notification->setSuccess('User subscription successfully suspended.');
				}
				else
				{
					$this->plugin->Notification->setError('Failed suspending user subscription!');
				}
				$this->redirect('/admin/subscriptions/subscribers');
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}

		private function getSubscribers()
		{
			return $this->plugin->Subscription->getSubscribedUsers();
		}

		private function getUserSubscriptions($userId)
		{
			return $this->plugin->Subscription->getUserSubscriptions($userId);
		}
	}
}
?>