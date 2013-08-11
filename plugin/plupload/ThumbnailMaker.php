<?php
namespace application\nutsnbolts\plugin\plupload
{
	use nutshell\Nutshell;
	
	class ThumbnailMaker
	{
		private $filename			= '';
		// private $outputDirectory	= '';
		private $outFilePath		= null;
		private $thumbnails			= null;
		private $orientation		= '';
		
		public function getOrientation()
		{
			return $this->orientation;
		}
		
		public function calculateOrientation($file)
		{
			$image = new thirdparty\SimpleImage();
			$image->load($file);
			
			if($image->getHeight() > $image->getWidth())
			{
				$this->orientation = "portrait";
			}
			else
			{
				$this->orientation = "landscape";
			}
		}
		
		public function __construct()
		{
			// require_once(__DIR__._DS_.'thirdparty'._DS_.'SimpleImage.php');
			$config = Nutshell::getInstance()->config;
			// $this->outputDirectory	= $config->plugin->Plupload->thumbnail_dir;
			$this->thumbnails		= $config->plugin->Plupload->thumbnails;
			
			// If 'thumbnails' is not an array of configurations, just use the thumbnail_whatever properties
			if(!is_array($this->thumbnails))
			{
				$thumbnail = new \stdClass();
				$thumbnail->width		= $config->plugin->Plupload->thumbnail_width;
				$thumbnail->height		= $config->plugin->Plupload->thumbnail_height;
				$thumbnail->constraint	= $config->plugin->Plupload->thumbnail_constraint;
				$this->thumbnails		= array($thumbnail);
			}
		}
		
		public function processFile($file, $outFilePath=null)
		{
			$this->filename	= $file;
			$this->outFilePath	= $outFilePath;
			$this->calculateOrientation($file);
			
			// Output the image into each of the thumbnail sizes
			foreach($this->thumbnails as $thumbnail)
			{
				$filepath = $this->getFilePath($thumbnail);
				if (!file_exists($filepath)) @mkdir($filepath, 0755, true);
				$image = new thirdparty\SimpleImage();
				$image->load($file);
				switch($thumbnail->constraint)
				{
					case 'scale':
						$this->scale($image, $thumbnail);
						break;
					case 'stretch':
						$this->stretch($image, $thumbnail);
						break;
					case 'crop-best-orientation':
						$this->cropBestOrientation($image, $thumbnail);
						break;
					case 'crop':
						$this->crop($image, $thumbnail);
						break;
					case 'stretch-best-orientation':
						$this->stretchBestOrientation($image, $thumbnail);
						break;
				}
			}
		}
		
		private function stretchBestOrientation($image, $config)
		{
			// swap the thumbnail width and height if they don't match the image's orientation
			if($image->getWidth() > $image->getHeight()) // image is landscape
			{
				if($config->height > $config->width) // config is portrait
				{
					// switch it
					$temp			= $config->width;
					$config->width	= $config->height;
					$config->height	= $temp;
				}
			}
			else // image is portrait
			{
				if($config->width > $config->height) // config is landscape
				{
					// switch it
					$temp			= $config->width;
					$config->width	= $config->height;
					$config->height	= $temp;
				}
			}
			$image->resize($config->width, $config->height);
			$newFile = $this->getFilename($config);
			$image->save($newFile);
		}
		
		private function cropBestOrientation($image, $config)
		{
			// swap the thumbnail width and height if they don't match the image's orientation
			if($image->getWidth() > $image->getHeight()) // image is landscape
			{
				if($config->height > $config->width) // config is portrait
				{
					// switch it
					$temp			= $config->width;
					$config->width	= $config->height;
					$config->height	= $temp;
				}
				$image->cropToHeight($config->width, $config->height);
			}
			else // image is portrait
			{
				if($config->width > $config->height) // config is landscape
				{
					// switch it
					$temp			= $config->width;
					$config->width	= $config->height;
					$config->height	= $temp;
				}
				$image->cropToWidth($config->width, $config->height);
			}
			$newFile = $this->getFilename($config);
			$image->save($newFile);
		}
		
		private function crop($image, $config)
		{
			// swap the thumbnail width and height if they don't match the image's orientation
			if($image->getWidth() > $image->getHeight()) // image is landscape
			{
				$image->cropToHeight($config->width, $config->height);
			}
			else // image is portrait
			{
				$image->cropToWidth($config->width, $config->height);
			}
			$newFile = $this->getFilename($config);
			$image->save($newFile);
		}
		
		private function stretch($image, $config)
		{
			$image->resize($config->width, $config->height);
			$newFile = $this->getFilename($config);
			$image->save($newFile);
		}
		
		private function scale($image, $config)
		{
			//todo
		}
		
		private function getFilename($config)
		{
			$filepath = $this->getFilePath($config);
			$pathinfo = pathinfo($this->filename);
			$basename = $pathinfo['basename'];
			// $basename = $basename.'.png';
			
			$filename = $filepath.$basename;
			// die($filename);
			return $filename;
		}
		
		private function getFilePath($config)
		{
			return $this->outFilePath._DS_.$config->width.'x'.$config->height._DS_;
		}
	}
}
