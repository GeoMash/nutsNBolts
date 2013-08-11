<?php
namespace application\nutsnbolts\plugin\plupload\thirdparty;
/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 * refactored into an object by Dean Rather
 */
class PluploadProcessor
{
	private $targetDir			= null;
	private $cleanupTargetDir	= null; // Remove old files
	private $maxFileAge			= null; // Temp file age in seconds
	private $maxExecutionTime	= null; // 5 minutes execution time
	private $filenameCleanRegex	= null;
	private $callback			= null;
	
	public function __construct()
	{
		$this->targetDir			= ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		$this->cleanupTargetDir		= true;
		$this->maxFileAge			= 5 * 3600;
		$this->maxExecutionTime		= 5 * 60;
		$this->filenameCleanRegex	= '/[^\w\._]+/';
		$this->callback				= null;
	}
	
	public function getTargetDir()
	{
	    return $this->targetDir;
	}
	
	public function setTargetDir($targetDir)
	{
	    $this->targetDir = $targetDir;
	    return $this;
	}
	
	public function getCleanupTargetDir()
	{
	    return $this->cleanupTargetDir;
	}
	
	public function setCleanupTargetDir($cleanupTargetDir)
	{
	    $this->cleanupTargetDir = $cleanupTargetDir;
	    return $this;
	}
	
	public function getMaxFileAge()
	{
	    return $this->maxFileAge;
	}
	
	public function setMaxFileAge($maxFileAge)
	{
	    $this->maxFileAge = $maxFileAge;
	    return $this;
	}
	
	public function getMaxExecutionTime()
	{
	    return $this->maxExecutionTime;
	}
	
	public function setMaxExecutionTime($maxExecutionTime)
	{
	    $this->maxExecutionTime = $maxExecutionTime;
	    return $this;
	}
	
	public function getFilenameCleanRegex()
	{
	    return $this->filenameCleanRegex;
	}
	
	public function setFilenameCleanRegex($filenameCleanRegex)
	{
	    $this->filenameCleanRegex = $filenameCleanRegex;
	    return $this;
	}
	
	public function getCallback()
	{
	    return $this->callback;
	}
	
	public function setCallback($callback)
	{
	    $this->callback = $callback;
	    return $this;
	}
	
	public function process()
	{
		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		// Settings
		$targetDir			= $this->targetDir;
		$cleanupTargetDir	= $this->cleanupTargetDir;
		$maxFileAge			= $this->maxFileAge;
		$maxExecutionTime	= $this->maxExecutionTime;
		$filenameCleanRegex	= $this->filenameCleanRegex;
		$callback			= $this->callback;
		
		@set_time_limit($maxExecutionTime);

		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

		// Clean the fileName for security reasons
		$fileName = preg_replace($filenameCleanRegex, '_', $fileName);

		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName))
		{
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);

			$count = 1;
			while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
				$count++;

			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

		// Create target dir
		if (!file_exists($targetDir)) @mkdir($targetDir, 0755, true);

		// Remove old temp files	
		if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir)))
		{
			while (($file = readdir($dir)) !== false)
			{
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part"))
				{
					@unlink($tmpfilePath);
				}
			}

			closedir($dir);
		}
		else
		{
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
		}
		
		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"])) $contentType	= $_SERVER["HTTP_CONTENT_TYPE"];
		if (isset($_SERVER["CONTENT_TYPE"])) $contentType		= $_SERVER["CONTENT_TYPE"];

		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false)
		{
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name']))
			{
				// Open temp file
				$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out)
				{
					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");

					if ($in)
					{
						while ($buff = fread($in, 4096))
						{
							fwrite($out, $buff);
						}
					}
					else
					{
						die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					}
					
					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				}
				else
				{
					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
				}
			}
			else
			{
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}
		}
		else
		{
			// Open temp file
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out)
			{
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");

				if ($in)
				{
					while ($buff = fread($in, 4096))
					{
						fwrite($out, $buff);
					}
				}
				else
				{
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				}

				fclose($in);
				fclose($out);
			}
			else
			{
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			}
		}

		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1)
		{
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
			
			if($callback)
			{
				call_user_func_array
				(
					$callback,
					array($filePath)
				 );
			}
			
			die('{"jsonrpc" : "2.0", "result" : "complete", "id" : "id"}');
		}

		// Return JSON-RPC response
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}
}
