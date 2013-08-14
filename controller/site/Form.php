<?php
namespace application\nutsNBolts\controller\site
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use application\nutsNBolts\base\Controller;
	
	class Form extends Controller
	{
		public function index()
		{
			
		}
		
		public function post($formRef)
		{
			$form=$this->model->Form->read(array('ref'=>$formRef));
			$request=$this->request->getAll();
			if (isset($form[0]) && count($request))
			{
				$this->model->FormSubmission->insertAssoc
				(
					array
					(
						'form_id'	=>	$form[0]['id'],
						'data'		=>	json_encode($request)
					)
				);
			}
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
	}
}
?>
