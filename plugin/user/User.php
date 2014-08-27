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
		
		public function create($record,$emailForRandom=true)
		{
			$this->validate($record);
			if (isset($record['set_random']))
			{
				$record['password']	=$this->generateRandomPassword();
				$record['status']	=self::STATUS_CONFIRMED;
			}
			if ($this->model->User->count(['email'=>$record['email']]))
			{
				throw new UserException(UserException::USER_EXISTS,'A user with that email address has already been registered.');
			}
			if (empty($record['password']))
			{
				throw new UserException(UserException::PASSWORD_BLANK,'Password cannot be blank');
			}
			$result=$this->model->User->handleRecord($record);
			if ($result && isset($record['set_random']) && $emailForRandom)
			{
				$this->sendEmail($record,'NEW_ACCOUNT_PASSWORD');
			}
			//TODO: Alternate account activation email for if set_random is not set.
			return $result;
		}
		
		public function update($record,$removeRoles=false,$emailForRandom=true)
		{
			$this->validate($record);
			if (isset($record['set_random']))
			{
				$record['password']=$this->generateRandomPassword();
			}
			$result=$this->model->User->handleRecord($record,$removeRoles);
			if ($result && isset($record['set_random']) && $emailForRandom)
			{
				$this->sendEmail($record,'ACCOUNT_PASSWORD_RESET');
			}
			return $result;
		}
		
		public function sendEmail($user,$templateRef)
		{
			//Get the email content type.
			$contentType	=$this->model->ContentType->read(['ref'=>'SYSTEM_EMAILS']);
			$emailTemplate	=null;
			//Get the email.
			if (isset($contentType[0]))
			{
				$emailTemplate=$this->model->Node->getWithParts
				(
					[
						'content_type_id'	=>$contentType[0]['id'],
						'ref'				=>$templateRef
					]
				);
				if (isset($emailTemplate[0]))
				{
					$emailTemplate=$emailTemplate[0];
				}
			}
			if ($emailTemplate)
			{
				//Email the user their password.
				$emailTemplate['message']=str_replace
				(
					[
						'{email}',
						'{password}',
						'{name_first}',
						'{name_last}',
						'{website}'
					],
					[
						$user['email'],
						$user['password'],
						$user['name_first'],
						$user['name_last'],
						'http://'.$_SERVER['HTTP_HOST'].'/'
					],
					$emailTemplate['message']
				);
				
				$email			=$this->plugin->Email->smtp;
				$email->Subject	=$emailTemplate['subject'];
				$email->AltBody	=$emailTemplate['non_html_message'];
				$email->MsgHTML($emailTemplate['message']);
				$email->AddAddress($user['email']);
				$email->send();
			}
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
		
		public function generateConfirmationCode()
		{
			$length=20;
			if (function_exists('openssl_random_pseudo_bytes'))
			{
				$code=openssl_random_pseudo_bytes($length/2);
			}
			else
			{
				$process	=fopen('/dev/urandom','rb');
				$code		=fread($process,$length/2);
				fclose($process);
			}
			return bin2hex($code);
		}

		/**
		 * This method sends an Registeration Confirmation Email to the New User
		 * @param $userId , The ID of the User record in the Model
		 * @param $masterTemplate , The HTML template to be used as the message body.
		 * This should contain the tags [NAME_FIRST], [NANME_LAST], [FULL_LINK], and [SHORT_LINK].
		 * @param $longUrl , The HTML Anchor Tag that will be used as a link to the Confirmation Page
		 * @throws exception\UserException
		 */
		public function sendConfirmationEmail($userId, $masterTemplate, $confirmationUrl, $subject = "Confirmation Email")
		{
			$query = $this->model->User->read([
				'id' => $userId
			]);
			if (count($query) == 0)
			{
				throw new UserException(UserException::GENERAL, "Invalid UserId passed, value = {$userId}");
			}
			else
			{
				$user = $query[0];
			}

			$longURL = <<<HTML
<a href="{$confirmationUrl}">{$confirmationUrl}</a>
HTML;
			$masterTemplate = str_replace('{name_first}',	$user['name_first'], $masterTemplate);
			$masterTemplate = str_replace('{name_last}',	$user['name_last'], $masterTemplate);
			$masterTemplate = str_replace('{full_link}',	$longURL, $masterTemplate);
			$masterTemplate = str_replace('{short_link}',	$user['confirmation_code'], $masterTemplate);

			$email			=$this->plugin->Email->smtp;
			$email->Subject	=$subject;
			$email->AltBody	=nl2br($masterTemplate);
			$email->MsgHTML(nl2br($masterTemplate));
			$email->AddAddress($user['email']);
			$email->send();
		}

		/**
		 * This method swithch the state of a User to Confirmed
		 * @param $userId , The ID of the User in the Model
		 * @param $confirmationCode , The Confirmation to validate against
		 * @throws exception\UserException
		 */
		public function confirmRegisteration($confirmationCode)
		{
			$query = $this->model->User->read([
				'confirmation_code' => $confirmationCode
			]);

			if (count($query) == 0)
			{
				throw new UserException(UserException::CONFIRMATION_INVALID, "No User found with this Confirmation Code.");
			}
			
			$user = $query[0];

			if ($user['confirmation_code'] != $confirmationCode)
			{
				throw new UserException(UserException::CONFIRMATION_INVALID, "Confirmation Code is Invalid.");
			}

			if ($user['status'] != User::STATUS_CONFIRMED)
			{
				$this->model->User->update
				(
					['status' => User::STATUS_CONFIRMED],
					['id' => $user['id']]
				);
			}
			else
			{
				throw new UserException(UserException::CONFIRMATION_INVALID, 'This account has already been activated.');
			}
		}
	}
}