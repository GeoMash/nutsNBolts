<?php
namespace application\nutsNBolts\controller\admin\subscriptions {
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use nutshell\core\exception\NutshellException;
	use application\nutsNBolts\plugin\subscription\Subscription;

	class Subscribers extends AdminController
	{
		public function index()
		{
			try {
				$this->plugin->Auth->can('admin.subscription.subscriber.read');

				$this->addBreadcrumb('Subscriptions', 'icon-envelope', 'subscriptions');

				$this->setContentView('admin/subscriptions/subscribers/list');
				$this->view->getContext()
					->registerCallback(
						'suspend',
						function ($userSubscriptionId) {
							return $this->suspend($userSubscriptionId);
						}
					);
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
						'formatStatus',
						function ($status) {
							return $this->formatStatus($status);
						}
					);

				$renderRef = 'userSubscriptions';
				$this->view->setVar('extraOptions', array());
				$this->execHook('onBeforeRender', $renderRef);
			} catch (AuthException $exception) {
				$this->setContentView('admin/noPermission');
			}
			$this->view->render();
		}

		public function edit($userSubscriberId)
		{
			try {
				$this->addBreadcrumb('User Subscriptions', 'icon-envelope', 'subscribers');
				$this->addBreadcrumb('Edit', 'icon-edit', 'edit/' . $userSubscriberId);

				$this->plugin->Auth
					->can('admin.subscription.subscriber.read')
					->can('admin.subscription.subscriber.update');

				$record = $this->request->getAll();

				if ($this->request->get('id')) {
					$userSubscription = $this->model->SubscriptionUser->handleRecord($record);
					$this->plugin->Notification->setSuccess('Subscription successfully updated.');
					$this->execHook('onEditUserSubscription', $userSubscription);
				}
			} catch (AuthException $exception) {
				$this->setContentView('admin/noPermission');
			} catch (NutshellException $exception) {
				var_dump($exception);
				$this->plugin->Notification->setError('Internal nuts n bolts error. Check the logs.');
			}
			if ($record = $this->model->SubscriptionUser->read($userSubscriberId)) {
				$this->view->setVars($record[0]);
			} else {
				$this->view->setVar('record', array());
			}
			$renderRef = 'userSubscriptions/edit';
			$this->setupAddEdit($renderRef);
		}

		private function setupAddEdit(&$renderRef)
		{
			$this->setContentView('admin/subscriptions/subscribers/addEdit');
			$this->view->getContext()
				->registerCallback
				(
					'getUsers',
					function () {
						return $this->getUsers();
					}
				);
			$this->view->getContext()
				->registerCallback
				(
					'getSubscriptions',
					function () {
						return $this->getSubscriptions();
					}
				);
			$this->view->getContext()
				->registerCallback(
					'formatStatus',
					function ($status) {
						return $this->formatStatus($status);
					}
				);
			$this->view->getContext()
				->registerCallback(
					'getSubscriberEmail',
					function ($userId) {
						return $this->getSubscriberEmail($userId);
					}
				);
			$this->view->getContext()
				->registerCallback(
					'getStatues',
					function () {
						return $this->getStatues();
					}
				);

			$this->view->setVar('extraOptions', array());
			$this->execHook('onBeforeRender', $renderRef);
			$this->view->render();
		}

		public function suspend($userSubscriptionId)
		{
			try {
				$this->plugin->Auth->can('admin.subscription.subscriber.update');
				if ($this->plugin->Subscription->suspendManual($userSubscriptionId)) {
					$this->plugin->Notification->setSuccess('User subscription successfully suspended.');
				} else {
					$this->plugin->Notification->setError('Failed suspending user subscription!');
				}
				$this->redirect('/admin/subscriptions/subscribers');
			} catch (AuthException $exception) {
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}

		public function getSubscribers()
		{
			return $this->plugin->Subscription->getSubscribedUsers();
		}

		public function getUserSubscriptions($userId)
		{
			return $this->plugin->Subscription->getUserSubscriptions($userId);
		}

		public function getUsers()
		{
			return $this->model->User->read([], ['id', 'name_first', 'name_last']);
		}

		public function getSubscriptions()
		{
			return $this->model->Subscription->read([], ['id', 'name']);
		}

		public function getSubscriberEmail($userId)
		{
			return $this->model->User->read(['id' => $userId], ['email'])[0]['email'];
		}

		public function getStatues()
		{
			return [
				Subscription::STATUS_ACTIVE,
				Subscription::STATUS_CANCELLED_MANUAL,
				Subscription::STATUS_CANCELLED_AUTO,
				Subscription::STATUS_PENDING,
			];
		}

		public function formatStatus($status)
		{
			switch ($status) {
				case Subscription::STATUS_ACTIVE:
					return "Active";
				case Subscription::STATUS_CANCELLED_MANUAL:
					return "Cancelled Manually";
				case Subscription::STATUS_CANCELLED_AUTO:
					return "Cancelled Automatically";
				case Subscription::STATUS_PENDING:
					return "Pending";
			}
		}
	}
}
?>