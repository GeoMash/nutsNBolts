<?php
namespace application\nutsNBolts\controller\site
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use application\nutsNBolts\base\Controller;
	
	class Facebook extends Controller
	{
		public function index()
		{
			
		}
		
		public function save()
		{
			$this->request->get('email');
			//store in model.
		}
	}
}
?>
