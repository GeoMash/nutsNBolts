<?php
namespace application\nutsNBolts\plugin\subscription {
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
				$arbStatus = $payment->createRecurringSubscription($userFirstName, $userLastName, $amount, $cardNo, $cardCode, $expDate, $transactionResponse);
				$timestamp = new \DateTime('now'); //Use this? or take from TransactionResponse? How precise we want it?

				//var_dump($transactionResponse);

				$arbId = $arbStatus->getSubscriptionId();
				$transactionId = $transactionResponse->transaction_id;

				//var_dump($arbId);

				//Activating the subscription for the user
				$subscriptionUserId = $this->model->SubscriptionUser->insertAssoc([
					'subscription_id' => $subscriptionId,
					'user_id' => $userId,
					'arb_id' => $arbId,
					'timestamp' => $timestamp->format('Y-m-d h:i:s'),
					'status' => $this::STATUS_ACTIVE
				]);

				$subscriptionTransactionId = $this->model->SubscriptionTransaction->insertAssoc([
					'gateway_transaction_id' => $transactionId,
					'timestamp' => $timestamp->format('Y-m-d h:i:s')
				]);

				$this->model->SubscriptionInvoice->insertAssoc([
					'subscription_user_id' => $subscriptionUserId,
					'subscription_transaction_id' => $subscriptionTransactionId,
					'timestamp' => $timestamp->format('Y-m-d h:i:s'),
					'meta' => json_encode($transactionResponse)
				]);
			} else {
				$transactionResponse = $payment->chargeCard($cardNo, $cardCode, $expDate, $amount);
				$transactionId = $transactionResponse->transaction_id;

				//var_dump($transactionResponse);
				//var_dump($transactionId);

				$timestamp = new \DateTime('now'); //Use this? or take from TransactionResponse? How precise we want it?

				//Activating the subscription for the user
				$subscriptionUserId = $this->model->SubscriptionUser->insertAssoc([
					'subscription_id' => $subscriptionId,
					'user_id' => $userId,
					'arb_id' => null,
					'timestamp' => $timestamp->format('Y-m-d h:i:s'),
					'status' => $this::STATUS_ACTIVE
				]);

				$subscriptionTransactionId = $this->model->SubscriptionTransaction->insertAssoc([
					'gateway_transaction_id' => $transactionId,
					'timestamp' => $timestamp->format('Y-m-d h:i:s')
				]);

				$this->model->SubscriptionInvoice->insertAssoc([
					'subscription_user_id' => $subscriptionUserId,
					'subscription_transaction_id' => $subscriptionTransactionId,
					'timestamp' => $timestamp->format('Y-m-d h:i:s'),
					'meta' => json_encode($transactionResponse)
				]);
			}
		}

		public function addInvoice($subscriptionUserId, $transactionId, $timestamp, $transactionResponseJson)
		{
			
		}

		public function assertUserHasActiveSubscription($userId)
		{
			$userActiveSubscriptions = $this->model->SubscriptionUser->read([
				'user_id' => $userId,
				'status' => $this::STATUS_ACTIVE
			]);

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
	}
}