<?php
namespace application\controller
{
	use geomash\Geomash;
	use nutshell\plugin\mvc\Mvc;
	use nutshell\plugin\mvc\Controller;
	
	class CLIAPI extends Controller
	{
		public function __construct(Mvc $MVC)
		{
			if (NS_INTERFACE!='CLI')
			{
				$this->plugin->logger->error('Permission Denied while trying to access CLI controller.');
				die('Permission Denied');
			}
			parent::__construct($MVC);
		}
		
		public function createHexRing($accountID,$radius,$startOutset,$endOutset)
		{
			Geomash::getInstance()->setCLIAccountID($accountID);
			$style	=array
			(
				'line'=>array
				(
					'color'	=>'7f333333',
					'width'	=>0.3
				),
				'polygon'=>array
				(
					'color'	=>'7fff0000'
				)
			);
			$heatmap=$this->plugin->Heatmap->Hexagon;
			$heatmap->createHexRing($radius,$startOutset,$endOutset,array('style'=>$style));
		}
		
		public function runCollection($accountID,$collectionID)
		{
			Geomash::getInstance()->setCLIAccountID($accountID);
			$collection=$this->model->Collection->read(array('id'=>$collectionID));
			if ($collection[0]['mongo'])
			{
				$query	=json_decode($collection[0]['query'],true);
				$records=$this->model->{ucwords($query['collection'])}->read($query['query']);
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					unset($records[$i]['_id']);
					$records[$i]['point']=$records[$i]['point']->toArray();
				}
				$this->model->CollectionCache->create
				(
					array
					(
						'collection_id'	=>$collectionID,
						'records'		=>$records
					)
				);
			}
			else
			{
				die('Non-Mongo collections not yet supported.');
			}
		}
	}
}
?>