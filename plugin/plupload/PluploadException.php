<?php
namespace application\nutsnbolts\plugin\plupload
{
	use nutshell\core\exception\NutshellException;

	/**
	 * @author Dean Rather
	 */
	class PluploadException extends NutshellException
	{
		/** You cannot upload without data */
		const MUST_HAVE_DATA = 1;
	}
}
?>