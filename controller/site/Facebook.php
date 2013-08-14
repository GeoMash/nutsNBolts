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
			$params=array();
			 		// foreach($user as $key)
			 		// {
			 			// $params['fb_uid']=$this->request->get('fb_uid');
			 			// $params['first_name']=$this->request->get('firstName');
			 			// $params['last_name']=$this->request->get('lastName');
			 			// $params['email']=$this->request->get('email');
			 			// $params['gender']=$this->request->get('gender');
			 			
			 		$result=$this->model->Facebook->insertAssoc($this->request->getAll());
			 		var_dump($result);
			 //store in model.
		}
		
	}
}
?>
