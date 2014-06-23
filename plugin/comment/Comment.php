<?php
namespace application\nutsNBolts\plugin\comment
{
	use nutshell\Nutshell;
	use nutshell\behaviour\Singleton;
	use nutshell\core\plugin\Plugin;

	class Comment extends Plugin implements Singleton
	{
		private $db		=null;
		private $model	=null;

		public function init()
		{
			if ($connection=Nutshell::getInstance()->config->plugin->Mvc->connection)
			{
				$this->db	=$this->plugin->Db->{$connection};
				$this->model=$this->plugin->Mvc->model;
			}
		}

		public function getComments($nodeId,$limit=50,$order=' ORDER BY id DESC')
		{
			return $this->model->NodeComment->read(array('node_id'=>$nodeId),array(),$order.' LIMIT '.$limit);
		}
		
		public function getCommentsWithUserDetails($nodeId,$limit,$order=' ORDER BY id DESC')
		{
			$comments=$this->getComments($nodeId,$limit, $order);
			
			for($i=0,$j=count($comments);$i<$j;$i++)
			{
				$user=$this->model->User->read(['id'=>$comments[$i]['user_id']]);
				$picture=null;
				if($user[0])
				{
					if($user[0]['image'])
					{
						$picture=$user[0]['image'];
					}
					else
					{
						$picture='/admin/images/avatars/default.jpg';
					}
					
					$comments[$i]['picture']=$picture;
				}
			}
			
			return $comments;
		}
	}
}