<?php
namespace application\nutsNBolts\controller\rest
{
	use application\nutsNBolts\base\Controller;

	class Index extends Controller
	{
		public function index()
		{
			$this->plugin->Rest('nutsNBolts');
		}
	}
}
?>