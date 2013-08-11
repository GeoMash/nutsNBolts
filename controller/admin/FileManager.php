<?php
namespace application\nutsnbolts\controller\admin
{
	use application\nutsnbolts\base\AdminController;
	use application\nutsnbolts\plugin\plupload\ThumbnailMaker;
	use nutshell\helper\ObjectHelper;
	
	class FileManager extends AdminController
	{
		private $collectionID=null;
		
		public function index()
		{
			
		}
		
		public function upload($collectionID)
		{
			$this->collectionID=$collectionID;
			$this->plugin->Plupload->setCallback(array($this,'uploadComplete'));
			$this->plugin->Plupload->upload();
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