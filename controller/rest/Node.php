<?php
namespace application\nutsNBolts\controller\rest
{
	use application\efti\Efti;
	use application\plugin\rest\RestController;
	use nutshell\Nutshell;
	use nutshell\plugin\session; 
	use \DateTime;

	class Node extends RestController
	{
		private $map=array
		(
			'facetedSearch'					=>'search',
			'createPlaylist'				=>'createPlaylist',
			'addToPlaylist'					=>'addToPlaylist'
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
							else
							{
								$allVideos[$i]['thumbnail']=$this->plugin->Video->getThumbnail($allVideos[$i]['video']);
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
		
		public function createPlaylist()
		{
			$record							=[];
			$record['original_user_id']		=$this->plugin->Session->userId;
			$record['status']				=2;
			
			foreach($this->request->getAll() AS $key=>$val)
			{
				$record[$key]=$val;				
			}
			
			$nodeId=$this->plugin->Mvc->model->Node->handleRecord($record);
			
			$record['id']=$nodeId;
			$this->setResponseCode(200);
			$this->respond
			(
				true,
				'OK',
				$record
			);
		}
		
		public function addToPlaylist()
		{
			$record							=[];
			$record['original_user_id']		=$this->plugin->Session->userId;
			$record['status']				=2;

			$thisPlayList=$this->plugin->Mvc->model->Node->read
			(
				[
					'id'				=>$this->request->get('_playlist'),
					'original_user_id'	=>$this->plugin->Session->userId,
					'content_type_id'	=>Efti::CONTENT_TYPE_PLAYLIST
				]
			);

			$allPlayLists=$this->plugin->Mvc->model->Node->getWithParts
			(
				[
					'id'	=>$thisPlayList[0]['id']
				]	
			);
//			$lastOrder=0;
//			if(count($allPlayLists))
//			{
//				for($i=0,$j=count($allPlayLists);$i<$j;$i++)
//				{
//					if($allPlayLists[$i]['order'] > $lastOrder)
//					{
//						$oldOrder=$allPlayLists[$i]['order'];
//					}
//				}
//			}
//			else
//			{
//				$oldOrder=0;
//			}
			
			$order=1;
			
			foreach($this->request->getAll() AS $key=>$val)
			{
				if($key=='_playlist')
				{
					continue;
				}
				$record[$key]=$val;				
			}
			
			$record['last_user_id'] = $this->plugin->Session->userId;
			$record['id'] = '';
			$record['transition_id'] = '';
			$record["node_part_id_".Efti::PLAYLIST_ORDER."_0"]=$order;

			$id=$this->plugin->Mvc->model->Node->handleRecord($record);
			
			if($id)
			{
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'OK',
					true
				);
			}
			else
			{
				$this->setResponseCode(200);
				$this->respond
				(
					true,
					'ERROR',
					false
				);
			}
			
		}
	}
}
?>