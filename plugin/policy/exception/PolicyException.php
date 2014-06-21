<?php
/**
 * @package nutsNBolts-plugin
 * @author Timothy Chandler
 */
namespace application\nutsNBolts\plugin\policy\exception
{
	use nutshell\core\exception\PluginException;

	class PolicyException extends PluginException
	{
		const POLICY_VALIDATION_FAILED	=1;
		const PASSWORD_TOO_SHORT		=2;
		const PASSWORD_TOO_LONG			=3;
		const PASSWORD_NO_SPECIALS		=4;
		const PASSWORD_NO_UPPER			=6;
		const PASSWORD_NO_LOWER			=7;
		const PASSWORD_NO_NUMERIC		=8;
		const PASSWORD_USER_DATA		=9;
		const PASSWORD_EXPIRED			=10;
		const PASSWORD_USED_IN_PAST		=11;
	}
}