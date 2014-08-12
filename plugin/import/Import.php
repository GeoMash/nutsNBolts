<?php
namespace application\nutsNBolts\plugin\import
{
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;

	class Import extends Plugin implements Singleton
	{
		public static function registerBehaviours(){}
		
		private $model=null;
	
		public function init()
		{
			$this->model=$this->plugin->Mvc->model;
		}
		
		public function fromFile($file,$dataType)
		{
			$csvParser=$this->plugin->FormatParser('Csv');
			if ($content=$csvParser->parseFromFile($file))
			{
				switch ($dataType)
				{
					case 'full':
					{
						
						break;
					}
					case 'users':
					{
						for ($i=0,$j=count($content); $i<$j; $i++)
						{
							$user=$this->model->User->read
							(
								['email'=>$content[$i]['email']]
							);
							if (isset($user[0]))
							{
								//TODO: Improve condition so that it returns a conflict in the below case.
//								if (isset($content[$i]['id']) && $content[$i]['id']!=$user[0]['id'])
//								{
//									
//								}
								$content[$i]['id']=$user[0]['id'];
							}
							$this->model->User->handleRecord($content[$i]);
						}
						break;
					}
				}
			}
			return $content;
		}
	}
}