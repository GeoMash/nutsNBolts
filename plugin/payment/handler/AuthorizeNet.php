<?php

namespace application\nutsNBolts\plugin\payment\handler
{
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\shared\AuthorizeNetCustomer;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\shared\AuthorizeNetPaymentProfile;
	use nutshell\core\exception\ApplicationException;
	use nutshell\core\exception\PluginException;
	use nutshell\core\plugin\PluginExtension;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\AuthorizeNetAIM;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\shared\AuthorizeNet_Subscription;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\AuthorizeNetARB;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\AuthorizeNetCIM;

	class AuthorizeNet extends PluginExtension
	{
		const TRANS_TYPE_AUTH = 0;
		const TRANS_TYPE_CAPTURE = 1;
		const TRANS_TYPE_AUTHCAPTURE = 2;

		private $login_id;
		private $transaction_key;

		/**
		 *    Requires the AuthorizeNet SDK files. And Import Authroize.Net LoginID and TransactionID
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

			switch ($transactionType)
			{
				case $this::TRANS_TYPE_AUTH:
					$response = $transaction->authorizeOnly($amount, $cardNo, $expDate);
					break;
				case $this::TRANS_TYPE_CAPTURE:
					$response = $transaction->captureOnly($authCode, $amount, $cardNo, $expDate);
					break;
				case $this::TRANS_TYPE_AUTHCAPTURE:
					$response = $transaction->authorizeAndCapture($amount, $cardNo, $expDate);
					break;
			}

			if (!$response->approved)
			{
				throw new ApplicationException(0, $response->response_reason_text);
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
				throw new ApplicationException(0, $transactionResponse->response_reason_text);
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
		 * @param $transactionResponse , After call returns, this have the first payment transaction respose
		 * @param int $duration , The interval of the recurring
		 * @return authorizeNet\AuthorizeNetARB_Response
		 * @throws \Exception
		 * @throws \nutshell\core\exception\ApplicationException
		 */
		public function createRecurringSubscription($userFirstName, $userLastName, $amount, $cardNo, $cardCode, $expDate, &$transactionResponse, $duration = 1)
		{
//			$firstTransactionCounter = 0;
//			$firstTransactionSuccess = true;
//			do{
//				try{
			$authenticationResponse = $this->chargeCard($cardNo, $cardCode, $expDate, $amount, $this::TRANS_TYPE_AUTH);
//					$firstTransactionSuccess = true;
//				}
//				catch(\Exception $ex)
//				{
//					$firstTransactionSuccess = false;
//				}
//			}while(!$firstTransactionResponse->approved && $firstTransactionCounter++ < 5);
//			
//			if(!$firstTransactionSuccess)

			$authCode = $authenticationResponse->authorization_code;
			$authenticationTransactionId = $authenticationResponse->transaction_id;

			$myStartDate = (new \DateTime())->add(new \DateInterval("P" . $duration . "M"));

			// Set the subscription fields.
			$subscription = new AuthorizeNet_Subscription;
			$subscription->name = "EFTI_RECURRING";
			$subscription->intervalLength = $duration;
			$subscription->intervalUnit = "months";
			$subscription->startDate = $myStartDate->format('Y-m-d');
			$subscription->amount = $amount;
			$subscription->totalOccurrences = "9999"; //On-Going
			$subscription->creditCardCardNumber = $cardNo;
			$subscription->creditCardExpirationDate = $expDate;
			$subscription->creditCardCardCode = $cardCode;
			$subscription->billToFirstName = $userFirstName;
			$subscription->billToLastName = $userLastName;

			$subscription_Request = new AuthorizeNetARB($this->login_id, $this->transaction_key);
			$subscription_Response = $subscription_Request->createSubscription($subscription);

			if ($subscription_Response->isError())
			{
				try
				{
					$this->voidTransaction($authenticationTransactionId);
				}
				catch (\Exception $exp)
				{
				}

				throw new ApplicationException(1, $subscription_Response->getErrorMessage());
			}

			$subscription_id = $subscription_Response->getSubscriptionId();
			$subscription_status_request = new AuthorizeNetARB($this->login_id, $this->transaction_key);
			$subscription_status_response = $subscription_status_request->getSubscriptionStatus($subscription_id);

			if ($subscription_status_response->getSubscriptionStatus() != "active")
			{
				try
				{
					$this->voidTransaction($authenticationTransactionId);
				}
				catch (\Exception $exp)
				{
				}

				throw new ApplicationException(2, $subscription_status_response->getMessageText());
			}

			try
			{
				$captureResponse = $this->chargeCard($cardNo, $cardCode, $expDate, $amount, $this::TRANS_TYPE_CAPTURE, $authCode);
				$transactionResponse = $captureResponse;
			}
			catch (\Exception $ex)
			{
				$this->deleteRecurringSubscription($subscription_id);
				try
				{
					$this->voidTransaction($authenticationTransactionId);
				}
				catch (\Exception $exp)
				{
				}
				throw $ex;
			}

			return $subscription_Response;
		}

		/**
		 * This method cancels an Authorize.Net ARB subscription
		 * @param $subscriptionId , The ID of the ARB Subscription
		 * @return bool, always true
		 * @throws \nutshell\core\exception\ApplicationException
		 */
		public function deleteRecurringSubscription($subscriptionId)
		{
			$cancellation = new AuthorizeNetARB($this->login_id, $this->transaction_key);
			$cancel_response = $cancellation->cancelSubscription($subscriptionId);

			if ($cancel_response->isError())
			{
				throw new ApplicationException(0, $cancel_response->getErrorMessage());
			}

			$status_request = new AuthorizeNetARB($this->login_id, $this->transaction_key);
			$status_response = $status_request->getSubscriptionStatus($subscriptionId);

			if ($status_response->getSubscriptionStatus() != "canceled")
			{
				throw new ApplicationException(1, $status_response->getMessageText());
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
			
			$this->plugin->Subscription->addTransaction($transactionId, $arbId, $isApproved, $jsonEncodedResponse);
		}
	}
}
?>