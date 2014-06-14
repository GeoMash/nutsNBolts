<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\NodeComment as NodeCommentBase;
	use \DateTime;

	class NodeComment extends NodeCommentBase	
	{
		public function read($whereKeyVals = array(), $readColumns = array(), $additionalPartSQL='')
		{
			$result=parent::read($whereKeyVals, $readColumns, $additionalPartSQL);
			for ($i=0,$j=count($result); $i<$j; $i++)
			{
				$result[$i]['date_created']=new DateTime($result[$i]['date_created']);
			}
			return $result;		
		}
	}
}
?>