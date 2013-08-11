<?php
/**
 * Database Model for "page"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 11/08/2013 
 */
namespace application\nutsnbolts\model\base
{
	use application\nutsnbolts\model\common\Base;
	
	class Page extends Base	
	{
		public $name		= 'page';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) unsigned NOT NULL ' ,
			'site_id' => 'int(10) NOT NULL ' ,
			'page_type_id' => 'int(11) NOT NULL ' ,
			'title' => 'varchar(100) NOT NULL ' ,
			'description' => 'text NOT NULL ' ,
			'status' => 'tinyint(1) NOT NULL ' 
		);
	}
}
?>