<?php
/**
 * Database Model for "permission"
 * 
 * @package application-model
 * @since 06/06/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class Permission extends Base	
	{
		public $name		= 'permission';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'key' => 'varchar(100) NOT NULL ' ,
			'name' => 'varchar(100) NOT NULL ' ,
			'description' => 'text NOT NULL ' ,
			'category' => 'text NOT NULL '
		);
	}
}
?>