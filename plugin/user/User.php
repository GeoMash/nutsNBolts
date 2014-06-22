<?php
namespace application\nutsNBolts\plugin\user
{
	use application\nutsNBolts\plugin\policy\exception\PolicyException;
	use application\nutsNBolts\plugin\user\exception\UserException;
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use application\nutsNBolts\base\Plugin;

	class User extends Plugin implements Singleton, Native
	{
		const STATUS_BANNED			=-2;
		const STATUS_DEACTIVATED	=-1;
		const STATUS_UNCONFIRMED	=0;
		const STATUS_CONFIRMED		=1;
		
		public function init()
		{
			
		}
		
		public function create($record)
		{
			$this->validate($record);
			if (isset($record['set_random']))
			{
				$record['password']=$this->generateRandomPassword();
			}
			if ($this->model->User->count(['email'=>$record['email']]))
			{
				throw new UserException(UserException::USER_EXISTS,'A user with that email address has already been registered.');
			}
			if (empty($record['password']))
			{
				throw new UserException(UserException::PASSWORD_BLANK,'Password cannot be blank');
			}
			return $this->model->User->handleRecord($record);
		}
		
		public function update($record,$removeRoles=false)
		{
			$this->validate($record);
			if (isset($record['set_random']))
			{
				$record['password']=$this->generateRandomPassword();
			}
			return $this->model->User->handleRecord($record,$removeRoles);
		}
		
		private function validate(&$record)
		{
			if (empty($record['password']))
			{
				return;
			}
			if (!$this->plugin->Policy->getPolicyValue('password','force_random'))
			{
				if ($record['password']!=$record['password_confirm'])
				{
					throw new UserException(UserException::PASSWORDS_NO_MATCH,'Passwords did not match.');
				}
				$minLength		=(int)$this->plugin->Policy->getPolicyValue('password','length_minimum');
				$maxLength		=(int)$this->plugin->Policy->getPolicyValue('password','length_maximum');
				$specialChars	=(bool)$this->plugin->Policy->getPolicyValue('password','special_characters');
				$upperLowerChars=(bool)$this->plugin->Policy->getPolicyValue('password','upper_lower_characters');
				$numericDigits	=(bool)$this->plugin->Policy->getPolicyValue('password','numeric_digits');
				
				if ($minLength && strlen($record['password'])<$minLength)
				{
					throw new PolicyException(PolicyException::PASSWORD_TOO_SHORT,'Password must be at least "'.$minLength.'" characters in length.');
				}
				if ($maxLength && strlen($record['password'])>$maxLength)
				{
					throw new PolicyException(PolicyException::PASSWORD_TOO_LONG,'Password cannot be more than "'.$maxLength.'" characters in length.');
				}
				$specials='!@#$%^&*_+-=';
				if ($specialChars && !preg_match('/['.$specials.']/',$record['password']))
				{
					throw new PolicyException(PolicyException::PASSWORD_NO_SPECIALS,'Password must contain at least 1 special character ('.$specials.').');
				}
				if ($numericDigits && !preg_match('/[0-9]/',$record['password']))
				{
					throw new PolicyException(PolicyException::PASSWORD_NO_NUMERIC,'Password must contain at least 1 numeric digit.');
				}
				if ($upperLowerChars)
				{
					if (!preg_match('/[a-z]/',$record['password']))
					{
						throw new PolicyException(PolicyException::PASSWORD_NO_LOWER,'Password must contain at least 1 lower case character.');
					}
					if (!preg_match('/[A-Z]/',$record['password']))
					{
						throw new PolicyException(PolicyException::PASSWORD_NO_UPPER,'Password must contain at least 1 upper case character.');
					}
				}
			}
		}
		
		public function generateRandomPassword()
		{
			$minLength=(int)$this->plugin->Policy->getPolicyValue('password','length_minimum');
			if (!$minLength)
			{
				$minLength=8;
			}
			if (function_exists('openssl_random_pseudo_bytes'))
			{
				$password=openssl_random_pseudo_bytes($minLength/2);
			}
			else
			{
				$process	=fopen('/dev/urandom','rb');
				$password	=fread($process,$minLength/2);
				fclose($process);
			}
			$password=bin2hex($password);
			if ($this->plugin->Policy->getPolicyValue('password','special_characters'))
			{
				$specials	=['!','@','#','$','%','^','&','*','-','+','_','='];
				$password	.=$specials[mt_rand(0,count($specials)-1)];
			}
			if ($this->plugin->Policy->getPolicyValue('password','numeric_digits') && !preg_match('/[0-9]/',$password))
			{
				$numbers	=range(0,9);
				$password	.=$numbers[mt_rand(0,count($numbers)-1)];
			}
			if ($this->plugin->Policy->getPolicyValue('password','upper_lower_characters'))
			{	
				if (!preg_match('/[a-z]/',$password))
				{
					$characters	=range('a','z');
					$password	.=$characters[mt_rand(0,count($characters)-1)];
				}
				if (!preg_match('/[A-Z]/',$password))
				{
					$characters	=range('A','Z');
					$password	.=$characters[mt_rand(0,count($characters)-1)];
				}
			}
			$password=str_shuffle($password);
			return $password;
		}
	}
}