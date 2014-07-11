<?php
namespace application\nutsNBolts\plugin\subscription
{
	use application\nutsNBolts\plugin\payment\Payment;
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use application\nutsNBolts\base\Plugin;
	use nutshell\core\exception\ApplicationException;

	class Subscription extends Plugin implements Singleton, Native
	{
		const DATETIME_FORMAT = 'Y-m-d h:i:s';
		
		const STATUS_CANCELLED_MANUAL = -2;
		const STATUS_CANCELLED_AUTO = -1;
		const STATUS_ACTIVE = 1;

		const RELAXATION_DAYS = 3;

		public function init()
		{
		}

		/**
		 * This method subscribe a user to any type of subscription packages
		 * @param $userId, The user ID to be subscribed
		 * @param $subscriptionId, The Package ID 
		 * @param $subscriptionRequest, The Credit Card Information
		 * @throws \nutshell\core\exception\ApplicationException
		 */
		public function subscribe($userId, $subscriptionId, $subscriptionRequest)
		{
			//Receiving Credit Card information
			$cardNo = $subscriptionRequest['number'];
			$cardCode = $subscriptionRequest['ccv'];

			$cardExpiryMonth = str_pad($subscriptionRequest['expiry-month'], 2, '0', STR_PAD_LEFT);
			$cardExpiryYear = str_pad($subscriptionRequest['expiry-year'], 2, '0', STR_PAD_LEFT);
			$expDate = $cardExpiryMonth . $cardExpiryYear;

			//Receiving Payment Amount
			$subscription = $this->model->Subscription->read($subscriptionId)[0];
			$amount = $subscription['amount'];
			$duration = $subscription['duration'];

			//Receiving the necessary user information
			$user = $this->model->User->read([
				'id' => $userId
			])[0];
			$userFirstName = $user['name_first'];
			$userLastName = $user['name_last'];

			//Checking for Subscription Package Activity
			if (!$subscription->status != STATUS_ACTIVE)
				throw new ApplicationException(0, "Subscription is inactive");

			//Starting the payment process
			$payment = $this->plugin->Payment("AuthorizeNet");

			if ($subscription['recurring'])
			{

				$transactionResponse = null;
				$arbStatus = $payment->createRecurringSubscription($userFirstName, $userLastName, $amount, $cardNo, $cardCode, $expDate, $transactionResponse, $duration);
				$arbId = $arbStatus->getSubscriptionId();
				$status = $this::STATUS_ACTIVE;

				$timestamp = new \DateTime('now'); //Use this? or take from TransactionResponse? How precise we want it?
				$timestamp_formatted = $timestamp->format($this::DATETIME_FORMAT);
				$expiry_timestamp_formatted = $timestamp->add(new \DateInterval("P" . $duration . "M"))->format($this::DATETIME_FORMAT);

				$transactionId = $transactionResponse->transaction_id;

				//Activating the subscription for the user
				$subscriptionUserId = $this->model->SubscriptionUser->insertAssoc([
					'subscription_id' => $subscriptionId,
					'user_id' => $userId,
					'arb_id' => $arbId,
					'timestamp' => $timestamp_formatted,
					'expiry_timestamp' => $expiry_timestamp_formatted,
					'status' => $status
				]);

				$subscriptionTransactionId = $this->model->SubscriptionTransaction->insertAssoc([
					'gateway_transaction_id' => $transactionId,
					'timestamp' => $timestamp_formatted
				]);

				$this->model->SubscriptionInvoice->insertAssoc([
					'subscription_user_id' => $subscriptionUserId,
					'subscription_transaction_id' => $subscriptionTransactionId,
					'timestamp' => $timestamp_formatted,
					'meta' => json_encode($transactionResponse)
				]);
			}
			else
			{
				$transactionResponse = $payment->chargeCard($cardNo, $cardCode, $expDate, $amount);
				$transactionId = $transactionResponse->transaction_id;

				$timestamp = new \DateTime('now'); //Use this? or take from TransactionResponse? How precise we want it?
				$duration = $subscription['duration'];

				$timestamp_formatted = $timestamp->format($this::DATETIME_FORMAT);
				$expiry_timestamp_formatted = $timestamp->add(new \DateInterval("P" . $duration . "M"))->format($this::DATETIME_FORMAT);

				//Activating the subscription for the user
				$subscriptionUserId = $this->model->SubscriptionUser->insertAssoc([
					'subscription_id' => $subscriptionId,
					'user_id' => $userId,
					'arb_id' => null,
					'expiry_timestamp' => $expiry_timestamp_formatted,
					'timestamp' => $timestamp_formatted,
					'status' => $this::STATUS_ACTIVE
				]);

				$subscriptionTransactionId = $this->model->SubscriptionTransaction->insertAssoc([
					'gateway_transaction_id' => $transactionId,
					'timestamp' => $timestamp_formatted
				]);

				$this->model->SubscriptionInvoice->insertAssoc([
					'subscription_user_id' => $subscriptionUserId,
					'subscription_transaction_id' => $subscriptionTransactionId,
					'timestamp' => $timestamp_formatted,
					'meta' => json_encode($transactionResponse)
				]);
			}
		}

		/**
		 * Checks whether a User has at least one active subscription
		 * @param $userId
		 * @return bool
		 */
		public function assertActiveSubscriber($userId)
		{
			$userActiveSubscriptions = $this->model->SubscriptionUser->read(
				[
					'user_id' => $userId,
					'status' => $this::STATUS_ACTIVE
				]);

			return count($userActiveSubscriptions) > 0;
		}

