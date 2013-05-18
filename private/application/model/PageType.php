<?php
namespace application\model
{
	use application\model\base\PageType as PageTypeBase;
	
	class PageType extends PageTypeBase	
	{
		public function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				$this->update($record,array('id'=>$record['id']));
			}
			//For Inserts
			else
			{
				return $this->insertAssoc($record);
			}
			return false;
		}
	}
}
?>