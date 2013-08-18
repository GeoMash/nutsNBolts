<?php
/**
 * Database Model for "site"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 18/08/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class Site extends Base	
	{
		public $name		= 'site';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'ref' => 'varchar(50) NOT NULL ' ,
			'domain' => 'varchar(100) NOT NULL ' ,
			'name' => 'varchar(200)' ,
			'description' => 'text' ,
			'status' => 'tinyint(1) NOT NULL ' 
		);
	}
}
?>