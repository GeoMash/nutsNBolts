<?php
namespace application\nutsNBolts\controller
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use nutshell\plugin\mvc\Controller;
	use application\plugin\blog\Blog;
	
	class Sandbox extends Controller
	{
		public function index()
		{
			echo '<pre>';
		

			$dates=$this->plugin->Blog->getAllDates(2);

			$allDates=array();
			$countedDates=array();
			foreach($dates AS $key=>$date)
			{
				$allDates[]=$newDate=$date['date_created']->format("F Y");
			}

			foreach($allDates AS $key=>$secondDate)
			{
				if (isset($countedDates[$secondDate]))
				{
					$countedDates[$secondDate]+=1;
				}
				else
				{
					$countedDates[$secondDate]=1;
				}
			}

			print_r($countedDates);
			return $countedDates;
		}
	}
}
?>
