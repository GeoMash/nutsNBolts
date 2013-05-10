<?php
namespace application\model\common
{
	use application\plugin\mvcQuery\MvcQuery;

	use application\helper\SecurityHelper;

	abstract class Base extends MvcQuery
	{
		const ACCOUNT_ID_COLUMN_KEY = 'account_id';

		private $hasAccountId = null;

		public $insertType = self::INSERT_ASSOC;

		private function hasAccountId()
		{
			if(is_null($this->hasAccountId)) {
				$this->hasAccountId = isset($this->columns[self::ACCOUNT_ID_COLUMN_KEY]);
			}
			return $this->hasAccountId;
		}

		public function read($whereKeyVals = array(), $readColumns = array(), $additionalPartSQL='', $mvcQueryObject=null)
		{
			$accountId = SecurityHelper::getAccountId();
			// allows members of the Admin account to manage any resource
			if(!is_numeric($accountId)) {$accountId = 0;}

			if($this->hasAccountId() && is_array($whereKeyVals) && $accountId > 0) {
				$whereKeyVals[self::ACCOUNT_ID_COLUMN_KEY] = $accountId;
			}
			return parent::read($whereKeyVals, $readColumns, $additionalPartSQL, $mvcQueryObject);
		}

		public function update($updateKeyVals, $whereKeyVals, $additionalPartSQL='')
		{
			$accountId = SecurityHelper::getAccountId();
			// allows members of the Admin account to manage any resource
			if(!is_numeric($accountId)) {$accountId = 0;}

			if($this->hasAccountId() && is_array($whereKeyVals) && $accountId > 0) {
				$whereKeyVals[self::ACCOUNT_ID_COLUMN_KEY] = $accountId;
			}
			if($this->hasAccountId() && is_array($updateKeyVals) && $accountId > 0) {
				$updateKeyVals[self::ACCOUNT_ID_COLUMN_KEY] = $accountId;
			}

			return parent::update($updateKeyVals,$whereKeyVals, $additionalPartSQL);
		}

		public function insert($record, $fields=array())
		{
			$accountId = SecurityHelper::getAccountId();
			// allows members of the Admin account to manage any resource
			if(!is_numeric($accountId)) {$accountId = 0;}

			if($this->hasAccountId() && $accountId > 0) {
				$index = array_search(self::ACCOUNT_ID_COLUMN_KEY, $fields);
				if($index !== false) {
					$record[$index] = $accountId;
				} else {
					$fields[] = self::ACCOUNT_ID_COLUMN_KEY;
					$record[] = $accountId;
				}
			}

			return parent::insert($record, $fields);
		}

		public function insertAssoc($record)
		{
			$accountId = SecurityHelper::getAccountId();
			// allows members of the Admin account to manage any resource
			if(!is_numeric($accountId)) {$accountId = 0;}

			if($this->hasAccountId() && $accountId > 0) {
				$record[self::ACCOUNT_ID_COLUMN_KEY] = $accountId;
			}

			return parent::insertAssoc($record);
		}

		public function delete($whereKeyVals, $mvcQueryObject=null)
		{
			$accountId = SecurityHelper::getAccountId();
			// allows members of the Admin account to manage any resource
			if(!is_numeric($accountId)) {$accountId = 0;}

			if($this->hasAccountId() && is_array($whereKeyVals) && $accountId > 0) {
				$whereKeyVals[self::ACCOUNT_ID_COLUMN_KEY] = $accountId;
			}

			return parent::delete($whereKeyVals, $mvcQueryObject);
		}
	}
}