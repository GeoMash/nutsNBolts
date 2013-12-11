<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\Page as PageBase;
	
	class Page extends PageBase
	{
		public function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				$pageURLs	=$this->extractURLs($record,'page_id');
				$return		=$this->update($record,array('id'=>$record['id']));
				//Update URLs
				$this->model->PageMap->delete(array('page_id'=>$record['id']));
				for ($i=0,$j=count($pageURLs); $i<$j; $i++)
				{
					$this->model->PageMap->insertAssoc($pageURLs[$i]);
				}
				return $return;
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$pageURLs	=$this->extractURLs($record,'page_id');
				if ($id=$this->insertAssoc($record))
				{
					for ($i=0,$j=count($pageURLs); $i<$j; $i++)
					{
						$pageURLs[$i]['page_id']=$id;
						$this->model->PageMap->insertAssoc($pageURLs[$i]);
					}
					return $id;
				}
			}
			return false;
		}
	}
}
?>