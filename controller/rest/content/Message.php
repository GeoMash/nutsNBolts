<?php
namespace application\nutsNBolts\controller\rest\content
{
	use application\plugin\rest\RestController;

	class Message extends RestController
	{
		private $map=array
		(
			''		=>'getAll',
			'{int}'	=>'getById'
		);
		
		public function getAll()
		{
			
		}
		
		public function get()
		{
			
		}		
	}
}
?>