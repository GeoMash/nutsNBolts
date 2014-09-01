<?php

namespace application\nutsNBolts\plugin\payment\handler
{
	use nutshell\core\exception\ApplicationException;
	use nutshell\core\exception\PluginException;
	use nutshell\core\plugin\PluginExtension;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\AuthorizeNetAIM;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\shared\AuthorizeNet_Subscription;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\AuthorizeNetARB;

	class TestTransactionResponse{
		public $transaction_id;
	}
	
	class TestARBResponse{
		public function getSubscriptionId()
		{
			return '1010101010';
		}
	}
	
	class Test extends PluginExtension
	{
		const TRANS_TYPE_AUTH = 0;
		const TRANS_TYPE_CAPTURE = 1;
		const TRANS_TYPE_AUTHCAPTURE = 2;

		/**
		 *  Requires the AuthorizeNet SDK files. And Import Authroize.Net LoginID and TransactionID
		 */
		public function init()
		{
			
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
			$response =  new TestTransactionResponse();
			$response->transaction_id = '101010101';
			
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
			return true;
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
			$arbProfileSettings = null)
		{
			$response = new TestARBResponse();
			return $response;
		}

		/**
		 * This method cancels an Authorize.Net ARB subscription
		 * @param $subscriptionId , The ID of the ARB Subscription
		 * @return bool, always true
		 * @throws \nutshell\core\exception\ApplicationException
		 */
		public function deleteRecurringSubscription($arbId)
		{
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