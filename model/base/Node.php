<?php
/**
 * Database Model for "node"
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
	
	class Node extends Base	
	{
		public $name		= 'node';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'site_id' => 'int(10) NOT NULL ' ,
			'content_type_id' => 'int(10) NOT NULL ' ,
			'workflow_step_id' => 'int(10) NOT NULL ' ,
			'title' => 'varchar(100) NOT NULL ' ,
			'date_created' => 'timestamp NOT NULL ' ,
			'date_updated' => 'timestamp NOT NULL ' ,
			'date_published' => 'timestamp' ,
			'original_user_id' => 'int(10)' ,
			'last_user_id' => 'int(10) NOT NULL ' ,
			'order' => 'int(5) NOT NULL ' ,
			'status' => 'tinyint(1) NOT NULL '
		);
	}
}
?>