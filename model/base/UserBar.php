<?php
/**
 * Database Model for "user_bar"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 30/10/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class UserBar extends Base	
	{
		public $name		= 'user_bar';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(11) unsigned NOT NULL ' ,
			'user_id' => 'int(11)' ,
			'bar_id' => 'int(11)' ,
			'role_id' => 'int(11)' 
		);
	}
}
?>