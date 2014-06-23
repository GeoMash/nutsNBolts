<?php
namespace application\nutsNBolts\plugin\subscription
{
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use application\nutsNBolts\base\Plugin;

	class Subscription extends Plugin implements Singleton, Native
	{
		const STATUS_AUTO_MANUAL	=-2;
		const STATUS_AUTO_CANCELLED	=-1;
		const STATUS_PENDING		=0;
		const STATUS_ACTIVE			=1;
		
		public function init()
		{
			
		}
	}
}