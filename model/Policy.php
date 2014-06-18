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
			if ((bool)(int)$data[$key]['enabled'])
			{
				$record[$key]=1;
			}
			else
			{
				$record[$key]=null;
			}
			$key='password_length_minimum';
			if ((bool)(int)$data[$key]['enabled'])
			{
				$record[$key]=(int)$data[$key]['value'];
			}
			else
			{
				$record[$key]=null;
			}
			$key='password_length_maximum';
			if ((bool)(int)$data[$key]['enabled'])
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