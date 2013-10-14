<?php
/**
 * Database Model for "nav_part"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 11/10/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class NavPart extends Base	
	{
		public $name		= 'nav_part';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'page_id' => 'int(10) NOT NULL ' ,
			'nav_id' => 'int(10) NOT NULL ' ,
			'node_id' => 'int(10) NOT NULL ' ,
			'label' => 'varchar(100) NOT NULL ' ,
			'url' => 'varchar(200) NOT NULL ' 
		);
	}
}
?>