<?php
/**
 * Database Model for "policy"
 * 
 * @package application-model
 * @since 18/06/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class Policy extends Base	
	{
		public $name		= 'policy';
		public $primary		= false;
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'password_force_random' => 'tinyint(1) NULL ',
			'password_length_minimum' => 'tinyint(2) NULL ',
			'password_length_maximum' => 'tinyint(2) NULL '
		);
	}
}
?>