<?php
namespace application\nutsNBolts\widget\image
{
	use application\nutsNBolts\widget\base\Widget as WidgetBase;

	class Image extends WidgetBase
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