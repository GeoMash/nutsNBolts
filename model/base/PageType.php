<?php
/**
 * Database Model for "page_type"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 15/08/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class PageType extends Base	
	{
		public $name		= 'page_type';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'site_id' => 'int(10) NOT NULL ' ,
			'ref' => 'varchar(100) NOT NULL ' ,
			'name' => 'varchar(100) NOT NULL ' ,
			'description' => 'text NOT NULL ' ,
			'status' => 'tinyint(1) NOT NULL ' 
		);
	}
}
?>