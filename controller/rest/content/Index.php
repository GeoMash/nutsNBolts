<?php
namespace application\nutsNBolts\controller\rest\content
{
	use application\nutsNBolts\base\RestController;

	class Index extends RestController
	{
		public function init()
		{
			$this->bindPaths
			(
				array
				(
					'node'=>'application\nutsNBolts\controller\rest\content\Node',
					'type'=>'application\nutsNBolts\controller\rest\content\Type'
				)
			);
		}
	}
}
?>