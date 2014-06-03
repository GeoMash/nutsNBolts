<?php
namespace application\nutsNBolts\widget\file
{
	use application\nutsNBolts\widget\base\Widget as WidgetBase;

	class File extends WidgetBase
	{
		public function init()
		{
			
		}
		
		public function doSomething($param1,$param2,$param3)
		{
			var_dump($param1,$param2,$param3);
			die('I did something');
		}
	}
}
?>