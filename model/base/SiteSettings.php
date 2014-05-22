<?php
/**
 * Database Model for "site"
 * 
 * @package application-model
 * @since 22/05/2014
 */
namespace application\nutsNBolts\model\base
{
	use application\nutsNBolts\model\common\Base;
	
	class SiteSettings extends Base	
	{
		public $name		='site_settings';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns=array
		(
			'label'	=>'varchar(200) NOT NULL ',
			'key'	=>'varchar(200) NOT NULL ',
			'value'	=>'text' 
		);
	}
}
?>