<?php
namespace application\nutsNBolts\hook
{
	use application\nutsNBolts\base\ControllerHook;
	use nutshell\Nutshell;
	use nutshell\plugin\template\Context as ViewContext;

	class Admin_settings_users_add extends ControllerHook
	{
		die('salam');
		public function onInitPage(&$page)
		{
			die('hooking');
		}
	}
}
?>