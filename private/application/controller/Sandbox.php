<?php
namespace application\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	
	class Sandbox extends Controller
	{
		public function index()
		{
			print 'SANDBOX';
		}
	}
}
?>