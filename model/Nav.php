<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\Nav as NavBase;
	
	class Nav extends NavBase	
	{
		public function handleRecord($record)
		{
			if (!isset($record['status']))$record['status']=0;
			//For Updates
			if (isset($record['id']) && is_numeric($record['id']))
			{
				$navItems		=$this->extractNavItems($record);
				$return			=$this->update($this->removeJunk($record),['id'=>$record['id']]);
				//Update items.
				$this->model->NavPart->delete(['nav_id'=>$record['id']]);
				for ($i=0,$j=count($navItems); $i<$j; $i++)
				{
					$navItems[$i]['nav_id']=$record['id'];
					$this->model->NavPart->insertAssoc($navItems[$i]);
				}
				return $return;
			}
			//For Inserts
			else
			{
				unset($record['id']);
				$navItems	=$this->extractNavItems($record);
				if ($id=$this->insertAssoc($this->removeJunk($record)))
				{
					for ($i=0,$j=count($navItems); $i<$j; $i++)
					{
						$navItems[$i]['nav_id']=$id;
						$this->model->NavPart->insertAssoc($navItems[$i]);
					}
					return $id;
				}
			}
			return false;
		}
		
		public function delete($query)
		{
			$nav=$this->read($query);
			var_dump($nav);
			$this->model->NavPart->delete(['nav_id'=>$nav[0]['id']]);
			parent::delete($query);
			return true;
		}
		
		private function extractNavItems(&$record)
		{
			$navItems=[];
			if(isset($record['label']))
			{
				for ($i=0,$j=count($record['label']); $i<$j; $i++)
				{
					$navItems[]=
					[
						'page_id'	=>$record['page_id'][$i],
						'node_id'	=>$record['node_id'][$i],
						'url'		=>$record['url'][$i],
						'order'		=>$record['order'][$i],
						'label'		=>$record['label'][$i]
					];
				}
				unset($record['page_id']);
				unset($record['node_id']);
				unset($record['url']);
				unset($record['order']);
				unset($record['label']);
			}
			return $navItems;
		}
		
		public function getWithParts($id,$status=1)
		{
			if (is_numeric($id))
			{
				$nav=$this->model->Nav->read
				(
					[
						'id'	=>$id,
						'status'=>$status
					]
				);
			}
			else
			{
				$nav=$this->model->Nav->read
				(
					[
						'ref'	=>$id,
						'status'=>$status
					]
				);
			}
			if (isset($nav[0]))
			{
				$nav=$nav[0];
				$nav['items']=$this->model->NavPart->read(['nav_id'=>$nav['id']]);
				return $nav;
			}
			return false;
		}
	}
}
?>