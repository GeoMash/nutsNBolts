<?php
/**
 * Database Model for "subscription"
 * 
 * @package application-model
 * @since 23/06/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class Subscription extends Base	
	{
		public $name		= 'subscription';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ',
			'name' => 'varchar(100) NOT NULL ',
			'description' => 'text NOT NULL ',
			'duration' => 'tinyint(3) NOT NULL ',
			'amount' => 'int(10) NOT NULL ',
			'currency' => 'varchar(3) NOT NULL ',
			'status' => 'tinyint(1) NOT NULL '
		);
	}
}
?>