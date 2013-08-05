<?php
namespace application\nutsnbolts\helper
{
	use \DateTime;
	use \DateTimeZone;

	class TimeHelper
	{
		private static $instance = null;

		private $timeZone = null;

		const UTC_TZ_CODE = 'UTC';

		const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';

		const UNIX_FORMAT = 'U';

		public static function getInstance()
		{
			if(is_null(self::$instance)) {
				self::$instance = new TimeHelper();
			}

			return self::$instance;
		}

		public function getTimeZone()
		{
			if(is_null($this->timeZone)) {
				$this->timeZone = new DateTimeZone(self::UTC_TZ_CODE);
			}

			return $this->timeZone;
		}

		public function getTime()
		{
			$date = new DateTime('now', $this->getTimeZone());
			return $date->format(self::UNIX_FORMAT);
		}

		public function getTimeStamp()
		{
			$date = new DateTime('now', $this->getTimeZone());
			return $date->format(self::TIMESTAMP_FORMAT);
		}
	}

}