<?php
/**
 * Database Model for "content_type_role"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 14/08/2013 
 */
namespace application\nutsnbolts\model\base
{
	use application\nutsnbolts\model\common\Base;
	
	class ContentTypeRole extends Base	
	{
		public $name		= 'content_type_role';
		public $primary		= array('content_type_id','role_id');
		public $primary_ai	= false;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'content_type_id' => 'int(10) NOT NULL ' ,
			'role_id' => 'int(10) NOT NULL ' 
		);
	}
}
?>