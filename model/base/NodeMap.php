<?php
/**
 * Database Model for "node_map"
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