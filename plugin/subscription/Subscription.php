<?php
namespace application\nutsNBolts\plugin\subscription {
	use application\nutsNBolts\plugin\payment\Payment;
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use application\nutsNBolts\base\Plugin;
	use nutshell\core\exception\ApplicationException;

	class Subscription extends Plugin implements Singleton, Native
	{
		const STATUS_CANCELLED_MANUAL = -2;
		const STATUS_CANCELLED_AUTO = -1;
		const STATUS_PENDING = 0;
		const STATUS_ACTIVE = 1;

		const RELAXATION_DAYS = 3;

		public function init()
		{
		}

		public function subscribe($userId, $subscriptionId, $subscriptionRequest)
		{
			//var_dump($userId, $subscriptionId, $subscriptionRequest);

			//Receiving Credit Card information
			$cardNo = $subscriptionRequest['number'];
			$cardCode = $subscriptionRequest['ccv'];

			$cardExpiryMonth = str_pad($subscriptionRequest['expiry-month'], 2, '0', STR_PAD_LEFT);
			$cardExpiryYear = str_pad($subscriptionRequest['expiry-year'], 2, '0', STR_PAD_LEFT);
			$expDate = $cardExpiryMonth . $cardExpiryYear;

			//Receiving Payment Amount
			$subscription = $this->model->Subscription->read($subscriptionId)[0];
			$amount = $subscription['amount'];

			//Receiving the necessary user information
			$user = $this->model->User->read([
				'id' => $userId
			])[0];
			$userFirstName = $user['name_first'];
			$userLastName = $user['name_last'];

			//var_dump($userFirstName, $userLastName);

			//Checking for Subscription Package Activity
			if (!$subscription->status != STATUS_ACTIVE)
				throw new ApplicationException(0, "Subscription is inactive");

			//Starting the payment process
			$payment = $this->plugin->Payment("AuthorizeNet");

			//var_dump($userId, $subscriptionId, $cardNo, $cardCode, $cardExpiryMonth, $cardExpiryYear, $amount, $subscription);

			if ($subscription['recurring']) {
				//var_dump('Recurring');

				$transactionResponse = null;
				$arbId = null;
				$status = null;

				try {
					$arbStatus = $payment->createRecurringSubscription($userFirstName, $userLastName, $amount, $cardNo, $cardCode, $expDate, $transactionResponse);
					$arbId = $arbStatus->getSubscriptionId();
					$status = $this::STATUS_ACTIVE;
				} catch (\Exception $ex) {
					//If the first transaction is done, just continue and give the user one month of service
					$arbId = null;
					$status = $this::STATUS_PENDING;

					//else, fail
					if ($ex->getCode() != 0) {
						throw $ex;
					}
				}

				$duration = 1;
				$timestamp = new \DateTime('now'); //Use this? or take from TransactionResponse? How precise we want it?
				$timestamp_formatted = $timestamp->format('Y-m-d h:i:s');
				$expiry_timestamp_formatted = $timestamp->add(new \DateInterval("P" . $duration . "M"))->format('Y-m-d h:i:s');

				//var_dump($transactionResponse);

				$transactionId = $transactionResponse->transaction_id;

				//var_dump($arbId);

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
			} else {
				$transactionResponse = $payment->chargeCard($cardNo, $cardCode, $expDate, $amount);
				$transactionId = $transactionResponse->transaction_id;

				//var_dump($transactionResponse);
				//var_dump($transactionId);

				$timestamp = new \DateTime('now'); //Use this? or take from TransactionResponse? How precise we want it?
				$duration = $subscription['duration'];

				$timestamp_formatted = $timestamp->format('Y-m-d h:i:s');
				$expiry_timestamp_formatted = $timestamp->add(new \DateInterval("P" . $duration . "M"))->format('Y-m-d h:i:s');

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

		public function assertActiveSubscriber($userId)
		{
			$whereClause = "AND ( `status` = " . $this::STATUS_ACTIVE . " OR `status` = " . $this::STATUS_PENDING . " )";
			$userActiveSubscriptions = $this->model->SubscriptionUser->read(
				['user_id' => $userId],
				array(),
				$whereClause
			);

			return count($userActiveSubscriptions) > 0;
		}

		public function getSubscribedUsers()
		{
			$query = <<<SQL
SELECT `user`.* 
FROM `user` INNER JOIN `subscription_user` ON `user`.`id` = `subscription_user`.`user_id`
SQL;
			if ($this->plugin->Db->nutsnbolts->select($query)) {
				$records = $this->plugin->Db->nutsnbolts->result('assoc');
				return isset($records) ? $records : null;
			}

			return null;
		}

		public function getUserSubscriptions($userId)
		{
			$query = <<<SQL
SELECT `subscription_user`.*, `subscription`.`name`
FROM `subscription_user` INNER JOIN `subscription` ON `subscription_user`.`subscription_id` = `subscription`.`id`
WHERE `subscription_user`.`user_id` = {$userId}
SQL;
			if ($this->plugin->Db->nutsnbolts->select($query)) {
				$records = $this->plugin->Db->nutsnbolts->result('assoc');
				return isset($records) ? $records : null;
			}

			return null;
		}

		public function suspendManual($userSubscriptionId)
		{
			return $this->suspend($userSubscriptionId, false);
		}

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
			if ($userSubscription['status'] == $this::STATUS_ACTIVE) {

				if ($isRecurring) {
					$arbId = $userSubscription['arb_id'];
					$this->plugin->Payment('AuthorizeNet')->deleteRecurringSubscription($arbId);
					$this->model->SubscriptionUser->setStatus(
						$userSubscriptionId,
						$this::STATUS_PENDING
					);
					//The scheduled task will later mark it cancelled
				} else {
					$this->model->SubscriptionUser->setStatus(
						$userSubscriptionId,
						$isAuto ? $this::STATUS_CANCELLED_AUTO : $this::STATUS_CANCELLED_MANUAL
					);
				}
			}
			return true;
		}

		public function addTransaction($gatewayTransactionId, $arbId, $isApproved, $jsonEncodedTransactionResponse)
		{
			$currentTimestamp = new \DateTime();

			//Grab the userSubscription to get the userSubscriptionId
			$userSubscription = $this->model->SubscriptionUser->read([
				'arb_id' => $arbId
			]);

			//Insert the new transaction
			$transactionId = $this->model->SubscriptionTransaction->insertAssoc([
				'gateway_transaction_id' => $gatewayTransactionId,
				'timestamp' => $currentTimestamp
			]);

			//If a approved, we need to create a new Invoice as well.
			if ($isApproved) {
				$this->model->SubscriptionInvoice->insertAssoc([
					'subscription_user_id' => $userSubscription['id'],
					'subscription_transaction_id' => $transactionId,
					'timestamp' => $currentTimestamp,
					'meta' => $jsonEncodedTransactionResponse
				]);

				$currentExpiryDate = $userSubscription['timestamp'];

				$newExpiryDate = null;
				if ($currentTimestamp - $currentExpiryDate < $this::RELAXATION_DAYS) {
					//All previous invoices are done on time. Just Extend one month
					$newExpiryDate = clone $currentExpiryDate;
				} else {
					//The service has been interrupted some time;
					// one of the transactions were interrupted.
					//Probably the status of the subscription is "cancelled".
					//New service span.
					$newExpiryDate = clone $currentTimestamp;
				}
				$newExpiryDate->add('P1M');

				$this->model->SubscriptionUser->update(
					[
						'expiry_timestamp' => $newExpiryDate,
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