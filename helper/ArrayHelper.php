<?php
namespace application\helper 
{
	class ArrayHelper
	{
		static public function flatten(Array $array)
		{
			$newArray=array();
			foreach ($array as $val)
			{
				if (is_array($val))
				{
					$newArray=array_merge($newArray,self::flatten($val));
				}
				else
				{
					$newArray[]=$val;
				}
			}
			return $newArray;
		}
		
		static public function without(Array $array,$without)
		{
			if (!is_array($without))
			{
				$without=array($without);
			}
			$newArray=array();
			foreach ($array as $key=>$val)
			{
				if (!in_array($key,$without))
				{
					$newArray[$key]=$val;
				}
			}
			return $newArray;
		}
		
		static public function closest(Array $array,$value)
		{
			sort($array);
			$closest=$array[0];
			for ($i=1,$j=count($array),$k=0; $i<$j; $i++,$k++)
			{
				$middleValue=($array[$i]-$array[$k])/2+$array[$k];
				if ($value>=$middleValue)
				{
					$closest=$array[$i];
				}
			}
			return $closest;
		}
		
		static public function trim(Array $array)
		{
			foreach($array as $key=>$val)
			{
				if (is_string($val))
				{
					$array[$key]=trim($val);
				}
			}
			return $array;
		}
		
		static public function trimKeys(Array $array)
		{
			$newArray=array();
			foreach($array as $key=>$val)
			{
				if (is_string($key))
				{
					$newArray[trim($key)]=$val;
				}
				else
				{
					$newArray[$key]=$val;
				}
			}
			return $newArray;
		}
	}
}