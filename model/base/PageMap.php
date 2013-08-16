<?php
/**
 * Database Model for "page_map"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 16/08/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class PageMap extends Base	
	{
		public $name		= 'page_map';
		public $primary		= array('page_id','url');
		public $primary_ai	= false;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'page_id' => 'int(10) NOT NULL ' ,
			'url' => 'varchar(255) NOT NULL ' 
		);
	}
}
?>