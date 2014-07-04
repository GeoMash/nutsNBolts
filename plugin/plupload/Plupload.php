<?php
namespace application\nutsNBolts\plugin\plupload
{
	use nutshell\Nutshell;
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;
	// use application\plugin\plupload\PluploadException;
	// use application\plugin\plupload\ThumbnailMaker;
	
	class Plupload extends Plugin implements Singleton
	{
		public function init()
		{
			// require_once(__DIR__._DS_.'PluploadException.php');
			// require_once(__DIR__._DS_.'ThumbnailMaker.php');
			// require_once(__DIR__._DS_.'thirdparty'._DS_.'PluploadProcessor.php');
		}
		
		private $callback = null;
		
		public function getCallback()
		{
		    return $this->callback;
		}
		
		public function setCallback($callback)
		{
		    $this->callback = $callback;
		    return $this;
		}
		
		public function upload()
		{
			// Check for Data
			if(!isset($_SERVER["HTTP_CONTENT_TYPE"]) && !isset($_SERVER["CONTENT_TYPE"]))
			{
				throw new PluploadException(PluploadException::MUST_HAVE_DATA, $_SERVER);
			}
			
			$config = Nutshell::getInstance()->config;
			$temporary_dir = $config->plugin->Plupload->temporary_dir;
			
			$plupload = new thirdparty\PluploadProcessor();
			$plupload->setTargetDir($temporary_dir);
			$plupload->setCallback(array($this, 'uploadComplete'));
			$plupload->process();
		}
		
		public function uploadComplete($filePathAndName)
		{
			$config = Nutshell::getInstance()->config;
			$completed_dir = $config->plugin->Plupload->completed_dir;
			// $thumbnail_dir = $config->plugin->Plupload->thumbnail_dir;
			$temporary_dir = $config->plugin->Plupload->temporary_dir;
			$pathinfo	= pathinfo($filePathAndName);
			$basename	= $pathinfo['basename'];	// eg. myImage.jpg
			$filename 	= $pathinfo['filename'];	// eg. myImage
			$extension	= $pathinfo['extension'];	// eg. jpg
			
			// $thumbnailMaker = new ThumbnailMaker();
			
			// Create thumbnail, move to complete dir
			// if (!file_exists($thumbnail_dir)) @mkdir($thumbnail_dir, 0755, true);
			if (!file_exists($completed_dir)) @mkdir($completed_dir, 0755, true);
			
			switch($extension)
			{
				case 'jpg':
				case 'jpeg':
				case 'png':
					// Make thumbnails from the image, store them in the thumbnail dir
					// $thumbnailMaker->processFile($filePathAndName);
					// Move the image to the complete dir
					rename($filePathAndName, $completed_dir.$basename);
					break;
					
				case 'mp4':
					// get a screenshot from the video, store it in the temp dir
					$this->videoScreenshot($filePathAndName, $temporary_dir.$basename.'.png');
					// Make thumbnails from the screenshot, store them in the thumbnail dir
					// $thumbnailMaker->processFile($temporary_dir.$basename.'.png', $basename.'.png');
					// delete the screenshot in the temporary dir
					@unlink($temporary_dir.$basename.'.png');
					// move the video to the complete dir
					rename($filePathAndName, $completed_dir.$basename);
					break;
					
				case 'zip':
					// unzip the file into a directory by the same name in the temp dir
					$this->unzip($filePathAndName, $temporary_dir.$filename);
					// delete the original file
					@unlink($filePathAndName);
					// Make thumbnails from the provided 'preview.png', store them in the thumbnail dir
					$previewFileName = $temporary_dir.$filename._DS_.'preview.png';
					// if(file_exists($previewFileName)) $thumbnailMaker->processFile($previewFileName, $basename.'.png');
					// delete any existing folder in the complete dir by that name
					$this->recursiveRemove($completed_dir.$filename);
					// move the folder into the complete dir
					rename($temporary_dir.$filename, $completed_dir.$filename);
					break;
					
				default:
					// Move the file to the complete dir
					rename($filePathAndName, $completed_dir.$basename);
			}
			$filepath=null;
			
			// process any extra stuff
			if($this->callback)
			{
				$filepath=call_user_func_array
				(
					$this->callback,
					array($basename)
				 );
			}
			return $filepath;
		}
		
		private function videoScreenshot($originalFile, $newFile, $percentage = 10)
		{
			// Check ffmpeg is configured
			$config = Nutshell::getInstance()->config;
			$ffmpeg_dir = $config->plugin->Plupload->ffmpeg_dir;
			if(!$ffmpeg_dir) return;
			
			// Get the potision a percentage of the way in the video
			$duration = $this->getVideoDuration($originalFile);
			$position = ($duration * ($percentage / 100));
			
			// save the screenshot
			$command = "\"{$ffmpeg_dir}ffmpeg\" -i \"$originalFile\" -ss $position -f image2 \"$newFile\"";
			shell_exec($command);
		}
		
		private function getVideoDuration($filename, $seconds = true)
		{
			$config = Nutshell::getInstance()->config;
			$ffmpeg_dir = $config->plugin->Plupload->ffmpeg_dir;
			if(!$ffmpeg_dir) return;
			
			ob_start();
			$command = "\"{$ffmpeg_dir}ffmpeg\" -i \"$filename\" 2>&1";
			passthru($command);
			$result = ob_get_contents();
			ob_end_clean();
			
			preg_match('/Duration: (.*?),/', $result, $matches);
			$duration = $matches[1];
			
			if($seconds)
			{
				$duration_array = explode(':', $duration);
				$duration = $duration_array[0] * 3600 + $duration_array[1] * 60 + $duration_array[2];
			}
			return $duration;
		}
		
		private function unzip($file, $directory)
		{
			$zipArchive = new \ZipArchive();
			$result = $zipArchive->open($file);
			if ($result) {
				$zipArchive ->extractTo($directory);
				$zipArchive ->close();
			}
		}
		
		private function recursiveRemove($file)
		{
			if(!is_dir($file))
			{
				if(file_exists($file))
				{
					unlink($file);
				}
				return;
			}
			foreach(glob($file . '/*') as $subFile)
			{
				if(is_dir($subFile))
				{
					self::recursiveRemove($subFile);
				}
				else
				{
					unlink($subFile);
				}
			}
			rmdir($file);
		}
	}
}
