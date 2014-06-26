<?php
namespace application\nutsNBolts\controller\admin\subscriptions {
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use application\nutsNBolts\plugin\subscription\Subscription;
	use nutshell\core\exception\ApplicationException;
	use nutshell\core\exception\NutshellException;

	class Subscribers extends AdminController
	{
		public function index()
		{
			try {
				$this->plugin->Auth->can('admin.subscription.subscriber.read');

				$this->addBreadcrumb('Configure Content', 'icon-cogs', 'configurecontent');
				$this->addBreadcrumb('Subscriptions', 'icon-envelope', 'subscriptions');

				$this->setContentView('admin/configureContent/subscriptions/list');
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

				$renderRef = 'subscriptions';
				$this->view->setVar('extraOptions', array());
				$this->execHook('onBeforeRender', $renderRef);
			} catch (AuthException $exception) {
				$this->setContentView('admin/noPermission');
			}
			$this->view->render();
		}

		public function edit($subscriberId)
		{
			throw new ApplicationException(0, "Method not implemented");
		}

		public function remove($subscriberId)
		{
			throw new ApplicationException(0, "Method not implemented");
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