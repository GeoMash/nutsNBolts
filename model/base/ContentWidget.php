<?php
/**
 * Database Model for "content_widget"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 10/08/2013 
 */
namespace application\nutsnbolts\model\base
{
	use application\nutsnbolts\model\common\Base;
	
	class ContentWidget extends Base	
	{
		public $name		= 'content_widget';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'site_id' => 'int(11) NOT NULL ' ,
			'name' => 'varchar(100) NOT NULL ' ,
			'description' => 'text NOT NULL ' ,
			'template' => 'text' ,
			'multivalue' => 'tinyint(1) NOT NULL ' 
		);
	}
}
?>