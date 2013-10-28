<?php
/**
 * SMS plugin main class
 *
 * @name Sms
 *
 * SMS plugin for Nuts n Bolts
 */
namespace application\nutsNBolts\plugin\sms
{
	use nutshell\behaviour\Native;
	use nutshell\behaviour\AbstractFactory;
	use nutshell\core\exception\NutshellException;
	use nutshell\core\plugin\Plugin;
	/**
	 * Sms plugin requires php version 5.4< to run optimally. Requires the nutshell framework
	 * @package application\nutsNBolts\plugins\sms
	 * @author Timothy Chandler <tim@geomash.com>
	 */
	class Sms extends Plugin implements Native,AbstractFactory
	{
		/**
		 *@ignore
		 **/
		public static function registerBehaviours(){}

		public static function runFactory($smsHandler)
		{
			$className='application\nutsNBolts\plugin\sms\handler\\'.$smsHandler;

			if(!class_exists($className,true))
			{
				throw new NutshellException('Invalid handler for SMS Plugin."'.$smsHandler.'" is not defined.');
			}
			else
			{
				return new $className();
			}
		}
	}
}
?>