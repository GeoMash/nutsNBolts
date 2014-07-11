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
WHERE `expiry_timestamp` IS NOT NULL AND CURRENT_TIMESTAMP() > TIMESTAMPADD(DAY,{$relaxationDays},`expiry_timestamp`)
AND `status` NOT IN ({$statusCancelledAuto},{$statusCancelledManual})
SQL;
			$this->plugin->Db->nutsnbolts->update($query);
		}
	}
}