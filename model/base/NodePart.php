<?php
/**
 * Database Model for "node_part"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 02/08/2013 
 */
namespace application\nutsnbolts\model\base
{
	use application\nutsnbolts\model\common\Base;
	
	class NodePart extends Base	
	{
		public $name		= 'node_part';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'site_id' => 'int(10) NOT NULL ' ,
			'node_id' => 'int(10) NOT NULL ' ,
			'content_part_id' => 'int(10) NOT NULL ' ,
			'value' => 'text NOT NULL ' 
		);
	}
}
?>