<?php
namespace application\nutsNBolts\base
{
	class ControllerHook
	{
		private $controller	=null;
		private $view		=null;

		public function __construct($parentController,$view)
		{
			$this->controller	=$parentController;
			$this->view			=$view;
		}
	}
}
?>