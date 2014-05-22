<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\SiteSettings as SiteSettingsBase;
	
	class SiteSettings extends SiteSettingsBase	
	{
		public function insertAll($records)
		{
			$this->getDb()->truncate($this->name);
			for ($i=0,$j=count($records); $i<$j; $i++)
			{
				$records[$i]['label']=$this->makeSafeFieldName($records[$i]['label']);
				$this->insertAssoc($records[$i]);
			}
			return $this;
		}
	}
}
?>