<?php
namespace application\nutsNBolts\controller\rest\content
{
	use application\nutsNBolts\base\RestController;

	class Node extends RestController
	{
		public function init()
		{
			$this->bindPaths
			(
				array
				(
					''		=>'getAll',
					'{int}'	=>'getById'
				)
			);
		}
		
		public function getAll()
		{
			
		}
		
		public function get()
		{
			
		}		
	}
}
?>