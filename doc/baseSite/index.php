<?php
namespace
{
	use nutshell\Nutshell;
	
	/**
	 * Nutshell bootsrapper.
	 * This overrides Nutshell's default bootsrapper.
	 *
	 * @global
	 * @return Void
	 */
	function bootstrap()
	{
		define('PUBLIC_DIR', __DIR__ . DIRECTORY_SEPARATOR);
		
		Nutshell::setApplictionPath(__DIR__.'/../private/application');
		Nutshell::registerDefaultConfig('YOUR_SITE');
		
		Nutshell::getInstance()->application->NutsNBolts();
		Nutshell::getInstance()->application->YOUR_SITE();
	}
	
	/* By including nutshell below, the framework will
	 * auto-initiate. Nutshell will detect our custom bootstrap
	 * and execute it.
	 */
	require __DIR__ . '/../private/lib/nutshell/Nutshell.php';
}
?>