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
	}
}
?>