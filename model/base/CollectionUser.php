<?php
/**
 * Database Model for "collection_user"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 30/10/2013 
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class CollectionUser extends Base	
	{
		public $name		= 'collection_user';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'collection_id' => 'int(10) NOT NULL ' ,
			'user_id' => 'int(10) NOT NULL ' 
		);
	}
}
?>