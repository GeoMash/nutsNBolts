<?php
/**
 * Database Model for "permission_node"
 * 
 * @package application-model
 * @since 04/07/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class PermissionNode extends Base	
	{
		public $name		= 'permission_node';
		public $primary		= null;
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'node_id' => 'int(10) NOT NULL ' ,
			'user_id' => 'int(10) NOT NULL ' ,
			'read' => 'tinyint(1) NOT NULL ' ,
			'update' => 'tinyint(1) NOT NULL ' ,
			'delete' => 'tinyint(1) NOT NULL ' 
		);
	}
}
?>