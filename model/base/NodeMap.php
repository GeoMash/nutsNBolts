<?php
/**
 * Database Model for "node_map"
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
	
	class NodeMap extends Base	
	{
		public $name		= 'node_map';
		public $primary		= array('node_id','url');
		public $primary_ai	= false;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'node_id' => 'int(10) NOT NULL ' ,
			'url' => 'varchar(255) NOT NULL ' 
		);
	}
}
?>