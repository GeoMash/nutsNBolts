<?php
/**
 * Database Model for "subscription_user"
 * 
 * @package application-model
 * @since 23/06/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class SubscriptionUser extends Base	
	{
		public $name		= 'subscription_user';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ',
			'user_id' => 'int(10) NOT NULL ',
			'subscription_id' => 'int(10) NOT NULL ',
			'arb_id' => 'int(13) NOT NULL ',
			'timestamp' => 'timestamp NOT NULL ',
			'status' => 'tinyint(1) NOT NULL '
		);
	}
}
?>