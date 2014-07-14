<?php
namespace application\nutsNBolts\controller\admin\subscriptions
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use nutshell\core\exception\NutshellException;
	use application\nutsNBolts\plugin\subscription\Subscription;

	class Subscribers extends AdminController
	{
		public function index()
		{
			try
			{
				$this->plugin->Auth->can('admin.subscription.subscriber.read');

				$this->addBreadcrumb('Subscriptions', 'icon-envelope', 'subscriptions');

				$this->setContentView('admin/subscriptions/subscribers/list');
				$this->view->getContext()
					->registerCallback(
						'suspend',
						function ($userSubscriptionId)
						{
							return $this->suspend($userSubscriptionId);
						}
					);
				$this->view->getContext()
					->registerCallback
					(
						'getSubscribers',
						function ()
						{
							return $this->getSubscribers();
						}
					);
				$this->view->getContext()
					->registerCallback(
						'getUserSubscriptions',
						function ($userId)
						{
							return $this->getUserSubscriptions($userId);
						}
					);
				$this->view->getContext()
					->registerCallback(
						'formatStatus',
						function ($status)
						{
							return $this->formatStatus($status);
						}
					);

				$renderRef = 'userSubscriptions';
				$this->view->setVar('extraOptions', array());
				$this->execHook('onBeforeRender', $renderRef);
			}
			catch (AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
			}
			$this->view->render();
		}

		public function edit($userSubscriberId)
		{
			try
			{
				$this->addBreadcrumb('User Subscriptions', 'icon-envelope', 'subscribers');
				$this->addBreadcrumb('Edit', 'icon-edit', 'edit/' . $userSubscriberId);

				$this->plugin->Auth
					->can('admin.subscription.subscriber.read')
					->can('admin.subscription.subscriber.update');

				$record = $this->request->getAll();

				if ($this->request->get('id'))
				{
					$userSubscription = $this->model->SubscriptionUser->handleRecord($record);
					$this->plugin->Notification->setSuccess('Subscription successfully updated.');
					$this->execHook('onEditUserSubscription', $userSubscription);
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
			if ($record = $this->model->SubscriptionUser->read($userSubscriberId))
			{
				$this->view->setVars($record[0]);
			}
			else
			{
				$this->view->setVar('record', array());
			}
			$renderRef = 'userSubscriptions/edit';
			$this->setupAddEdit($renderRef, false);
		}

		public function add()
		{
			try
			{
				$this->plugin->Auth->can('admin.subscription.subscriber.create');

				$this->addBreadcrumb('User Subscriptions', 'icon-envelope', 'subscribers');
				$this->addBreadcrumb('Add Subscription', 'icon-plus', 'add');

				$request = $this->request->getAll();
				if ($request['user_id'])
				{
					$error = false;
					if (!isset($request['subscription_id']) || !is_numeric($request['subscription_id']))
					{
						$this->plugin->Notification->setError('Please select a package.');
						$error = true;
					}
					if (!isset($request['user_id']) || !is_numeric($request['user_id']))
					{
						$this->plugin->Notification->setError('Please select a user.');
						$error = true;
					}

					if (!isset($request['timestamp']))
					{
						$this->plugin->Notification->setError('Please select a Creation Date.');
						$error = true;
					}
					else
					{
						try
						{
							$temp = new \DateTime($request['timestamp']);
						}
						catch (\Exception $ex)
						{
							$this->plugin->Notification->setError('Please select a Valid Creation Date.');
							$error = true;
						}
					}

					if (isset($request['expiry_timestamp']))
					{
						try
						{
							$temp = new \DateTime($request['expiry_timestamp']);
						}
						catch (\Exception $ex)
						{
							$this->plugin->Notification->setError('Please select a Valid Expiry Date.');
							$error = true;
						}
					}

					if ($request['is_new'])
					{
						if (empty($request['cc']['number']))
						{
							$this->plugin->Notification->setError('Please profile a valid Credit Card Number.');
							$error = true;
						}
						if (empty($request['cc']['expiry-month']))
						{
							$this->plugin->Notification->setError('Please specify the expiry month of your Credit Card.');
							$error = true;
						}
						if (empty($request['cc']['expiry-year']))
						{
							$this->plugin->Notification->setError('Please specify the year month of your Credit Card.');
							$error = true;
						}
						if (empty($request['cc']['ccv']))
						{
							$this->plugin->Notification->setError('Please specify the CCV number of your Credit Card.');
							$error = true;
						}
						if (!$error)
						{
							try
							{
								$userId = $request['user_id'];
								$subscriptionId = $request['subscription_id'];
								$subscriptionRequest = $request['cc'];

								$timestamp = new \DateTime($request['timestamp']);
								$expiryTimestamp = isset($request['expiry_timestamp']) && $request['expiry_timestamp'] != '' ? new \DateTime($request['expiry_timestamp']) : null;

								$subscriptionUserId = $this->plugin->Subscription->subscribe($userId, $subscriptionId, $subscriptionRequest, $timestamp, $expiryTimestamp);
								$this->plugin->Notification->setSuccess('Subscription successfully added. Would you like to <a href="/admin/subscriptions/subscribers/add/">Add another one?</a>');
								$this->redirect('/admin/subscriptions/subscribers/edit/' . $subscriptionUserId);
							}
							catch (\Exception $exception)
							{
								$this->plugin->Notification->setError($exception->getMessage());
							}
						}
					}
					else
					{
						if (!$error)
						{
							$record = $this->request->getAll();
							unset($record['is_new']);
							unset($record['cc']);

							if (($id = $this->model->SubscriptionUser->handleRecord($record)) !== false)
							{
								$this->plugin->Notification->setSuccess('Subscription successfully added. Would you like to <a href="/admin/subscriptions/subscribers/add/">Add another one?</a>');
								$this->redirect('/admin/subscriptions/subscribers/edit/' . $id);
							}
							else
							{
								$this->plugin->Notification->setError('Failed to add a Subscription!');
							}
						}
					}
				}

				$renderRef = 'userSubscriptions/add';
				$this->setupAddEdit($renderRef, true);
			}
			catch (AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}

		private function setupAddEdit(&$renderRef, $isAdding)
		{
			$this->setContentView('admin/subscriptions/subscribers/addEdit');
			$this->view->getContext()
				->registerCallback(
					'getUsers',
					function ()
					{
						return $this->getUsers();
					}
				);
			$this->view->getContext()
				->registerCallback(
					'getSubscriptions',
					function ()
					{
						return $this->getSubscriptions();
					}
				);
			$this->view->getContext()
				->registerCallback(
					'formatStatus',
					function ($status)
					{
						return $this->formatStatus($status);
					}
				);
			$this->view->getContext()
				->registerCallback(
					'getSubscriberEmail',
					function ($userId)
					{
						return $this->getSubscriberEmail($userId);
					}
				);
			$this->view->getContext()
				->registerCallback(
					'getStatues',
					function ()
					{
						return $this->getStatues();
					}
				);

			$this->view->setVar('extraOptions', array('isAdding' => $isAdding));
			$this->execHook('onBeforeRender', $renderRef);
			$this->view->render();
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
			catch (AuthException $exception)
			{
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
				Subscription::STATUS_CANCELLED_AUTO
			];
		}

		public function formatStatus($status)
		{
			switch ($status)
			{
				case Subscription::STATUS_ACTIVE:
					return "Active";
				case Subscription::STATUS_CANCELLED_MANUAL:
					return "Cancelled Manually";
				case Subscription::STATUS_CANCELLED_AUTO:
					return "Cancelled Automatically";
			}
		}
	}
}
?>