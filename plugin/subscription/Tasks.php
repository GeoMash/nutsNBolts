<?php
namespace application\nutsNBolts\plugin\subscription {
	use application\nutsNBolts\plugin\payment\Payment;
	use nutshell\core\plugin\PluginExtension;
	use nutshell\plugin\db\impl\SQLite;

	class Tasks extends PluginExtension
	{
		private $model = null;
	
		public function init()
		{
			$this->model = $this->plugin->Subscription->model;
		}

		public function validateSubscriptions()
		{
			//For those subscriptions beyond expiration date => Set status to Cancel_auto

			$statusCancelledAuto = Subscription::STATUS_CANCELLED_AUTO;
			$statusCancelledManual = Subscription::STATUS_CANCELLED_MANUAL;
			$relaxationDays = Subscription::RELAXATION_DAYS;

			$query = <<<SQL
UPDATE `subscription_user`
SET `status` = {$statusCancelledManual}
WHERE CURRENT_TIMESTAMP() > TIMESTAMPADD(DAY,{$relaxationDays},`expiry_timestamp`)
AND `status` NOT IN ({$statusCancelledAuto},{$statusCancelledManual})
SQL;
			var_dump($query);
			$this->plugin->Db->nutsnbolts->update($query);
		}

		/**
		 *
		 */
		public function completeRBSubscriptions()
		{
			$uncompletedRecurringSubscriptions = $this->model->SubscriptionUser->read([
				"status" => Subscription::STATUS_PENDING
			]);
			
			foreach ($uncompletedRecurringSubscriptions as $userSubscription) {
				$subscription = $this->model->Subscription->read([
						'id' => $userSubscription['subscription_id']
					])[0];
				
				$expiryTimestamp = new \DateTime($userSubscription['expiry_timestamp']);
				
				if ($subscription['recurring'] == true && $expiryTimestamp > new \DateTime()) {
					//Start the ARB if the expiry date is still in range.
					$user = $this->model->User->read([
						'id' => $userSubscription['user_id']
					])[0];
					
					try {
						$cardInfo = $this->plugin->Payment('AuthorizeNet')->loadCardInfo($user['id']);
						
						$arbResponse = $this->plugin
							->Payment('AuthorizeNet')
							->createARBsubscription(
								$subscription['amount'],
								$cardInfo['cardNo'], $cardInfo['cardCode'], $cardInfo['expDate'],
								$user['name_first'], $user['name_last'],
								$expiryTimestamp);
						
						$arbId = $arbResponse->getSubscriptionId();
						
						$this->model->SubscriptionUser->update([
								'arb_id' => $arbId,
								'status' => Subscription::STATUS_ACTIVE
							],
							[
								'id' => $userSubscription['id']
							]);
					} catch (\Exception $ex) {
						//Do nothing, just give it more chance!
					}
				}
				else
				{
					//If we're beyond the expiry date, this means the user has been serviced
					// for the whole first month of the subscription, and the Recurring subscription
					// never been able to complete despite the scheduled task attempts.
				}
			}
		}
	}
}