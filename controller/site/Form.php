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
				$id=$this->model->FormSubmission->insertAssoc
				(
					array
					(
						'form_id'	=>	$form[0]['id'],
						'data'		=>	json_encode($request)
					)
				);
				$redirectTo	=$_SERVER['HTTP_REFERER'];
				$redirectTo	=rtrim($redirectTo,'/').'/';
				$error		=$this->request->getFileError('upload');
				if (!$error)
				{
					$moveTo=APP_HOME.'nutsNBolts'._DS_.$this->config->application->dataDir.'formUploads'._DS_.$id._DS_;
					if ($this->request->moveFile('upload',$moveTo))
					{
						$this->redirect($redirectTo.'success');
						exit();
					}
				}
			}
			$this->redirect($redirectTo.'error');
			exit();
		}
	}
}
?>