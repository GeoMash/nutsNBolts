<?php
namespace application\nutsnbolts\model
{
	use application\nutsnbolts\model\base\PageMap as PageMapBase;
	
	class PageMap extends PageMapBase	
	{
		public function getPageFromPath($path)
		{
			$path=rtrim($path,'/');
			if (empty($path))
			{
				$path='/';
			}
			//Is there a URL matching this exact path?
			$query=<<<SQL
			SELECT page.*
			FROM page
			LEFT JOIN page_map ON page_map.page_id=page.id
			WHERE page_map.url=?;
SQL;
			if ($this->db->select($query,array($path)))
			{
				return $this->db->result('assoc')[0];
			}
			
			//No, so lets see if we can find one that has a wildcard.
			
			$pathParts	=explode('/',$path);
			$checks		=array();
			$currentPath='';
			for ($i=0,$j=count($pathParts); $i<$j; $i++)
			{
				if (!empty($pathParts[$i]))
				{
					$currentPath.='/';
				}
				$currentPath.=$pathParts[$i];
				$checks[]=$currentPath.'/\\\\*';
			}
			$regexp='('.implode(')|(',$checks).')';
			$query=<<<SQL
			SELECT page.*
			FROM page
			LEFT JOIN page_map ON page_map.page_id=page.id
			WHERE page_map.url REGEXP('^{$regexp}\$')
			ORDER BY LENGTH(page_map.url) DESC
			LIMIT 1;
SQL;
			if ($this->db->select($query))
			{
				return $this->db->result('assoc')[0];
			}
			return false;
		}
	}
}
?>