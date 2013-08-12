<?php
/**
 * Database Model for "content_part"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 11/08/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class ContentPart extends Base	
	{
		public $name		= 'content_part';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'widget' => 'varchar(100) NOT NULL ' ,
			'label' => 'varchar(100) NOT NULL ' ,
			'ref' => 'varchar(100) NOT NULL ' ,
			'content_type_id' => 'int(10) unsigned NOT NULL ' ,
			'config' => 'text NOT NULL ' 
		);
	}
}
?>