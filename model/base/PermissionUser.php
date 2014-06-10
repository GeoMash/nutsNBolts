<?php
/**
 * Database Model for "permission_user"
 * 
 * @package application-model
 * @since 06/06/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class PermissionUser extends Base	
	{
		public $name		= 'permission_user';
		public $primary		= array('permission_id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'permission_id' => 'int(10) NOT NULL ' ,
			'user_id' => 'int(10) NOT NULL ' 
		);
	}
}
?>