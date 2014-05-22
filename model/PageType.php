<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\PageType as PageTypeBase;
	
	class PageType extends PageTypeBase	
	{
		public function handleDeleteRecord($id)
		{
			return $this->delete(array('id'=>$id));
		}
	}
}
?>