<?php
/**
 * @package nutsNBolts-plugin
 * @author Timothy Chandler
 */
namespace application\nutsNBolts\plugin\user\exception
{
	use nutshell\core\exception\PluginException;

	class UserException extends PluginException
	{
		const GENERAL				=0;
		const USER_EXISTS			=1;
		const PASSWORDS_NO_MATCH	=2;
		const PASSWORD_BLANK		=3;
		const CONFIRMATION_INVALID	=4;
	}
}