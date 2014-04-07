<?php
/**
 * Database Model for "bar quota"
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

	class BarQuota extends Base
	{
		public $name		= 'bar_quota';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;

		public $columns = array
		(
			'id' => 'int(11) unsigned NOT NULL ' ,
			'bar_id' => 'int(11)' ,
			'package_id' => 'int(11)',
			'date_started'=>'date'
		);
	}
}
?>