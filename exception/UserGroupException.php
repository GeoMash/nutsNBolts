<?php
namespace application\exception
{
	use application\exception\NutsNBoltsException;
	
	class UserGroupException extends NutsNBoltsException
	{
		protected $message = 'Unknown user account group exception';
		
		public function __construct($message = null, $code = 0, Exception $previous = null) {
			
			parent::__construct($message,$code,$previous);
			if($message) $this->message = $message;
			if($code) $this->code = $code;			

		}
	}
}
