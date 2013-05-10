<?php
namespace
{
	use nutshell\Nutshell;
	use nutsnbolts\NutsNBolts;
	use \Exception;
	
	/**
	 * Nutshell bootsrapper.
	 * This overrides Nutshell's default bootsrapper.
	 *
	 * The bootstrapper simply includes the main application
	 * file (../private/application/NutsNBolts.php) and initates
	 * it. The application file will handle everything from that
	 * point on.
	 *
	 * @global
	 * @return Void
	 */
	function bootstrap()
	{
		define('PUBLIC_DIR', __DIR__ . DIRECTORY_SEPARATOR);
		
		//Include the Connect application.=
		include __DIR__ . '/../private/application/NutsNBolts.php';

		$GLOBALS['NUTSNBOLTS'] = new NutsNBolts();
		$GLOBALS['NUTSNBOLTS'] ->init();
	}
	
	/* By including nutshell below, the framework will
	 * auto-initiate. Nutshell will detect our custom bootstrap
	 * and execute it.
	 */
	require __DIR__ . '/../private/lib/nutshell/Nutshell.php';
}
?>