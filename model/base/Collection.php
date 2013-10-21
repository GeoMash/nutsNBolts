<?php
/**
 * Database Model for "collection"
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
	
	class Collection extends Base	
	{
		public $name		= 'collection';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'name' => 'varchar(100) NOT NULL ' ,
			'description' => 'varchar(255) NOT NULL ' ,
			'status' => 'tinyint(1) NOT NULL ' 
		);
	}
}
?>