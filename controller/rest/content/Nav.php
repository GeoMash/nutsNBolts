<?php
namespace application\nutsNBolts\controller\rest\content
{
	use application\plugin\rest\RestController;

	class Nav extends RestController
	{
		private $map=array
		(
			'{*}'		=>'get'
		);
		
		public function get($ref)
		{
			$this->setResponseCode(200);
			$this->respond
			(
				true,
				'OK',
				$this->plugin->Nav->getByRef($ref)
			);
		}
	}
}
?>