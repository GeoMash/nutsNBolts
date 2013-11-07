<?php
namespace application\nutsNBolts\controller\rest
{
	use nutshell\Nutshell;
	use nutshell\core\exception\NutshellException;
	use application\nutsNBolts\base\Controller;

	class Index extends Controller
	{
		const FORMAT_DEFAULT='json';

		private $restController=null;

		public function index()
		{
			$request		=$this->request->getNodes();
			$lastIndex		=count($request)-1;
			$latNodeParts	=explode('.',$request[$lastIndex]);
			if (isset($latNodeParts[1]))
			{
				$format=$latNodeParts[1];
				$request[$lastIndex]=str_replace('.'.$format,'',$request[$lastIndex]);
			}
			else
			{
				$format=self::FORMAT_DEFAULT;
			}
			//Check for action.
			$action	=isset($request[2])?$request[2]:null;
			$args	=array();
			$node	=3;
			//Check for args.
			if (!is_null($action))
			{
				while (true)
				{
					//grab the next node
					$arg = $request($node++);

					if(is_null($arg))
					{
						break;
					}
					//append to the args array
					$args[] = $arg;
				}
			}
			else
			{
				$action='index';
			}
			switch ($request[1])
			{
				case 'node':	$this->restController=new User($this->MVC,$format,$request);	break;
				case 'user':	$this->restController=new Node($this->MVC,$format,$request);	break;
			}
			call_user_func_array
			(
				array($this->restController,$action),
				$args
			);
		}
	}
}
?>