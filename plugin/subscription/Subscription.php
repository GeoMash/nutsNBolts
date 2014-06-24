<?php
namespace application\nutsNBolts\plugin\subscription
{
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use application\nutsNBolts\base\Plugin;
	use nutshell\core\exception\ApplicationException;

	class Subscription extends Plugin implements Singleton, Native
	{
		const STATUS_CANCELLED_MANUAL	=-2;
		const STATUS_CANCELLED_AUTO		=-1;
		const STATUS_PENDING			=0;
		const STATUS_ACTIVE				=1;
		
		public function init()
		{
			
		}
		
		public function subscribe($userId, $subscriptionId, $subscriptionRequest)
		{
			//var_dump($userId, $subscriptionId, $subscriptionRequest);
			
			$cardNo = $subscriptionRequest['number'];
			$cardCode = $subscriptionRequest['ccv'];
			
			$cardExpiryMonth = str_pad($subscriptionRequest['expiry-month'], 2, '0', STR_PAD_LEFT);
			$cardExpiryYear = str_pad($subscriptionRequest['expiry-year'], 2, '0', STR_PAD_LEFT);
			$expDate = $cardExpiryMonth.$cardExpiryYear;
			
			$subscription = $this->model->Subscription->read($subscriptionId)[0];			
			$amount = $subscription['amount'];
				
			if(!$subscription->status != STATUS_ACTIVE)
				throw new ApplicationException(0,"Subscription is inactive");
			
			$payment = $this->plugin->Payment("AuthorizeNet");
			
			//var_dump($userId, $subscriptionId, $cardNo, $cardCode, $cardExpiryMonth, $cardExpiryYear, $amount, $subscription);
			
			if($subscription['recurring'])
			{
				//var_dump('Recurring');
				
				$transactionResponse = null;
				$arbStatus = $payment->createRecurringSubscription('Hasan', 'Baidoun', $amount, $cardNo, $cardCode, $expDate, $transactionResponse);
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
			}
			else{
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
	}
}