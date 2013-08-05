<?php
namespace application\nutsnbolts\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	
	class Sandbox extends Controller
	{
		public function index()
		{
			$list=$this->application->nutsnbolts->getWidgetList();
			var_dump($list);
			// $this->testWidgets();
		}
		
		public function testWidgets()
		{
			$this->application->nutsnbolts->widget->Textbox();
		}
		
		
	}
}
?>