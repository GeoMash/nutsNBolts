<?php
/**
 * 
 * 
 * @todo Handle user permissions.
 */
namespace application\nutsNBolts\plugin\search
{
	use application\nutsNBolts\plugin\search\exception\SearchException;
	use nutshell\behaviour\Native;
	use nutshell\behaviour\Factory;
	use application\nutsNBolts\base\Plugin;
	use nutshell\Nutshell;
	use DateTime;

	class Search extends Plugin implements Factory, Native
	{
		const TABLE_NAME_PREFIX='search_';
		const ORDER_ASCENDING	='ASC';
		const ORDER_DECENDING	='DESC';

		private $db					=null;
		private $contentType		=null;
		private $contentTypeJoins	=[];
		private $name				=null;
		private $filter				=[];
		private $limit				=100;
		private $offset				=0;
		private $orderBy			=[];
		
		public function init($contentType)
		{
			if ($connection=Nutshell::getInstance()->config->plugin->Mvc->connection)
			{
				//Make a shortcut reference to the
				$this->db=$this->plugin->Db->{$connection};
			}
			if (is_string($contentType))
			{
				$this->contentType=$this->model->ContentType->read(['ref'=>$contentType]);
			}
			else if (is_numeric($contentType))
			{
				$this->contentType=$this->model->ContentType->read($contentType);
			}
			if (isset($this->contentType[0]))
			{
				$this->contentType=$this->contentType[0];
			}
			else
			{
				die('TODO - Exception 1');
			}
			
		}
		
		public function getDb()
		{
			return $this->db;
		}
		
		public function joinWithContentType($contentType,$joinHow)
		{
			if (is_string($contentType))
			{
				$contentType=$this->model->ContentType->read(['ref'=>$contentType]);
			}
			else if (is_numeric($contentType))
			{
				$contentType=$this->model->ContentType->read($contentType);
			}
			if (isset($contentType[0]))
			{
				$this->contentTypeJoins[]=[$contentType[0],$joinHow];
			}
			else
			{
				die('TODO - Exception 2');
			}
			return $this;
		}
		
		public function limit($num)
		{
			$this->limit=$num;
			return $this;
		}
		
		public function offset($num)
		{
			$this->offset=$num;
			return $this;
		}
		
		public function orderBy()
		{
			unset($this->orderBy);
			$this->orderBy=[];
			$args=func_get_args();
			if (count($args))
			{
				if (!is_array($args[0]))
				{
					$this->orderBy[$args[0]]=$args[1];
				}
				else
				{
					$this->orderBy=$args[0];
				}
			}
			return $this;
		}
		
		public function execute()
		{
//			$userId		=$this->plugin->Auth->getUserId();
			$tableName	=self::TABLE_NAME_PREFIX.$this->contentType['ref'];
			
			if (!$this->cacheExists($tableName))
			{
				$nodes=$this->model->Node->getWithParts(['content_type_id'=>$this->contentType['id']]);
				if (isset($nodes[0]))
				{
					$this->createTable($this->contentType,$nodes);
				}
				//No results
				else
				{
					return [];
				}
			}
			
			//Joins
			if (count($this->contentTypeJoins))
			{
				$joins=[];
				for ($i=0,$j=count($this->contentTypeJoins); $i<$j; $i++)
				{
					$thisTableName=self::TABLE_NAME_PREFIX.$this->contentTypeJoins[$i][0]['ref'];
					if (!$this->cacheExists($thisTableName))
					{
						$nodes=$this->model->Node->getWithParts(['content_type_id'=>$this->contentTypeJoins[$i][0]['id']]);
						if (isset($nodes[0]))
						{
							$this->createTable($this->contentTypeJoins[$i][0],$nodes);
						}
						//No results - Nullify the join.
						else
						{
							$this->contentTypeJoins[$i]=null;
						}
					}
					if (!is_null($this->contentTypeJoins[$i]))
					{
						$fromName	=self::TABLE_NAME_PREFIX.$this->contentType['ref'];
						$toName		=self::TABLE_NAME_PREFIX.$this->contentTypeJoins[$i][0]['ref'];
						$joins[]='LEFT JOIN `'.$toName.'` ON `'.$toName.'`.`'.$this->contentTypeJoins[$i][1].'`=`'.$fromName.'`.`id`';
					}
				}
				$joins=implode("\n",$joins);
			}
			else
			{
				$joins='';
			}
			//Now build the actual search query.
			
			//Filtering
			$values=[];
			if (count($this->filter))
			{
				$where=[];
				foreach ($this->filter as $column=>$value)
				{
					if (strtolower(substr($value,0,4))=='like')
					{
						$where[]='`'.$tableName.'`.`'.$column.'` '.$value;
						continue;
					}
					else if (in_array(substr($value,0,1),['=','<','>']))
					{
						$operand	=substr($value,0,1);
						$values[]	=substr($value,1);
					}
					else if (in_array(substr($value,0,2),['!=','<>']))
					{
						$operand	=substr($value,0,2);
						$values[]	=substr($value,2);
					}
					else
					{
						$operand	='=';
						$values[]	=$value;
					}
					$where[]='`'.$tableName.'`.`'.$column.'`'.$operand.'?';
				}
				$where='WHERE '.implode(' AND ',$where);
			}
			else
			{
				$where='';
			}
			//Ordering
			$orderBy=[];
			foreach ($this->orderBy as $column=>$direction)
			{
				$orderBy[]='`'.$tableName.'`.`'.$column.'` '.$direction;
			}
			if (count($orderBy))
			{
				$orderBy='ORDER BY '.implode(',',$orderBy);
			}
			else
			{
				$orderBy='';
			}
			
			$query=<<<SQL
SELECT *
FROM `{$tableName}`
{$joins}
{$where}
{$orderBy}
LIMIT {$this->offset},{$this->limit};
SQL;
			$this->getDb()->query($query,$values);
			return $this->getDb()->result('assoc');
		}
		
