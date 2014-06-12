<?php
namespace application\nutsNBolts\controller\rest
{
	use application\plugin\rest\RestController;
	use nutshell\Nutshell;
	use nutshell\plugin\session; 
	use \DateTime;

	class Node extends RestController
	{
		private $map=array
		(
			'facetedSearch'				=>'search'
		);

		public function search()
		{
			$facets=$this->request->get('item');
			if($facets[0])
			{
				$json=json_decode($facets,true);

				$videoContentTypeId=\application\efti\Efti::CONTENT_TYPE_VIDEO;
				$allVideos=$this->model->Node->getWithParts(['content_type_id'=>$videoContentTypeId]);
				$requiredKeys=[];
				
				foreach($json AS $searchKeys=>$searchItem)
				{
					// generate an array of the required facet keys to search the node data with.
					$facetKey=strtolower(str_replace('-','_',$searchKeys));
					$requiredKeys[]=$facetKey;
					// cleanup the json array with the proper index to help speed up the search speed.
					$json[$facetKey]=$json[$searchKeys];
					unset($json[$searchKeys]);
				}
				
				for($i=0,$j=count($allVideos);$i<$j;$i++)
				{
					if(count(array_intersect_key(array_flip($requiredKeys), $allVideos[$i])) === count($requiredKeys))
					{
						// all the search criteria exists (not the search value)
						for($b=0,$c=count($requiredKeys);$b<$c;$b++)
						{
							if(!in_array($allVideos[$i][$requiredKeys[$b]],$json[$requiredKeys[$b]]))
							{
								unset($allVideos[$i]);
							}
						}
					}
				}
			}

			if(count($allVideos))
			{
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					array('results'=>array_values($allVideos))
				);
			}
			else
			{
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'EMPTY',
					null
				);
			}
		}	
	}
}
?>