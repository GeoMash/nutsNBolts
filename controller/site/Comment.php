<?php
namespace application\nutsNBolts\controller\site
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use application\nutsNBolts\base\Controller;
	
	class Comment extends Controller
	{
		public function index()
		{
			
		}
		
		public function post()
		{
			$this->model->NodeComment->insertAssoc($this->request->getAll());
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
	}
}
?>
