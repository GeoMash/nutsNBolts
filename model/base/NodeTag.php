<?php
/**
 * Database Model for "node_tag"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 02/10/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class NodeTag extends Base	
	{
		public $name		= 'node_tag';
		public $primary		= array('node_id','tag');
		public $primary_ai	= false;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'node_id' => 'int(10) NOT NULL ' ,
			'tag' => 'varchar(30) NOT NULL ' 
		);
	}
}
?>