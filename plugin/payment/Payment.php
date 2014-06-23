<?php


namespace application\nutsNBolts\plugin\payment
{
	use nutshell\behaviour\Native;
	use nutshell\behaviour\AbstractFactory;
	use nutshell\core\exception\NutshellException;
	use nutshell\core\plugin\Plugin;

	class Payment extends Plugin implements Native,AbstractFactory
	{
		/**
		 *@ignore
		 **/
		public static function registerBehaviours(){}

		public static function runFactory($paymentHandler)
		{
			$className='application\nutsNBolts\plugin\payment\handler\\'.$paymentHandler;

			if(!class_exists($className,true))
			{
				throw new NutshellException('Invalid handler for Payment Plugin."'.$paymentHandler.'" is not defined.');
			}
			else
			{
				return new $className();
			}
		}
	}
}
?>