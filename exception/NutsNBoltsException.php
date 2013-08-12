<?php
namespace application\exception
{
	use nutshell\core\exception\NutshellException;

	/**
	 * @author Guillaume Bodi <gbodi@praxisbt.com>
	 */
	class NutsNBoltsException extends NutshellException
	{
		const OPERATION_REQUIRES_ID = 10;
		const OPERATION_REQUIRES_DATA = 11;
		const ID_MUST_BE_NUMERIC = 12;
	}
}
