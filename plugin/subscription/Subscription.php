<?php
namespace application\nutsNBolts\plugin\subscription
{
	use application\nutsNBolts\model\base\NodeRead;
	use application\nutsNBolts\plugin\payment\Payment;
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use application\nutsNBolts\base\Plugin;
	use nutshell\core\exception\ApplicationException;
	use nutshell\core\exception\PluginException;

	class Subscription extends Plugin implements Singleton, Native
	{
		const DATETIME_FORMAT = 'Y-m-d h:i:s';

		const STATUS_CANCELLED_MANUAL = -2;
		const STATUS_CANCELLED_AUTO = -1;
		const STATUS_ACTIVE = 1;
		const STATS_TRIAL = 2;

		const RELAXATION_DAYS = 3;

		public function init()
		{
		}

		/**
		 * This method subscribe a user to any type of subscription packages
		 * @param $userId , The user ID to be subscribed
		 * @param $subscriptionId , The Package ID
		 * @param $subscriptionRequest , The Credit Card Information
		 * @param $preset_timestamp , A preset for the creation time of the subscription
		 * @param $preset_expiry_timestamp , A preset for the expiry time of the subscription
		 * @throws \nutshell\core\exception\ApplicationException
		 */
		public
		function subscribe($userId, $subscriptionId, $subscriptionRequest, $preset_timestamp = null, $preset_expiry_timestamp = null)
		{
			//Receiving Credit Card information
			$cardNo = $subscriptionRequest['number'];
			$cardCode = $subscriptionRequest['ccv'];
var_dump('1');
			$cardExpiryMonth = str_pad($subscriptionRequest['expiry-month'], 2, '0', STR_PAD_LEFT);
			$cardExpiryYear = str_pad($subscriptionRequest['expiry-year'], 2, '0', STR_PAD_LEFT);
			$cardExpDate = $cardExpiryMonth . $cardExpiryYear;
var_dump('2');
			//Receiving Payment Amount
			$subscription = $this->model->Subscription->read($subscriptionId)[0];
			$amount = $subscription['amount'];
var_dump('3');
			//Receiving the necessary user information
			$user = $this->model->User->read([
				'id' => $userId
			])[0];
			$userFirstName = $user['name_first'];
			$userLastName = $user['name_last'];
var_dump('4');
			//Checking for Subscription Package Activity
			if ($subscription['status'] != self::STATUS_ACTIVE)
				throw new ApplicationException(0, "Subscription is inactive");
var_dump('5');
			$duration = $subscription['duration'];
			$trialPeriod = $subscription['trial_period'];
			$totalOccurrences = is_null($subscription['total_bills'])? null : intval($subscription['total_bills'],10);
			$billingInterval = $subscription['billing_interval'];
var_dump('6');
			$timestamp = $preset_timestamp ?: new \DateTime('now'); //Use this? or take from TransactionResponse? How precise we want it?
			$timestampFormatted = $timestamp->format(self::DATETIME_FORMAT);
var_dump('7');
			//Starting the payment process
			$payment = $this->plugin->Payment("AuthorizeNet");
var_dump('8');
			//DB registration fields : to be set for DB management section
			$arbId = null;
			$transactionId = null;
			$transactionResponse = null;
			$status = null;
var_dump('9');
			if ($trialPeriod == 0 && $totalOccurrences === 1)
			{
var_dump('10');
				//OTP with No Trial: One Payment, then subscription is open until the end of the package duration
				$transactionResponse = $payment->chargeCard($cardNo, $cardCode, $cardExpDate, $amount);
				$transactionId = $transactionResponse->transaction_id;
var_dump('11');
				if ($duration == null)
				{
					$expiryTimestamp = null;
var_dump('12');
				}
				else
				{
					$expiryTimestamp = $preset_expiry_timestamp ?: (clone $timestamp);
					$expiryTimestamp->add(new \DateInterval("P{$duration}M"));
var_dump('13');
				}
var_dump('14');
				$status = self::STATUS_ACTIVE;
			}
			else
			{
				//Installment or Recurring
var_dump('15');
				$arbProfileSettings = [
					'totalOccurrences' => null,
					'startDate' => null,
					'billingInterval' => is_null($billingInterval)? null : $billingInterval
				];
var_dump('16');
				if ($trialPeriod > 0)
				{
					$transactionResponse = null;
var_dump('17');
					//Trial Period exist: Installment or Recurring, either way an ARB is created
					$arbProfileSettings['totalOccurrences'] = $totalOccurrences;
					$arbProfileSettings['startDate'] = clone $timestamp;
					$arbProfileSettings['startDate']->add(new \DateInterval("P{$trialPeriod}D"));
var_dump('18');
					$expiryTimestamp = $preset_expiry_timestamp ?: (clone $timestamp);
					$expiryTimestamp->add(new \DateInterval("P{$trialPeriod}D"));
var_dump('19');
					$status = self::STATS_TRIAL;
				}
				elseif ($trialPeriod == 0 && $totalOccurrences !== 1)
				{
var_dump('20');
					//No Trial Period
					$transactionResponse = $payment->chargeCard($cardNo, $cardCode, $cardExpDate, $amount);
var_dump('21');
					if ($totalOccurrences === null)
					{
var_dump('22');
						//Infinite Recurring
						$arbProfileSettings['totalOccurrences'] = null;
					}
					elseif ($totalOccurrences > 1)
					{
var_dump('23');
						//Installment plan
						$arbProfileSettings['totalOccurrences'] = $totalOccurrences - 1;
					}
var_dump('24');
					$billingInterval = $subscription['billing_interval'];
var_dump('25');
					$arbProfileSettings['startDate'] = clone $timestamp;
					$arbProfileSettings['startDate']->add(new \DateInterval("P{$billingInterval}M"));
var_dump('26');
					$expiryTimestamp = $preset_expiry_timestamp ?: (clone $timestamp);
					$expiryTimestamp->add(new \DateInterval("P{$billingInterval}M"));
var_dump('27');
					$status = self::STATUS_ACTIVE;
				}
var_dump('27.1', $arbProfileSettings);
				//Create ARB
				$arbStatus = $payment->createRecurringSubscription(
					$userFirstName, $userLastName, 
					$amount, $cardNo, $cardCode, $cardExpDate, 
					$arbProfileSettings['billingInterval'], $arbProfileSettings['totalOccurrences'], $arbProfileSettings['startDate']);
				$arbId = $arbStatus->getSubscriptionId();
				$transactionId = is_null($transactionResponse) ? null : $transactionResponse->transaction_id;
var_dump('28',$arbId,$transactionId);
			}

//			if ($subscription_Response->isError())
//			{
//				try
//				{
//					$this->voidTransaction($authenticationTransactionId);
//				}
//				catch (\Exception $exp)
//				{
//				}
//
//				throw new ApplicationException(1, $subscription_Response->getErrorMessage());
//			}
			//Managing the DB side
			$expiryTimestampFormatted = is_null($expiryTimestamp) ? null : $expiryTimestamp->format(self::DATETIME_FORMAT);
var_dump('29');
			$subscriptionUserId = $this->model->SubscriptionUser->insertAssoc([
				'subscription_id' => $subscriptionId,
				'user_id' => $userId,
				'arb_id' => $arbId,
				'timestamp' => $timestampFormatted,
				'expiry_timestamp' => $expiryTimestampFormatted,
				'status' => $status
			]);
var_dump('30');
			if ($transactionId !== null)
			{
var_dump('31');
				$subscriptionTransactionId = $this->model->SubscriptionTransaction->insertAssoc([
					'gateway_transaction_id' => $transactionId,
					'timestamp' => $timestampFormatted
				]);
var_dump('32');
				$this->model->SubscriptionInvoice->insertAssoc([
					'subscription_user_id' => $subscriptionUserId,
					'subscription_transaction_id' => $subscriptionTransactionId,
					'timestamp' => $timestampFormatted,
					'meta' => json_encode($transactionResponse)
				]);
			}

			return $subscriptionUserId;
		}

