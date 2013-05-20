<?php
/**
 * Database Model for "user"
 * 
 * This file has been automatically generated by the Nutshell
 * Model Generator Plugin.
 * 
 * @package application-model
 * @since 20/05/2013 
 */
namespace application\model\base
{
	use application\model\common\Base;
	
	class User extends Base	
	{
		public $name		= 'user';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'email' => 'varchar(150) NOT NULL ' ,
			'password' => 'varchar(100) NOT NULL ' ,
			'salt' => 'varchar(100) NOT NULL ' ,
			'name_first' => 'varchar(100) NOT NULL ' ,
			'name_last' => 'varchar(100) NOT NULL ' ,
			'gender' => 'tinyint(1) NOT NULL ' ,
			'date_created' => 'datetime NOT NULL ' ,
			'date_lastlogin' => 'datetime NOT NULL ' ,
			'date_lastactive' => 'datetime NOT NULL ' ,
			'status' => 'tinyint(1) NOT NULL ' 
		);
	}
}
?>