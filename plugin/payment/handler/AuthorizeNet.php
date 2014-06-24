<?php

namespace application\nutsNBolts\plugin\payment\handler
{
	use nutshell\core\exception\ApplicationException;
	use nutshell\core\plugin\PluginExtension;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\AuthorizeNetAIM;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\shared\AuthorizeNet_Subscription;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\AuthorizeNetARB;
	use application\nutsNBolts\plugin\payment\handler\authorizeNet\AuthorizeNetARB_Response;

	class AuthorizeNet extends PluginExtension
	{
		private $login_id;
		private $transaction_key;
		
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
		
		public function test()
		{
			echo 'hi';
		}
		
		public function chargeCard($cardNo, $cardCode, $expDate, $amount)
		{
			$transaction = new AuthorizeNetAIM($this->login_id,$this->transaction_key);
			
			if($cardCode) $transaction->card_code = $cardCode;
			
			$response =  $transaction->authorizeAndCapture($amount,$cardNo,$expDate);
			
			if(!$response->approved)
				throw new ApplicationException(0, $response->response_reason_text);
			
			return $response;
		}
		
		public function voidTransaction($transactionId)
		{
			$transaction = new AuthorizeNetAIM($this->login_id,$this->transaction_key);
			$transactionResponse = $transaction->void($transactionId);
			
			if(!$transactionResponse->approved)
			{
				throw new ApplicationException(0, $transactionResponse->response_reason_text);
			}
			else
			{
				return $transactionResponse;
			}
		}
		
		public function createRecurringSubscription($userFirstName, $userLastName, $amount, $cardNo, $cardCode, $expDate, &$transactionResponse)
		{
			$firstTransactionCounter = 0;
			$firstTransactionSuccess = true;
			do{
				try{
					$firstTransactionResponse = $this->chargeCard($cardNo, $cardCode,$expDate,$amount);
					$firstTransactionSuccess = true;
				}
				catch(\Exception $ex)
				{
					$firstTransactionSuccess = false;
				}
			}while(!$firstTransactionResponse->approved && $firstTransactionCounter++ < 5);
			
			if(!$firstTransactionSuccess)
			{
				throw new ApplicationException(0, $firstTransactionResponse->response_reason_text);
			}
			else
			{
				$transactionResponse = $firstTransactionResponse;
			}
			
			// Set the subscription fields.
			$subscription = new AuthorizeNet_Subscription;
			$subscription->name = "Short subscription";
			$subscription->intervalLength = "1";
			$subscription->intervalUnit = "months";
			$subscription->startDate = (new \DateTime())->add(new \DateInterval("P1M"))->format('Y-m-d');
			$subscription->amount = $amount;
        	$subscription->totalOccurrences = "9999"; //On-Going
			$subscription->creditCardCardNumber = $cardNo;
			$subscription->creditCardExpirationDate = $expDate;
			$subscription->creditCardCardCode = $cardCode;
			$subscription->billToFirstName = $userFirstName;
			$subscription->billToLastName = $userLastName;
			
			$arbRequest = new AuthorizeNetARB($this->login_id,$this->transaction_key);
        	$arbResponse = $arbRequest->createSubscription($subscription);
			
			if($arbResponse->isError())
				throw new ApplicationException(1, $arbResponse->getErrorMessage());
			
        	$subscription_id = $arbResponse->getSubscriptionId();
        	$status_request = new AuthorizeNetARB($this->login_id,$this->transaction_key);
  		    $status_response = $status_request->getSubscriptionStatus($subscription_id);
			
			if($status_response->getSubscriptionStatus() != "active")
				throw new ApplicationException(2, $status_response->getMessageText());
			
			return $arbResponse;
		}
		
		public function deleteRecurringSubscription($subscriptionId)
		{
        	$cancellation = new AuthorizeNetARB($this->login_id,$this->transaction_key);
        	$cancel_response = $cancellation->cancelSubscription($subscriptionId);
			
			if($cancel_response->isError())
				throw new ApplicationException(0, $cancel_response->getErrorMessage());
			
			$status_request = new AuthorizeNetARB($this->login_id,$this->transaction_key);
        	$status_response = $status_request->getSubscriptionStatus($subscriptionId);
			
			if($status_response->getSubscriptionStatus() != "canceled")
				throw new ApplicationException(1, $status_response->getMessageText());
			
			return true;
		}
	}
}
?>