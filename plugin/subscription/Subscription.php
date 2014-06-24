<?php
namespace application\nutsNBolts\plugin\subscription
{
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use application\nutsNBolts\base\Plugin;

	class Subscription extends Plugin implements Singleton, Native
	{
		const STATUS_CANCELLED_MANUAL	=-2;
		const STATUS_CANCELLED_AUTO		=-1;
		const STATUS_PENDING			=0;
		const STATUS_ACTIVE				=1;
		
		public function init()
		{
			
		}
		
		public function subscribe($userId, $subscriptionId, $cardNo, $cardCode, $cardExpiryMonth, $cardExpiryYear)
		{
			$subscription = $this->model->Subscription->read($subscriptionId)[0];
			$amount = $subscription['amount'];
			$expDate = $cardExpiryMonth.$cardExpiryYear;
				
			$payment = $this->plugin->Payment("AuthorizeNet");
			
			var_dump($userId, $subscriptionId, $cardNo, $cardCode, $cardExpiryMonth, $cardExpiryYear, $amount, $subscription);
			
			if($subscription->recurring)
			{
				$transactionResponse = null;
				$arbStatus = $payment->createRecurringSubscription($amount, $cardNo, $cardCode, $expDate, $transactionResponse);
				$timestamp = new \DateTime('now'); //Use this? or take from TransactionResponse? How precise we want it?
				
				$arbId = $arbStatus->getSubscriptionId();
				$transactionId = $transactionResponse->transaction_id;
				
				//Activating the subscription for the user
				$subscriptionUserId = $this->model->SubscriptionUser->add([
					'subscription_id' => $subscriptionId,
					'arb_id' => $arbId,
					'timestamp' => $timestamp->format('Y-m-d h:i:s'),
					'status' => $this::STATUS_ACTIVE
				]);
				
				$this->model->SubscriptionInvoice->add([
					'subscription_user_id' => $subscriptionUserId,
					'user_id' => $userId,
					'transaction_id' => $transactionId,
					'timestamp' => $timestamp->format('Y-m-d h:i:s'),
					'meta' => json_encode($transactionResponse)
					]);
			}
			else{
				$transactionResponse = $payment->chargeCard($cardNo, $cardCode, $expDate, $amount);
				$transactionId = $transactionResponse->transaction_id;
				
				var_dump($transactionResponse);
				
				$timestamp = new \DateTime('now'); //Use this? or take from TransactionResponse? How precise we want it?
				
				//Activating the subscription for the user
				$subscriptionUserId = $this->model->SubscriptionUser->insertAssoc([
					'subscription_id' => $subscriptionId,
					'arb_id' => null,
					'timestamp' => $timestamp->format('Y-m-d h:i:s'),
					'status' => $this::STATUS_ACTIVE
				]);
				
				var_dump($subscriptionId);
				
				$this->model->SubscriptionInvoice->insertAssoc([
					'subscription_user_id' => $subscriptionUserId,
					'user_id' => $userId,
					'transaction_id' => $transactionId,
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