		/**
		 * Checks whether a User has at least one active subscription
		 * @param $userId
		 * @return bool
		 */
		public
		function assertActiveSubscriber($userId)
		{
			$userActiveSubscriptions = $this->model->SubscriptionUser->read(
				[
					'user_id' => $userId,
					'status' => self::STATUS_ACTIVE
				]);

			$userTrialSubscriptions = $this->model->SubscriptionUser->read(
				[
					'user_id' => $userId,
					'status' => self::STATS_TRIAL
				]);

			return (count($userActiveSubscriptions) + count($userTrialSubscriptions)) > 0;
		}

		/**
		 * This method is used to retrieve an array of users who have subscriptions in any state.
		 * @return array of subscribed users.
		 */
		public
		function getSubscribedUsers()
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
		public
		function getUserSubscriptions($userId)
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
		public
		function suspendManual($userSubscriptionId)
		{
			return $this->suspend($userSubscriptionId, false);
		}

		/**
		 * To suspend a user subscription in a specific package into the Canceled Automatically state
		 * @param $userSubscriptionId
		 * @return bool
		 */
		public
		function suspendAuto($userSubscriptionId)
		{
			return $this->suspend($userSubscriptionId, true);
		}

		private
		function suspend($userSubscriptionId, $isAuto)
		{
			$userSubscription = $this->model->SubscriptionUser->read([
				'id' => $userSubscriptionId
			])[0];

			$userSubscriptionStatus = $userSubscription['status'];
			if (in_array($userSubscriptionStatus, [self::STATUS_CANCELLED_AUTO, self::STATUS_CANCELLED_MANUAL]))
			{
				//Idempotence: subscription is already cancelled
				return true;
			}

			$subscriptionId = $userSubscription['subscription_id'];
			$subscription = $this->model->Subscription->read([
				'id' => $subscriptionId
			])[0];

			if (isset($userSubscription['arb_id']))
			{
				$arbId = $userSubscription['arb_id'];
				$this->plugin->Payment('AuthorizeNet')->deleteRecurringSubscription($arbId);
			}

			$this->model->SubscriptionUser->setStatus(
				$userSubscriptionId,
				$isAuto ? $this::STATUS_CANCELLED_AUTO : $this::STATUS_CANCELLED_MANUAL
			);
			return true;
		}

