<?php
namespace application\nutsNBolts\base
{
	use nutshell\plugin\mvc\Mvc;
	use nutshell\plugin\mvc\Controller as MvcController;
	
	class Controller extends MvcController
	{
		private $site=null;
		
		public function __construct(Mvc $MVC)
		{
			parent::__construct($MVC);
			$this->MVC=$MVC;
			
			$result=$this->model->Site->read(array('domain'=>$_SERVER['HTTP_HOST']));
			if (isset($result[0]))
			{
				$this->site=$result[0];
			}
			else
			{
				die('No site registered for this domain!');
			}
			if (!$this->application->nutsNBolts->getSiteBinding($this->getSiteRef()))
			{
				die('No site bound for this domain!');
			}
		}
		
		public function getSite()
		{
			return $this->site;
		}
		
		public function getSiteId()
		{
			return $this->site['id'];
		}
		
		public function getSiteRef()
		{
			return $this->site['ref'];
		}
	}
}
?>