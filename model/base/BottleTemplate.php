<?php
/**
 * Database Model for "bottle_template"
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
	
	class BottleTemplate extends Base	
	{
		public $name		= 'bottle_template';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(11) unsigned NOT NULL ' ,
			'name' => 'varchar(50)' ,
			'volume' => 'varchar(10)' ,
			'bottle_type' => 'varchar(50)' ,
			'image' => 'varchar(100)' 
		);
	}
}
?>