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
			$files=$this->request->getFiles();
			
			var_dump($files);
			
			exit();
			// $this->plugin->Plupload->setCallback(array($this,'uploadComplete'));
			// $this->plugin->Plupload->upload();
			// $redirectTo=$_SERVER['HTTP_REFERER'];
			// $redirectTo=rtrim($redirectTo,'/').'/';
			// $this->redirect($redirectTo.'success');
		}
		
		public function uploadComplete($basename)
		{
			$completedDir	=$this->config->plugin->Plupload->completed_dir;
			$moveTo			=$completedDir.$this->collectionID._DS_;
			
			rename($completedDir.$basename,$moveTo.$basename);
			$this->makeThumbnail($moveTo,$basename);
		}
	}
}
?>