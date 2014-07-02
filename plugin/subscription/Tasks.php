<?php
namespace application\nutsNBolts\plugin\subscription
{
	use application\nutsNBolts\plugin\payment\Payment;
	use nutshell\core\plugin\PluginExtension;
	use nutshell\plugin\db\impl\SQLite;

	class Tasks extends PluginExtension
	{
		const RELAXATION_DAYS = 3; 
		
		public function init()
		{
			
		}
		
		public function validateSubscriptions()
		{
			//For those subscriptions beyond expiration date => Set status to Cancel_auto
			
			$statusCancelledAuto = Subscription::STATUS_CANCELLED_AUTO;
			$statusCancelledManual = Subscription::STATUS_CANCELLED_MANUAL;			
			$relaxationDays = $this::RELAXATION_DAYS;
			
			$query = <<<SQL
UPDATE `subscription_user`
SET `status` = {$statusCancelledManual}
WHERE CURRENT_TIMESTAMP() > TIMESTAMPADD(DAY,{$relaxationDays},`expiry_timestamp`)
AND `status` NOT IN ({$statusCancelledAuto},{$statusCancelledManual})
SQL;
			var_dump($query);
			$this->plugin->Db->nutsnbolts->update($query);
		}
		
		public function completeRBSubscriptions()
		{
			
		}
	}
}