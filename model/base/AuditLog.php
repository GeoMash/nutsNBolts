<?php
/**
 * Database Model for "audit_log"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 21/10/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class AuditLog extends Base	
	{
		public $name		= 'audit_log';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(11) unsigned NOT NULL ' ,
			'user_id' => 'int(11)' ,
			'date_created' => 'timestamp' 
		);
	}
}
?>