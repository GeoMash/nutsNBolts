<?php
/**
 * Database Model for "page"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
<<<<<<< HEAD
 * @since 21/10/2013 
=======
 * @since 23/10/2013 
>>>>>>> refs/heads/dev.ego.isika
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
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