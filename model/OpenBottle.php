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

        public function getBottles($userId,$barId)
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
    {$where}
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

        public function viewExpiring($barId,$duration,$expireIn)
        {
            $query=<<<SQL
            SELECT *
            FROM open_bottle ob
            WHERE id=parent_id
            AND date_opened BETWEEN DATE_SUB(SYSDATE(), INTERVAL {$duration}-1 DAY) AND DATE_SUB(SYSDATE(), INTERVAL {$duration}-{$expireIn} DAY)
            AND
            (
                (
                    SELECT checked_out
                    FROM open_bottle t
                    WHERE ob.id = t.id
                    AND date_opened =
                    (
                        SELECT max(date_opened)
                        FROM open_bottle
                        WHERE t.id = id
                    )
                    AND bar_id={$barId}
                ) = 0
            OR
                (
                    SELECT date_opened
                    FROM open_bottle t
                    WHERE ob.id = t.id
                    AND date_opened =
                    (
                        SELECT max(date_opened)
                        FROM open_bottle
                        WHERE t.id = id
                    )
                    AND bar_id={$barId}
                ) < DATE_SUB(SYSDATE(), INTERVAL 2 DAY)
            )
SQL;


            $result=$this->plugin->Db->nutsnbolts->getResultFromQuery($query,array($barId));
            return $result;
        }

        public function viewExpired($barId,$duration)
        {
            $query=<<<SQL
            SELECT *
            FROM open_bottle ob
            WHERE id=parent_id
            AND date_opened < DATE_SUB(SYSDATE(), INTERVAL {$duration} DAY)
            AND
            (
                (
                    SELECT checked_out
                    FROM open_bottle t
                    WHERE ob.id = t.id
                    AND date_opened =
                    (
                        SELECT max(date_opened)
                        FROM open_bottle
                        WHERE t.id = id
                    )
                    AND bar_id={$barId}
                ) = 0
            OR
                (
                    SELECT date_opened
                    FROM open_bottle t
                    WHERE ob.id = t.id
                    AND date_opened =
                    (
                        SELECT max(date_opened)
                        FROM open_bottle
                        WHERE t.id = id
                    )
                    AND bar_id={$barId}
                ) < DATE_SUB(SYSDATE(), INTERVAL 2 DAY)
            )
SQL;
            $result=$this->plugin->Db->nutsnbolts->getResultFromQuery($query,array($barId));
            return $result;
        }

        public function cronGetAllBottles($duration,$expireIn,$type)
        {
            if($type=="expired")
            {
                $subQuery=$duration;
            }
            else
            {
                $subQuery=$duration-$expireIn;
            }

            $query=<<<SQL
            SELECT *
            FROM open_bottle ob
            WHERE id=parent_id
            AND (DATE_FORMAT(date_opened, "%Y-%m-%d")) = DATE_SUB(DATE_FORMAT(SYSDATE(), "%Y-%m-%d"), INTERVAL {$subQuery} DAY)
            AND
            (
                (
                    SELECT checked_out
                    FROM open_bottle t
                    WHERE ob.id = t.id
                    AND date_opened =
                    (
                        SELECT max(date_opened)
                        FROM open_bottle
                        WHERE t.id = id
                    )
                ) = 0
            OR
                (
                    SELECT date_opened
                    FROM open_bottle t
                    WHERE ob.id = t.id
                    AND date_opened =
                    (
                        SELECT max(date_opened)
                        FROM open_bottle
                        WHERE t.id = id
                    )
                ) < DATE_SUB(SYSDATE(), INTERVAL 2 DAY)
            )
SQL;
            $result=$this->plugin->Db->nutsnbolts->getResultFromQuery($query);
            return $result;
        }
    }


}
?>