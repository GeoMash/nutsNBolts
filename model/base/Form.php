<?php
/**
 * Database Model for "form"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 10/10/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class Form extends Base	
	{
		public $name		= 'form';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'site_id' => 'int(10) NOT NULL ' ,
			'ref' => 'varchar(100) NOT NULL ' ,
			'name' => 'varchar(100) NOT NULL ' ,
			'description' => 'varchar(255) NOT NULL ' ,
			'status' => 'tinyint(1) NOT NULL ' ,
			'message_success' => 'text' 
		);
	}
}
?>