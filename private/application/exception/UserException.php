<?php
namespace application\exception
{
	use application\exception\GeomashException;
	
	class UserException extends GeomashException
	{
		const NOT_EXIST = 'The specified user does not exist.';
		const EMAIL_EXISTS = 'The specified email address already exists in the database.';
		const REQUIRE_DELETE_FLAG = 'Deleting a user requires confirmation. Please use the --delete flag to confirm deletion.';

		
		protected $message = 'Unknown user exception';
		
		public function __construct($message = null, $code = 0, Exception $previous = null) {
			
			parent::__construct($message,$code,$previous);
			if($message) $this->message = $message;
			if($code) $this->code = $code;			

		}
	}
}
