<?php

namespace application\nutsNBolts\plugin\payment\handler
{
	use nutshell\core\exception\ApplicationException;
	use nutshell\core\exception\PluginException;
	use nutshell\core\plugin\PluginExtension;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\AuthorizeNetAIM;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\shared\AuthorizeNet_Subscription;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\AuthorizeNetARB;

	class AuthorizeNet extends PluginExtension
	{
		const TRANS_TYPE_AUTH = 0;
		const TRANS_TYPE_CAPTURE = 1;
		const TRANS_TYPE_AUTHCAPTURE = 2;

		private $login_id;
		private $transaction_key;

		/**
		 *  Requires the AuthorizeNet SDK files. And Import Authroize.Net LoginID and TransactionID
		 */
		public function init()
		{
			require_once 'authorizeNet/shared/AuthorizeNetException.php';
			require_once 'authorizeNet/shared/AuthorizeNetRequest.php';
			require_once 'authorizeNet/shared/AuthorizeNetResponse.php';
			require_once 'authorizeNet/shared/AuthorizeNetTypes.php';
			require_once 'authorizeNet/shared/AuthorizeNetXMLResponse.php';

			require_once 'authorizeNet/AuthorizeNetAIM.php';
			require_once 'authorizeNet/AuthorizeNetARB.php';

			$this->login_id = $this->config->authorize_net->login_id;
			$this->transaction_key = $this->config->authorize_net->transaction_key;
		}

		/**
		 * The method is used to charge a Credit Card with One Time Payment transaction
		 * @param $cardNo , The Credit Card No.
		 * @param $cardCode , The Security Code of the Card
		 * @param $expDate , The Expiry Date of the card. Formatted as: 'mmyy' e.g. July 2010 = '0710'
		 * @param $amount , The amount of cash to debit
		 * @param $transactionType , Whether the transaction is Authorization only, Capture Only, Void, or Authorization and Capture
		 * @param $authCode , In case of Capture Only Transaction, this is the Authentication Code use for the Capture
		 * @return authorizeNet\AuthorizeNetAIM_Response
		 * @throws \nutshell\core\exception\ApplicationException
		 */
		public function chargeCard($cardNo, $cardCode, $expDate, $amount, $transactionType = AuthorizeNet::TRANS_TYPE_AUTHCAPTURE, $authCode = null)
		{
			$transaction = new AuthorizeNetAIM($this->login_id, $this->transaction_key);

			if ($cardCode)
			{
				$transaction->card_code = $cardCode;
			}

			$response = null;
			switch ($transactionType)
			{
				case self::TRANS_TYPE_AUTH:
					$response = $transaction->authorizeOnly($amount, $cardNo, $expDate);
					break;
				case self::TRANS_TYPE_CAPTURE:
					$response = $transaction->captureOnly($authCode, $amount, $cardNo, $expDate);
					break;
				case self::TRANS_TYPE_AUTHCAPTURE:
					$response = $transaction->authorizeAndCapture($amount, $cardNo, $expDate);
					break;
			}

			if (!$response->approved)
			{
				throw new PluginException(0, $response->response_reason_text);
			}

			return $response;
		}

		/**
		 * The method is used to Void a Transaction
		 * @param $transactionId , The ID of the transaction to void.
		 * @return authorizeNet\AuthorizeNetAIM_Response
		 * @throws \nutshell\core\exception\ApplicationException
		 */
		public function voidTransaction($transactionId)
		{
			$transaction = new AuthorizeNetAIM($this->login_id, $this->transaction_key);
			$transactionResponse = $transaction->void($transactionId);

			if (!$transactionResponse->approved)
			{
				throw new PluginException(0, $transactionResponse->response_reason_text);
			}
			else
			{
				return $transactionResponse;
			}
		}

