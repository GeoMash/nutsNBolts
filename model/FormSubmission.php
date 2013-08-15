<?php
namespace application\nutsNBolts\model
{
	use application\nutsNBolts\model\base\FormSubmission as FormSubmissionBase;
	
	class FormSubmission extends FormSubmissionBase	
	{
		public function exportNewRecords($id)
		{
			$query=<<<SQL
			SELECT id,data
			FROM form_submission
			WHERE form_id=?
			AND exported=0;
SQL;
			if ($this->db->select($query,array($id)))
			{
				$records=$this->db->result('assoc');
				$return	=array();
				for ($i=0,$j=count($records); $i<$j; $i++)
				{
					$return[$i]=json_decode($records[$i]['data'],true);
					foreach ($return[$i] as $field=>$value)
					{
						if (is_array($value))
						{
							$return[$i][$field]=implode(',',$value);
						}
					}
				}
				$ids	=implode(',',array_column($records,'id'));
				/*
				In case any inserts happen between the above query
				and this next one, best to just update the records we have.
				 */
				$query=<<<SQL
				UPDATE form_submission
				SET exported=1
				WHERE id IN({$ids})
SQL;
				$this->db->update($query);
				return $return;
			}
			return array();
		}
	}
}
?>