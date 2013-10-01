<?php
namespace application\nutsNBolts\plugin\userAuth
{
	use nutshell\core\exception\NutshellException;

	
	class UserAuthException extends NutshellException
	{
		/** The request must be valid JSON */
		const PASSWORDS_DO_NOT_MATCH			= 1;
		
		const REQUEST_FAILED = 2;

		const REQUIRED_FIELD_MISSING=3;
		
	}
}
?>