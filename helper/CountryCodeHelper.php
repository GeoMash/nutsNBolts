<?php
namespace application\nutsNBolts\helper 
{
	use nutshell\Nutshell;
	
	class CountryCodeHelper
	{
		const ALPHA_2_CODE_PATH = '/resources/countryListAlpha2Code.php';
		const ALPHA_3_CODE_PATH = '/resources/countryListAlpha3Code.php';
		protected static $alphaCode2 = null;
		protected static $alphaCode3 = null;
		private static $instance = null;

		public function __construct()
		{

		}
		public static function getInstance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new CountryCodeHelper();
			}
			return self::$instance;
		}

		public static function loadAlphaCode2()
		{
			if(!is_null(self::$alphaCode2)) {
				return self::$alphaCode2;
			}
			else
			{
				require_once(__DIR__ . self::ALPHA_2_CODE_PATH);
				return self::$alphaCode2 = $countryListCodeA;
			}
			
		}
		public static function loadAlphaCode3()
		{
			if(!is_null(self::$alphaCode3))
			{
				return self::$alphaCode3;
			}
			else
			{
			require_once(__DIR__ . self::ALPHA_3_CODE_PATH);
			return self::$alphaCode3 = $countryListCodeB;
			}
		}
		public static function cCodeByLongName($longName,$codeReq)
		{
			$cCodeA=self::loadAlphaCode2();
			$cCodeB=self::loadAlphaCode3();
			if(strtolower($codeReq)==='alpha2')
			{
				foreach ($cCodeA as $key => $value)
				{
					if($value===ucwords(strtolower($longName)))
					{
						return $key;
					}
				}
			}
			elseif (strtolower($codeReq)==='alpha3')
			{
				foreach ($cCodeB as $key => $value)
				{
					if($value===$longName)
					{
						return $key;
					}
				}
			}
		}
		public static function cNameByType($type,$code)
		{
			$cCodeA=self::loadAlphaCode2();
			$cCodeB=self::loadAlphaCode3();
			$cCode= null;
			if(strtolower($type)==='alpha2')
			{
				if(array_key_exists(strtoupper($code),$cCodeA))
				{
					$cCode=$cCodeA[$code];
				}
			}
			elseif (strtolower($type)==='alpha3')
			{
				if(array_key_exists(strtoupper($code),$cCodeB))
				{
					$cCode=$cCodeB[$code];
				}
			}
			else
			{
				print('Country code type not found');
			}
			return $cCode;
		}
	}
}