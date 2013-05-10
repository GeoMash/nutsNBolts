<?php
namespace application\controller
{
	use nutshell\Nutshell;
	use nutshell\plugin\mvc\Controller;
	use nutshell\plugin\formatParser\FormatParserBase;
	use application\plugin\kml\Kml;
	use application\plugin\shape\Polygon as PolygonShape;
	
	class Upload extends Controller
	{
		public function index()
		{
			
		}
		
		public function import()
		{
			$this->plugin->Plupload->setCallback(array($this,'importUploadComplete'));
			$this->plugin->Plupload->upload();
		}
		
		public function importUploadComplete($basename, $thumbnailMaker)
		{
			$completed_dir	=Nutshell::getInstance()->config->plugin->Plupload->completed_dir;
			$kmlDoc			=Kml::fromFile($completed_dir.$basename);
			$locations		=$kmlDoc->getLocations();
			$polygons		=$kmlDoc->getPolygons();
			
			//Import Locations
			for ($i=0,$j=count($locations); $i<$j; $i++)
			{
				$locations[$i]['point']	=$locations[$i]['point']->toArray();
				// $locations[$i]['tags']	=?????;
				$this->model->Location->create($locations[$i]);
			}
			//Import Polygons
			for ($k=0,$l=count($polygons); $k<$l; $k++)
			{
				
				$shape=new PolygonShape
				(
					$polygons[$k]['points'],
					$polygons[$k]['inner']
				);
				$shape->setDescription($polygons[$k]['description']);
				$shape->setAddress($polygons[$k]['address']);
				// $shape->setTags(array('?????'));
				$this->model->Polygon->create($shape);
			}
			unset($kmlDoc);
			
		}
		
		// public function import($type,$file)
		// {
		// 	try {
		// 		$db = $this->plugin->Db->mongodb->selectDb($type);
				
		// 		$csvParser = $this->plugin->FormatParser('Csv', array(
		// 			'post_headers_read' => array($this, 'recordHeadersMapping')
		// 		));
				
		// 		// $csvParser->setRecordDelimiter(FormatParserBase::LINE_ENDING_WIN);
				
		// 		if($content = $csvParser->parseFromFile($file))
		// 		{
		// 			var_dump($content);exit();
		// 			foreach($content as $entry)
		// 			{
		// 				$this->trimValues($entry);
		// 				$db->save($type, $entry);
		// 			}
		// 		}
		// 	}
		// 	catch(\Exception $e)
		// 	{
		// 		print $e->__toString();
		// 	}
		// }
		
		// public function importLocations($accountID,$file,$latColumn,$lngColumn,$tags=array())
		// {
		// 	try {
				
				
		// 		$csvParser = $this->plugin->FormatParser('Csv', array(
		// 			'post_headers_read' => array($this, 'recordHeadersMapping')
		// 		));
				
		// 		// $csvParser->setRecordDelimiter(FormatParserBase::LINE_ENDING_WIN);
				
		// 		if($content = $csvParser->parseFromFile($file))
		// 		{
		// 			$tags=explode(',',$tags);
		// 			foreach($content as $entry)
		// 			{
						
		// 				$this->trimValues($entry);
		// 				$entry['account_id']=(int)$accountID;
		// 				$entry['tags']		=$tags;
		// 				$entry['point']		=array((float)$entry['lng'],(float)$entry['lat']);
		// 				unset($entry['lat']);
		// 				unset($entry['lng']);
		// 				$this->model->Location->create($entry);
		// 			}
		// 		}
		// 	}
		// 	catch(\Exception $e)
		// 	{
		// 		print $e->__toString();
		// 	}
		// }

		// public function recordHeadersMapping($originalHeaders)
		// {
		// 	$db = $this->plugin->Db->mongodb;

		// 	$mappings = array();

		// 	foreach($originalHeaders as $header)
		// 	{
		// 		// define the mapping object that will be registered in mongo
		// 		$mapping = array(
		// 			'_id' => $this->sanitizeHeader($header), 
		// 			'label' => $header
		// 		);


		// 		$db->save('header_mappings', $mapping);
		// 		$mappings[] = $mapping['_id'];
		// 	}
		// 	return $mappings;
		// }

		// protected function sanitizeHeader($header)
		// {
		// 	return preg_replace(
		// 		array(
		// 			'/[^a-zA-Z0-9_]/',
		// 			'/_{2,}/'
		// 		),
		// 		'_', 
		// 		$header
		// 	);
		// }

		// protected function trimValues(&$content)
		// {
		// 	foreach($content as $key => $value)
		// 	{
		// 		$content[$key] = trim($value);
		// 	}
		// }
	}
}
?>