		/**
		 * This method is used to handle the Transaction Notification coming from the Payment Gateway
		 *    for recurring subscription billing
		 * @param $gatewayTransactionId
		 * @param $arbId
		 * @param $isApproved
		 * @param $jsonEncodedTransactionResponse
		 */
		public
		function addTransaction($gatewayTransactionId, $arbId, $isApproved, $jsonEncodedTransactionResponse)
		{
			$currentTimestamp = new \DateTime();

			//Grab the userSubscription to get the userSubscriptionId
			$userSubscription = $this->model->SubscriptionUser->read([
				'arb_id' => $arbId
			])[0];

			//Grab the subscription for the 'duration'
			$subscription = $this->model->Subscription->read([
				'id' => $userSubscription['subscription_id']
			])[0];
			$duration = $subscription['duration'];
			$billingInterval = $subscription['billing_interval'];
			$totalBills = $subscription['total_bills'];

			//Insert the new Transaction record
			$transactionId = $this->model->SubscriptionTransaction->insertAssoc([
				'gateway_transaction_id' => $gatewayTransactionId,
				'timestamp' => $currentTimestamp->format(self::DATETIME_FORMAT)
			]);

			//If approved, we created a Transaction record, now we need to create a new Invoice as well.
			if ($isApproved)
			{
				$this->model->SubscriptionInvoice->insertAssoc([
					'subscription_user_id' => $userSubscription['id'],
					'subscription_transaction_id' => $transactionId,
					'timestamp' => $currentTimestamp->format(self::DATETIME_FORMAT),
					'meta' => $jsonEncodedTransactionResponse
				]);

				if ($userSubscription['expiry_timestamp'] == null)
				{
					return true;
				}
				else
				{
					$currentExpiryDate = new \DateTime($userSubscription['expiry_timestamp']);
				}

				/*
				 * Creating the Expiry Date base.
				 * NOTE: The sign of the $dateDiffDays are from the perspective of the expiry date, - is before, + is after
				*/
				$newExpiryDate = null;
				$dateDiff = date_diff($currentExpiryDate, $currentTimestamp);
				$dateDiffDays = $dateDiff->invert ? -$dateDiff->days : $dateDiff->days;
				if ($dateDiffDays < self::RELAXATION_DAYS)
				{
					//All previous invoices including this one are done on time. Just Extend on 'duration'
					$newExpiryDate = clone $currentExpiryDate;
				}
				else
				{
					//The service has been interrupted some time;
					// one of the transactions was unsuccessful.
					//Probably the status of the subscription is "Cancelled".
					//New service span.
					$newExpiryDate = clone $currentTimestamp;
				}

				$isRecurring = is_null($totalBills) OR $totalBills == 0;
				$isInstallment = !is_null($totalBills) AND is_numeric($totalBills);

				if ($isRecurring)
				{
					$newExpiryDate->add(new \DateInterval("P{$billingInterval}M"));
				}
				elseif ($isInstallment)
				{
					$query = <<<SQL
SELECT COUNT(*) AS invoice_count
FROM `subscription_invoice` INNER JOIN `subscription_user` ON `subscription_invoice`.subscription_user_id = `subscription_user`.id
WHERE `subscription_user`.id = {$userSubscription['id']}
SQL;
					if ($this->plugin->Db->nutsnbolts->select($query))
					{
						$records = $this->plugin->Db->nutsnbolts->result('assoc');
						$invoiceCount = $records[0]['invoice_count'];
						$isLastPayment = $invoiceCount == $totalBills;
					}
					else
					{
						throw new PluginException(0, "IDK ...");	
					}
					
					if ($isLastPayment)
					{
						if (is_null($duration))
						{
							$newExpiryDate = null;
						}
						else
						{
							$now = clone $currentTimestamp;
							$remainingServiceTime = $duration - (($totalBills - 1) * $billingInterval);
							$newExpiryDate = $now->add(new \DateInterval("P{$remainingServiceTime}M"));
						}
					}
					else
					{
						$newExpiryDate->add(new \DateInterval("P{$billingInterval}M"));
					}
				}
				else
				{
					return true;
				}

				$this->model->SubscriptionUser->update(
					[
						'expiry_timestamp' => is_null($newExpiryDate) ? null : $newExpiryDate->format(self::DATETIME_FORMAT),
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