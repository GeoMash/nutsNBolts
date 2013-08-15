<?php
namespace application\nutsNBolts\plugin\fileSystem
{
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;
	use nutshell\core\exception\NutshellException;
	use application\nutsNBolts\helper\MimeHelper;
	use \DirectoryIterator;

	class FileSystem extends Plugin implements Singleton
	{
		// private $MVC=null;
		
		public function init()
		{
			// $this->MVC=$this->plugin->MVC;
		}
		
		public function getFileListFromCollection($collectionID)
		{
			$dir=PUBLIC_DIR.'_collections'._DS_.$collectionID;
			if (is_dir($dir))
			{
				$fileList=array();
				foreach (new DirectoryIterator($dir) as $iteration)
				{
					if ($iteration->isFile() && !$iteration->isDot())
					{
						$fileList[]=array
						(
							'systemPath'	=>$iteration->getPathname(),
							'publicPath'	=>'/_collections/'.$collectionID.'/'.$iteration->getFileName(),
							'thumbPath'		=>'/_collections/'.$collectionID.'/_thumbs/120x120/'.$iteration->getFileName(),
							'name'			=>$iteration->getFilename(),
							'extension'		=>strtolower($iteration->getExtension()),
							'mime'			=>MimeHelper::getMimeTypeFromExtension($iteration->getExtension())
						);
					}
				}
				return $fileList;
			}
			else
			{
				throw new NutshellException('Directory "'.$dir.'" doesn\'t exist.');
			}
		}
	}
}