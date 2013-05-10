<?php
namespace application\plugin\responder
{
	use nutshell\core\exception\NutshellException;

	/**
	 * @author Dean Rather
	 */
	class ResponderException extends NutshellException
	{
		/** 'debug' is not an acceptable response type unless you are in DEV mode */
		const CANNOT_DEBUG_IN_PRODUCTION = 1;
	}
}
?>