<?php
/**
 * @package nutsNBolts-plugin
 * @author Timothy Chandler
 */
namespace application\nutsNBolts\plugin\auth\exception
{
	use nutshell\core\exception\PluginException;

	class AuthException extends PluginException
	{
		const PERMISSION_DENIED	=1;
		const NOT_AUTHENTICATED	=2;
		const INVALID_USERNAME	=3;
		const INVALID_PASSWORD	=4;
	}
}