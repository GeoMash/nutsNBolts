<?php
namespace application\nutsNBolts\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	use application\nutsNBolts\Encoding;

	class Sandbox extends Controller
	{
		public function index()
		{
			$nodes=$this->model->Node->getWithParts(array('content_part_id'=>324));
			for ($i=0,$j=count($nodes); $i<$j; $i++)
			{
				var_dump($nodes[$i]);
				break;
			}
		}
	}
}
?>