		/**
		 * This method is used to retrieve an array of users who have subscriptions in any state.
		 * @return array of subscribed users.
		 */
		public function getSubscribedUsers()
		{
			$query = <<<SQL
SELECT DISTINCT `user`.* 
FROM `user` INNER JOIN `subscription_user` ON `user`.`id` = `subscription_user`.`user_id`
SQL;
			if ($this->plugin->Db->nutsnbolts->select($query))
			{
				$records = $this->plugin->Db->nutsnbolts->result('assoc');
				return isset($records) ? $records : null;
			}

			return null;
		}

		/**
		 * This method retrieve all Subscriptions of a user in any state in any package.
		 * @param $userId The ID of the User.
		 * @return array of subscriptions
		 */
		public function getUserSubscriptions($userId)
		{
			$query = <<<SQL
SELECT `subscription_user`.*, `subscription`.`name`
FROM `subscription_user` INNER JOIN `subscription` ON `subscription_user`.`subscription_id` = `subscription`.`id`
WHERE `subscription_user`.`user_id` = {$userId}
SQL;
			if ($this->plugin->Db->nutsnbolts->select($query))
			{
				$records = $this->plugin->Db->nutsnbolts->result('assoc');
				return isset($records) ? $records : null;
			}

			return null;
		}

		/**
		 * To suspend a user subscription in a specific package into the Canceled Manually state
		 * @param $userSubscriptionId The ID of the subscription
		 * @return bool
		 */
		public function suspendManual($userSubscriptionId)
		{
			return $this->suspend($userSubscriptionId, false);
		}

		/**
		 * To suspend a user subscription in a specific package into the Canceled Automatically state 
		 * @param $userSubscriptionId
		 * @return bool
		 */
		public function suspendAuto($userSubscriptionId)
		{
			return $this->suspend($userSubscriptionId, true);
		}

		private function suspend($userSubscriptionId, $isAuto)
		{
			$userSubscription = $this->model->SubscriptionUser->read([
				'id' => $userSubscriptionId
			])[0];

			$subscriptionId = $userSubscription['subscription_id'];
			$subscription = $this->model->Subscription->read([
				'id' => $subscriptionId
			])[0];

			$isRecurring = $subscription['recurring'];
			if ($userSubscription['status'] == $this::STATUS_ACTIVE)
			{
				if ($isRecurring)
				{
					$arbId = $userSubscription['arb_id'];
					$this->plugin->Payment('AuthorizeNet')->deleteRecurringSubscription($arbId);
				}
				$this->model->SubscriptionUser->setStatus(
					$userSubscriptionId,
					$isAuto ? $this::STATUS_CANCELLED_AUTO : $this::STATUS_CANCELLED_MANUAL
				);
			}
			return true;
		}

		/**
		 * This method is used to handle the Transaction Notification coming from the Payment Gateway
		 * 	for recurring subscription billing
		 * @param $gatewayTransactionId
		 * @param $arbId
		 * @param $isApproved
		 * @param $jsonEncodedTransactionResponse
		 */
		public function addTransaction($gatewayTransactionId, $arbId, $isApproved, $jsonEncodedTransactionResponse)
		{
			$currentTimestamp = new \DateTime();

			//Grab the userSubscription to get the userSubscriptionId
			$userSubscription = $this->model->SubscriptionUser->read([
				'arb_id' => $arbId
			])[0];
			
			//Gran the subscription for the 'duration'
			$subscription = $this->model->Subscription->read([
				'id' => $userSubscription['subscription_id']
			])[0];
			
			//Insert the new Transaction record
			$transactionId = $this->model->SubscriptionTransaction->insertAssoc([
				'gateway_transaction_id' => $gatewayTransactionId,
				'timestamp' => $currentTimestamp->format($this::DATETIME_FORMAT)
			]);

			//If approved, we created a Transaction record, now we need to create a new Invoice as well.
			if ($isApproved)
			{
				$this->model->SubscriptionInvoice->insertAssoc([
					'subscription_user_id' => $userSubscription['id'],
					'subscription_transaction_id' => $transactionId,
					'timestamp' => $currentTimestamp->format($this::DATETIME_FORMAT),
					'meta' => $jsonEncodedTransactionResponse
				]);

				$currentExpiryDate = new \DateTime($userSubscription['expiry_timestamp']);
				
				//The sign of the $dateDiffDays are from the perspective of the expiry date, - is before, + is after
				$newExpiryDate = null;
				$dateDiff = date_diff($currentExpiryDate,$currentTimestamp);
				$dateDiffDays = $dateDiff->invert? -$dateDiff->days : $dateDiff->days;
				if($dateDiffDays < $this::RELAXATION_DAYS)
				{
					//All previous invoices including this one are done on time. Just Extend one month
					$newExpiryDate = clone $currentExpiryDate;
				}
				else
				{
					//The service has been interrupted some time;
					// one of the transactions were interrupted.
					//Probably the status of the subscription is "Cancelled".
					//New service span.
					$newExpiryDate = clone $currentTimestamp;
				}
				
				$newExpiryDate->add(new \DateInterval('P'.$subscription['duration'].'M'));
								
				$this->model->SubscriptionUser->update(
					[
						'expiry_timestamp' => $newExpiryDate->format($this::DATETIME_FORMAT),
						'status' => $this::STATUS_ACTIVE
					],
					[
						'id' => $userSubscription['id']
					]);
			}

			$userSubscriptionId = null;
		}
	}
}