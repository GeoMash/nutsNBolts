<?php
/**
 * Database Model for "subscription_invoice"
 * 
 * @package application-model
 * @since 23/06/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class SubscriptionInvoice extends Base
	{
		public $name		= 'subscription_invoice';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ',
			'subscription_user_id' => 'int(10) NOT NULL ',
			'subscription_transaction_id' => 'int(10) NOT NULL ',
			'timestamp' => 'timestamp NOT NULL ',
			'meta' => 'text'
		);
	}
}
?>