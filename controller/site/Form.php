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
				unset($request['MAX_FILE_SIZE']);
				$id=$this->model->FormSubmission->insertAssoc
				(
					array
					(
						'form_id'	=>	$form[0]['id'],
						'data'		=> 	json_encode($request)
					)
				);
				$redirectTo=$_SERVER['HTTP_REFERER'];  
				$redirectTo=rtrim($redirectTo,'/').'/';
				$files=$this->request->getFiles();
				if ($files['upload']['error']==4)
				{
					$this->redirect($redirectTo.'success');
					exit();
				}   
				else
				{
					$error=$this->request->getFileError('upload');
					if (!$error)
					{
						$moveTo=APP_HOME.'nutsNBolts'._DS_.$this->config->application->d
						if ($this->request->moveFile('upload',$moveTo))
						{
							$this->redirect($redirectTo.'success');
							exit();
						}
					}
				}
			}
			$this->redirect($redirectTo.'error');
			exit();
		}
	}
} 
?>
