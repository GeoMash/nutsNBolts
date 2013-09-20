<?php
namespace application\nutsNBolts\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	use application\plugin\blog\Blog;
	
	class Sandbox extends Controller
	{
		public function index()
		{
			$this->plugin->Blog->getNextBlogArticle(159,2);
		}
	}
}
?>
