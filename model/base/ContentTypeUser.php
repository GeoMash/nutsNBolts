<?php
/**
 * Database Model for "content_type_user"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
<<<<<<< Updated upstream
 * @since 01/10/2013 
=======
 * @since 02/10/2013 
>>>>>>> Stashed changes
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class ContentTypeUser extends Base	
	{
		public $name		= 'content_type_user';
		public $primary		= array('content_type_id','user_id');
		public $primary_ai	= false;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'content_type_id' => 'int(10) NOT NULL ' ,
			'user_id' => 'int(10) NOT NULL ' 
		);
	}
}
?>