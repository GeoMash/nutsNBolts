<?php

namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\OpenBottle as OpenBottleBase;

	class OpenBottle extends OpenBottleBase
	{
        public function getCheckedOut($userId,$barId=null)
        {
            $where='';
            if($barId)
            {
                $where=<<<WHERE
AND bar_id=$barId
WHERE;

            }
            $query=<<<SQL
SELECT *
FROM open_bottle t
WHERE date_opened = (
    SELECT max(date_opened)
    FROM open_bottle
    WHERE t.parent_id = parent_id
    AND user_id=?
)
AND checked_out=1
{$where}
SQL;
            $result=$this->plugin->Db->nutsnbolts->getResultFromQuery($query,array($userId));
            return $result;
        }

        public function getCheckedIn($userId,$barId=null)
        {
            $where='';
            if($barId)
            {
                $where=<<<WHERE
AND bar_id=$barId
WHERE;

            }
            $query=<<<SQL
SELECT *
FROM open_bottle t
WHERE date_opened = (
    SELECT max(date_opened)
    FROM open_bottle
    WHERE t.parent_id = parent_id
    AND user_id=?
)
AND checked_out=0
{$where}
SQL;
            $result=$this->plugin->Db->nutsnbolts->getResultFromQuery($query,array($userId));
            return $result;
        }

        public function getBottles($userId)
        {
            $query=<<<SQL
SELECT *
FROM open_bottle t
WHERE date_opened = (
    SELECT max(date_opened)
    FROM open_bottle
    WHERE t.parent_id = parent_id
    AND user_id=?
)
SQL;
            $result=$this->plugin->Db->nutsnbolts->getResultFromQuery($query,array($userId));
            return $result;
        }

        public function getHistory($bottleId,$userId)
        {
            $query=<<<SQL
SELECT *
FROM open_bottle
WHERE bottle_id=?
AND user_id=?
SQL;
            $result=$this->plugin->Db->nutsnbolts->getResultFromQuery($query,array($bottleId,$userId));
            return $result;
        }

        public function managerRead($barId)
        {
            $barId=$barId['bar_id'];
            $query=<<<SQL
SELECT *
FROM open_bottle t
WHERE date_opened = (
    SELECT max(date_opened)
    FROM open_bottle
    WHERE t.parent_id = parent_id
    AND bar_id=?
)
SQL;
            $result=$this->plugin->Db->nutsnbolts->getResultFromQuery($query,array($barId));
            return $result;
        }

	}
}
?>