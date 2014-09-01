<?php
/**
 * @package nutsNBolts-plugin
 * @author Timothy Chandler
 */
namespace application\nutsNBolts\plugin\workflow\exception
{
	use nutshell\core\exception\PluginException;

	class WorkflowException extends PluginException
	{
		const INVALID_TRANSITION	=0;
	}
}