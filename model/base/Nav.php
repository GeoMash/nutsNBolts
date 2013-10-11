<?php
/**
 * Database Model for "nav"
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
	
	class Nav extends Base	
	{
		public $name		= 'nav';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'site_id' => 'int(10) NOT NULL ' ,
			'name' => 'varchar(100) NOT NULL ' ,
			'descripton' => 'text NOT NULL ' ,
			'status' => 'tinyint(1) NOT NULL ' 
		);
	}
}
?>