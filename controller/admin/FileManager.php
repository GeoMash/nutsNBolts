<?php
namespace application\nutsNBolts\controller\admin
{
	use application\nutsNBolts\base\AdminController;
	use application\nutsNBolts\plugin\auth\exception\AuthException;
	use application\nutsNBolts\plugin\plupload\ThumbnailMaker;
	use nutshell\helper\ObjectHelper;
	
	class FileManager extends AdminController
	{
		private $collectionID=null;
		
		public function index()
		{
			
		}
		
		public function upload($collectionID=null)
		{
			if(!$collectionID)
			{
				$collections=$this->model->CollectionUser->read
				(
					[
						'user_id'		=>$this->plugin->Session->userId
					]	
				);

				$collectionID=$collections[0]['collection_id'];
			}
			
			try
			{
				$this->plugin->Auth->can('admin.collection.create');
				$this->collectionID=$collectionID;
				$this->plugin->Plupload->setCallback(array($this,'uploadComplete'));
				$this->plugin->Plupload->upload();
			}
			catch(AuthException $exception)
			{
				$this->setContentView('admin/noPermission');
				$this->view->render();
			}
		}
		
		public function uploadComplete($basename)
		{
			$completedDir=$this->config->plugin->Plupload->completed_dir;
			
			
			//Move the file
			$moveTo=$completedDir.$this->collectionID._DS_;
			rename($completedDir.$basename,$moveTo.$basename);
			$this->makeThumbnail($moveTo,$basename);
		}
		
		public function makeThumbnail($dir,$fileName)
		{
			$thumbnailMaker=new ThumbnailMaker();
			$thumbnailMaker->processFile($dir.$fileName,$dir._DS_.'_thumbs');
		}
	}
}
?>