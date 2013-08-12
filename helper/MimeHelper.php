<?php
namespace application\nutsNBolts\helper {
	
	use nutshell\core\Component;

	class MimeHelper {
		
		protected static $knownExtensions = null;
		
		const MIME_LIST_PATH = '/resources/mimeList.php';
		
		protected static function loadMimeList() 
		{
			if(!is_null(self::$knownExtensions)) {
				return;
			}
			
			require_once(__DIR__ . self::MIME_LIST_PATH);
			self::$knownExtensions = $mimeList;
		}
		
		public static function getMimeType($file, $defaultValue = null) 
		{
			self::loadMimeList();
			$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
			
			if(isset(self::$knownExtensions[$extension])) {
				return self::$knownExtensions[$extension];
			}
				
			//lookup the file to see if it exists
			if(file_exists($file)) {
				//try to get the mime type from the file itself
				$finfo = @finfo_open(FILEINFO_MIME_TYPE);
				if($finfo && $mimeType = @finfo_file($finfo, $file)) {
					return $mimeType;
				}
			}
			
			return $defaultValue;
		}
		
		/**
		 * Simple lookup of mime type from a file extension
		 * @param string $extension
		 * @return string
		 */
		public static function getMimeTypeFromExtension($extension)
		{
			self::loadMimeList();
			
			if(isset(self::$knownExtensions[$extension])) {
				return self::$knownExtensions[$extension];
			}
			return null;
		}
		
		
		/**
		 * Simple lookup of extension from mime type
		 *  
		 * @param string $mimeType
		 * @return string 
		 */
		public static function getExtensionFromMimeType($mimeType)
		{
			self::loadMimeList();
			if ($extension = array_search($mimeType, self::$knownExtensions)) {
				return $extension;
			}
			
			return null;
		}
		
		public static function getTypeFromMimeType($mimeType)
		{
			$mimeParts = explode('/', $mimeType);
			$type = array_shift($mimeParts);
			return $type;
		}
		
		public static function getFormatFromMimeType($mimeType)
		{
			$mimeParts = explode('/', $mimeType);
			$type = array_pop($mimeParts);
			return $type;
		}
	}
} 