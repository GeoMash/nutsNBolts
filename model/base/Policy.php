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
			'password_length_maximum' => 'tinyint(2) NULL ',
			'password_special_characters' => 'tinyint(2) NULL ',
			'password_numeric_digits' => 'tinyint(2) NULL ',
			'password_upper_lower_characters' => 'tinyint(2) NULL ',
			'password_expiry' => 'tinyint(2) NULL ',
			'password_past_passwords' => 'tinyint(2) NULL '
		);
	}
}
?>