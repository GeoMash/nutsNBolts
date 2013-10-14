<?php
/**
 * Database Model for "sessions"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 11/10/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class Sessions extends Base	
	{
		public $name		= 'sessions';
		public $primary		= array('session_id');
		public $primary_ai	= false;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'session_id' => 'varchar(32) NOT NULL ' ,
			'session_ts' => 'timestamp NOT NULL ' ,
			'session_data' => 'blob NOT NULL ' 
		);
	}
}
?>