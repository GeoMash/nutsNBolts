<?php
namespace application\nutsNBolts\plugin\policy
{
	use application\nutsNBolts\plugin\policy\exception\PolicyException;
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Singleton;
	use application\nutsNBolts\base\Plugin;

	class Policy extends Plugin implements Singleton, Native
	{
		const PASSWORD='password_';
	
		private $policies=
		[
			'password'=>[]
		];
	
		public function init()
		{
			$policies=$this->model->Policy->read();
			if (isset($policies[0]))
			{
				foreach ($policies[0] as $policy=>$value)
				{
					if (substr($policy,0,strlen(self::PASSWORD))==self::PASSWORD)
					{
						$this->policies['password'][str_replace(self::PASSWORD,'',$policy)]=$value;
					}
				}
			}
		}
		
		public function hasPolicy($category,$policy=null)
		{
			if (is_null($policy))
			{
				return (bool)(count($this->policies[$category]));
			}
			else
			{
				return (isset($this->policies[$category][$policy]));
			}
		}
		
		public function policyEnabled($category,$policy)
		{
			return (is_null($this->policies[$category][$policy]));
		}
		
		public function getPolicyValue($category,$policy)
		{
			return $this->policies[$category][$policy];
		}
		
		public function validatePassword($password)
		{
			
		}
		
		public function generateRandomPassword()
		{
			if (function_exists('openssl_random_pseudo_bytes'))
			{
				
			}
		}
	}
}