<?php
/**
 * Database Model for "permission_role"
 * 
 * @package application-model
 * @since 06/06/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class PermissionRole extends Base	
	{
		public $name		= 'permission_role';
		public $primary		= array('permission_id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'permission_id' => 'int(10) NOT NULL ' ,
			'role_id' => 'int(10) NOT NULL ' 
		);
	}
}
?>