<?php
/**
 * Database Model for "subscription_transaction"
 * 
 * @package application-model
 * @since 23/06/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class SubscriptionTransaction extends Base	
	{
		public $name		= 'subscription_transaction';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ',
			'gateway_transaction_id' => 'int(20) NOT NULL ',
			'timestamp' => 'timestamp NOT NULL '
		);
	}
}
?>