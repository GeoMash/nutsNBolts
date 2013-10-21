<?php
/**
 * Database Model for "open_bottle"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 21/10/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class OpenBottle extends Base	
	{
		public $name		= 'open_bottle';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(11) unsigned NOT NULL ' ,
			'user_id' => 'int(11)' ,
			'bottle_by_bar_id' => 'int(11)' ,
			'bar_id' => 'int(11)' ,
			'date_opened' => 'timestamp' ,
			'bottle_price' => 'double(15,2)' 
		);
	}
}
?>