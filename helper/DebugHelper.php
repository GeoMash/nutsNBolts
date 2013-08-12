<?php
/**
 * @package nutshell
 * @author dean
 */
namespace application\nutsNBolts\helper
{
	/**
	 * The Debug Helper class
	 * If you use this instead of var_dump while debugging, it might save you some woes.
	 * 
	 * @author dean
	 * @package nutshell
	 * @static
	 */
	class DebugHelper
	{
		/**
		 * This function acts much like var_dump, except that it includes the calling file and line number.
		 * @param string $classname
		 */
		public static function log($data)
		{
			self::debugHeader('Log');
			var_dump($data);
		}
		
		public static function trace()
		{
			$trace=debug_backtrace();
			echo '<hr>';
			self::debugHeader('Trace');
			foreach($trace as $call)
			{
				if(!isset($call['file'])) continue;
				echo $call['file'].' ('.$call['line'].')<br>';
			}
			echo '<hr>';
		}
		
		private static function debugHeader($name='log')
		{
			$trace=debug_backtrace();
			$call=$trace[1];
			echo "DebugHelper $name from: ".$call['file'].' ('.$call['line'].')';
		}
	}
}
?>