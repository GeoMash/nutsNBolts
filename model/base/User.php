<?php
/**
 * Database Model for "user"
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
			'image' => 'varchar(100)' ,
			'position' => 'varchar(100)' ,
			'company' => 'varchar(100)' ,
			'about' => 'text' ,
			'date_created' => 'datetime NOT NULL ' ,
			'date_lastlogin' => 'datetime NOT NULL ' ,
			'date_lastactive' => 'datetime NOT NULL ' ,
			'status' => 'tinyint(1) NOT NULL ' ,
			'phone' => 'varchar(50)' ,
			'dob' => 'varchar(200)' ,
			'income_range' => 'varchar(20)' ,
			'gender' => 'varchar(10)',
			'force_password_change' => 'TINYINT(1) DEFAULT 0',
			'confirmation_code' => 'varchar(200)',
			'address1' => 'varchar(255)',
			'address2' => 'varchar(255)',
			'country' => 'varchar(100)',
			'zip' => 'int(6)',
			'video' => 'varchar(100)',
			'ic' => 'bigint(20)',
			'ces_account' => 'varchar(100)'
		);
	}
}
?>