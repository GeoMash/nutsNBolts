<?php
/**
 * Database Model for "node_read"
 * 
 * @package application-model
 * @since 14/06/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class NodeRead extends Base	
	{
		public $name		= 'node_read';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'content_type_id' => 'int(10) NOT NULL ' ,
			'node_id' => 'int(10) NOT NULL ' ,
			'user_id' => 'int(10) NOT NULL ' ,
			'timestamp' => 'timestamp',
		);
	}
}
?>