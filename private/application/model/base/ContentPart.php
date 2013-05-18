<?php
/**
 * Database Model for "content_part"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 18/05/2013 
 */
namespace application\model\base
{
	use application\model\common\Base;
	
	class ContentPart extends Base	
	{
		public $name		= 'content_part';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'content_type_id' => 'int(10) NOT NULL ' ,
			'content_widget_id' => 'int(10) NOT NULL ' ,
			'label' => 'varchar(100) NOT NULL ' 
		);
	}
}
?>