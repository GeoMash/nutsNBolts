<?php
namespace application\nutsnbolts\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	use application\plugin\FaceBookPlugin\FaceBookPlugin;
	
	class Sandbox extends Controller
	{
		public function index()
		{
			// $list=$this->application->nutsnbolts->getWidgetList();
			// var_dump($list);
			// $this->testWidgets();
			//print_r($this->plugin->FaceBookPlugin->getUserProfile());
			//$this->plugin->FaceBookPlugin->fbLogin();
			print_r($this->plugin->FaceBookPlugin->getUserProfile());
			//echo $this->plugin->FaceBookPlugin->fbLogout();
		}
		
		public function testWidgets()
		{
			$this->application->nutsnbolts->widget->Textbox();
		}
		
		public function testFBPlugin()
		{

		}
		
	}
}
?>