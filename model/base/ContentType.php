<?php
/**
 * Database Model for "content_type"
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
	
	class ContentType extends Base	
	{
		public $name		= 'content_type';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'site_id' => 'int(10) NOT NULL ' ,
			'workflow_id' => 'int(10) NOT NULL ' ,
			'ref' => 'varchar(100) NOT NULL ' ,
			'name' => 'varchar(100) NOT NULL ' ,
			'description' => 'text NOT NULL ' ,
			'icon' => 'varchar(50) NOT NULL ' ,
			'status' => 'tinyint(1) NOT NULL ' 
		);
	}
}
?>