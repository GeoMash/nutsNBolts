<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\Policy as PolicyBase;
	
	class Policy extends PolicyBase	
	{
		public function handleRecord($data)
		{
			$record=[];
			$key='password_force_random';
			if (isset($data[$key]['enabled']) && (bool)(int)$data[$key]['enabled'])
			{
				$record[$key]=1;
			}
			else
			{
				$record[$key]=null;
			}
			$key='password_length_minimum';
			if (isset($data[$key]['enabled']) && (bool)(int)$data[$key]['enabled'])
			{
				$record[$key]=(int)$data[$key]['value'];
			}
			else
			{
				$record[$key]=null;
			}
			$key='password_length_maximum';
			if (isset($data[$key]['enabled']) && (bool)(int)$data[$key]['enabled'])
			{
				$record[$key]=(int)$data[$key]['value'];
			}
			else
			{
				$record[$key]=null;
			}
			$key='password_special_characters';
			if (isset($data[$key]['enabled']) && (bool)(int)$data[$key]['enabled'])
			{
				$record[$key]=1;
			}
			else
			{
				$record[$key]=null;
			}
			$key='password_numeric_digits';
			if (isset($data[$key]['enabled']) && (bool)(int)$data[$key]['enabled'])
			{
				$record[$key]=1;
			}
			else
			{
				$record[$key]=null;
			}
			$key='password_upper_lower_characters';
			if (isset($data[$key]['enabled']) && (bool)(int)$data[$key]['enabled'])
			{
				$record[$key]=1;
			}
			else
			{
				$record[$key]=null;
			}
			$key='password_expiry';
			if (isset($data[$key]['enabled']) && (bool)(int)$data[$key]['enabled'])
			{
				$record[$key]=(int)$data[$key]['value'];
			}
			else
			{
				$record[$key]=null;
			}
			$key='password_past_passwords';
			if (isset($data[$key]['enabled']) && (bool)(int)$data[$key]['enabled'])
			{
				$record[$key]=(int)$data[$key]['value'];
			}
			else
			{
				$record[$key]=null;
			}
			$this->delete();
			$this->insertAssoc($record);
		}
	}
}
?>