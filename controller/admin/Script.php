<?php
namespace application\nutsnbolts\controller\admin
{
	use application\nutsnbolts\base\AdminController;
	use nutshell\helper\ObjectHelper;
	
	class Script extends AdminController
	{
		public function index()
		{
			die('script');
		}
		
		public function widget($type,$namespace)
		{
			$namespace=str_replace('.','\\',$namespace);
			
			$className	=ObjectHelper::getBaseClassName($namespace);
			$class		=$namespace.'\\'.ucwords($className);
			$widget		=new $class;
			$js			=null;
			if ($type=='config')
			{
				$js=$widget->getConfigJS();
			}
			if (!$js)
			{
				$js='';
			}
			$this->plugin	->Responder('js')
							->setData($js)
							->send();
		}
	}
}
?>