		/**
		 * This method creates an Recurring Subscription in Authorize.Net by making one Payment Transaction,
		 *  then creating an ARB that start at the time of the next payment.
		 * @param $userFirstName , Required for Authorize.Net
		 * @param $userLastName , Required for Authorize.Net
		 * @param $amount , Amount to be charged
		 * @param $cardNo , Credit Card No.
		 * @param $cardCode , Credit Card Security No.
		 * @param $expDate , Credit Card Expiry Date formatted as : 'mmyy', e.g. '0919' is September 2019
		 * @param $arbProfileSettings , an array that contains 'totalOccurrences', 'billingInterval', and 'startDate'
		 * @return authorizeNet\AuthorizeNetARB_Response
		 * @throws \Exception
		 * @throws \nutshell\core\exception\ApplicationException
		 */
		public function createRecurringSubscription(
			$userFirstName, $userLastName,
			$amount, $cardNo, $cardCode, $expDate,
			$billingInterval, $totalOccurrences = null, $startDate = null)
		{
			if ($billingInterval == null)
			{
				//Deferred single payment
			}

			//Defaulting
			$startDate = !is_null($startDate) ? $startDate : new \DateTime();
			$totalOccurrences = !is_null($totalOccurrences) ? $totalOccurrences : '9999'; // 9999 means Unlimited
			$billingInterval = !is_null($billingInterval) ? $billingInterval : 1;

			// Set the subscription fields.
			$subscription = new AuthorizeNet_Subscription;
			$subscription->name = "EFTI_RECURRING";
			$subscription->intervalLength = $billingInterval;
			$subscription->intervalUnit = "months";
			$subscription->startDate = $startDate->format('Y-m-d');
			$subscription->amount = $amount;
			$subscription->totalOccurrences = $totalOccurrences;
			$subscription->creditCardCardNumber = $cardNo;
			$subscription->creditCardExpirationDate = $expDate;
			$subscription->creditCardCardCode = $cardCode;
			$subscription->billToFirstName = $userFirstName;
			$subscription->billToLastName = $userLastName;

			$subscription_Request = new AuthorizeNetARB($this->login_id, $this->transaction_key);
			$subscription_Response = $subscription_Request->createSubscription($subscription);

			if ($subscription_Response->isError())
			{
				throw new PluginException(2, $subscription_Response->getErrorMessage());
			}

			$subscription_id = $subscription_Response->getSubscriptionId();
			$subscription_status_request = new AuthorizeNetARB($this->login_id, $this->transaction_key);
			$subscription_status_response = $subscription_status_request->getSubscriptionStatus($subscription_id);

			if ($subscription_status_response->getSubscriptionStatus() != "active")
			{
				try
				{
					$this->deleteRecurringSubscription($subscription_id);
				}
				catch (\Exception $exp)
				{
				}
				throw new PluginException(3, $subscription_status_response->getMessageText());
			}

			return $subscription_Response;
		}

		/**
		 * This method cancels an Authorize.Net ARB subscription
		 * @param $subscriptionId , The ID of the ARB Subscription
		 * @return bool, always true
		 * @throws \nutshell\core\exception\ApplicationException
		 */
		public function deleteRecurringSubscription($arbId)
		{
			$cancellation = new AuthorizeNetARB($this->login_id, $this->transaction_key);
			$cancel_response = $cancellation->cancelSubscription($arbId);

			if ($cancel_response->isError())
			{
				throw new PluginException(0, $cancel_response->getErrorMessage());
			}

			$status_request = new AuthorizeNetARB($this->login_id, $this->transaction_key);
			$status_response = $status_request->getSubscriptionStatus($arbId);

			if ($status_response->getSubscriptionStatus() != "canceled")
			{
				throw new PluginException(1, $status_response->getMessageText());
			}

			return true;
		}

		/**
		 * This method handles Silent Post Requests coming from Authorize.Net
		 *    used primarily for tracking ARB billing
		 */
		public function handleSilentPost()
		{
			$fields = $this->request->getAll();
//			$fields = [
//				'x_response_code' => 1,
//				'x_trans_id' => '101010101',
//				'x_subscription_id' => '2176791'
//				];
			$isApproved = ($fields["x_response_code"] == 1);
			$transactionId = $fields["x_trans_id"];
			$arbId = $fields["x_subscription_id"];
			$jsonEncodedResponse = json_encode($fields);

			if ($arbId)
			{
				//Only passing the ARB Transactions as normal Transaction responses are handled synchronously
				// at the transaction issuing time.
				$this->plugin->Subscription->addTransaction($transactionId, $arbId, $isApproved, $jsonEncodedResponse);
			}
		}
	}
}
?>