		public function clearCache()
		{
			$name=self::TABLE_NAME_PREFIX.$this->contentType['ref'];
			$this->getDb()->query('DROP TABLE IF EXISTS `'.$name.'`;');
			for ($i=0,$j=count($this->contentTypeJoins); $i<$j; $i++)
			{
				$name=self::TABLE_NAME_PREFIX.$this->contentTypeJoins[$i][0]['ref'];
				$this->getDb()->query('DROP TABLE IF EXISTS `'.$name.'`;');
			}
			return $this;
		}
		
		public function addFilter(Array $keyVals)
		{
			foreach ($keyVals as $column=>$value)
			{
				$this->filter[$column]=$value;
			}
			return $this;
		}
		
		public function removeFilter($column)
		{
			unset($this->filter[$column]);
			return $this;
		}
		
		public function clearFilter()
		{
			unset($this->filter);
			$this->filter=[];
			return $this;
		}
		
		private function cacheExists($name)
		{
			$this->getDb()->query('SHOW TABLES LIKE ?',$name);
			$result=$this->getDb()->result('indexed');
			if (isset($result[0][0]))
			{
				return ($result[0][0]==$name);
			}
			return false;
		}
		
		private function createTable($contentType,Array &$nodes)
		{
			$keys		=array_keys($nodes[0]);
			$columns	=[];
			
			//Map out columns
			for ($i=0,$j=count($keys); $i<$j; $i++)
			{
				if ($keys[$i]=='id')
				{
					$columns[]='`id` INT(10) NOT NULL';
				}
				else if (substr($keys[$i],-3,3)=='_id')
				{
					$columns[]='`'.$keys[$i].'` INT(10) NULL';
				}
				else if ($keys[$i]=='order')
				{
					$columns[]='`order` INT(5) NULL DEFAULT "0"';
				}
				else if ($keys[$i]=='status')
				{
					$columns[]='`status` TINYINT(1) NULL DEFAULT "0"';
				}
				else if ($keys[$i]=='title')
				{
					$columns[]='`title` VARCHAR(100) NULL';
				}
				else if (substr($keys[$i],0,4)=='date')
				{
					$columns[]='`'.$keys[$i].'` TIMESTAMP NULL DEFAULT "0000-00-00 00:00:00"';
				}
				else
				{
					$columns[]='`'.$keys[$i].'` TEXT';
				}
			}
			
			//Create the table.
			$columns	=implode(",\n",$columns);
			$tableName	=self::TABLE_NAME_PREFIX.$contentType['ref'];
			$query=<<<SQL
CREATE TABLE `{$tableName}`
(
	{$columns},
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
			$this->getDb()->query($query);
			
			//Insert all nodes into table.
			$keys				=array_keys($nodes[0]);
			$columnCount		=count($keys);
			$columns			='`'.implode('`,`',$keys).'`';
			$valuePlaceholders	=rtrim(str_repeat('?,',$columnCount),',');
			$query				=<<<SQL
			INSERT INTO `{$tableName}`
			({$columns})
			VALUES
			({$valuePlaceholders});
SQL;
			for ($i=0,$j=count($nodes); $i<$j; $i++)
			{
				$values=array_values($nodes[$i]);
				$this->sanitizeValues($values);
				$this->getDb()->query($query,$values);
			}
			return $this;
		}
		
		private function getContentTypeRef($contentTypeId)
		{
			$query=<<<SQL
SELECT ref
FROM content_type
WHERE id=?;
SQL;
			$this->getDb()->query($query);
			$result=$this->getDb()->result('assoc');
			if (isset($result[0]))
			{
				
			}
			return false;
		}
		
		private function sanitizeValues(Array &$values)
		{
			for ($i=0,$j=count($values); $i<$j; $i++)
			{
				if ($values[$i] instanceof DateTime)
				{
					$values[$i]=$values[$i]->format('Y-m-d H:i:s');
				}
			}
		}
	}
}
