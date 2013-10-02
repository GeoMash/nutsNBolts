<?php
/**
 * Database Model for "form_submission"
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
	
	class FormSubmission extends Base	
	{
		public $name		= 'form_submission';
		public $primary		= array('id');
		public $primary_ai	= true;
		public $autoCreate	= false;
		
		public $columns = array
		(
			'id' => 'int(10) NOT NULL ' ,
			'form_id' => 'int(10) NOT NULL ' ,
			'data' => 'text NOT NULL ' ,
			'exported' => 'tinyint(1) NOT NULL ' 
		);
	}
}
?>