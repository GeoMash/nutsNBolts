<?php
namespace application\controller
{
	use application\helper\CryptoHelper;

	use nutshell\Nutshell;
	use nutshell\helper\StringHelper;
	use nutshell\plugin\mvc\Controller;
	use nutshell\core\exception\NutshellException;
	
	class Dev extends Controller
	{
		private function guard()
		{
			if (NS_ENV == 'production' && NS_INTERFACE != 'CLI')
			{
				die('Nice try Dave.');
			}
		}

		public function index()
		{
			echo "No.\n";
			exit;
		}
		
		public function generateModels()
		{
			$this->guard();

			// Get list of Table Names
			$query = "SELECT `TABLE_NAME` FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` = ?";
			$tables = $this->plugin->MvcQuery->db->getResultFromQuery($query, Nutshell::getInstance()->config->plugin->Db->connections->{Nutshell::getInstance()->config->plugin->Mvc->connection}->database);
			$models = array();
			foreach($tables as $tableData)
			{
				$tableName = $tableData['TABLE_NAME'];
				$modelName = StringHelper::formatModelName($tableName);
				$models[$tableName] = $modelName;
			}
			
			// Get the Model Generator, configure it
			$generator = $this->plugin->ModelGenerator;
			$generator->setBaseClass('Base');
			$generator->setBaseClassNamespace('application\\model\\common\\');
			
			// Define the location to put the generated models
			$modelsFolder = APP_HOME . _DS_ . 'model';
			$subfolder = _DS_ . 'base';
			
			foreach($models as $tableName => $modelName)
			{
				$fileContents = $generator->getModelStrFromTable($tableName, $subfolder, $modelName);
				file_put_contents($modelsFolder.$subfolder._DS_.$modelName.'.php', $fileContents);
				echo "$tableName -- $modelName\n";
			}
			
			exit;
		}

		public function reset()
		{
			$this->guard();
			try {
				printf("Resetting the database...\n");

				$files = array('schema', 'permissions');
				foreach($files as $dbFile)
				{
					printf("Processing %s...\n", $dbFile);
					if($query = file_get_contents(sprintf('%s../../doc/%s.sql', APP_HOME, $dbFile)))
					{
						$this->plugin->MvcQuery->db->getResultFromQuery($query, Nutshell::getInstance()->config->plugin->Db->connections->{Nutshell::getInstance()->config->plugin->Mvc->connection}->database);
					}
				}

				printf("Database reinitialised.\n");
			}
			catch(\Exception $e)
			{
				echo $e->__toString();
			}
		}

		public function pwd($password = null)
		{
			$this->guard();
			try {
				if(!$password)
				{
					$password = CryptoHelper::getInstance()->getRandomString(8);	
				}
				$salt = CryptoHelper::getInstance()->getRandomString(40);
				$pwd = sha1($salt . $password);
				printf("password (cleartext): %s\npwd (hashed): %s\nsalt: %s\n", $password, $pwd, $salt);
			}
			catch(\Exception $e)
			{
				echo $e->__toString();
			}
		}

		private function testData()
		{
			$this->guard();
			try {
				printf("Preparing test DB...\n");

				$files = array('schema', 'testData.sql');
				foreach($files as $dbFile)
				{
					printf("Processing %s...\n", $dbFile);
					if($query = file_get_contents(sprintf('%s../../doc/%s.sql', APP_HOME, $dbFile)))
					{
						$this->plugin->MvcQuery->db->getResultFromQuery($query, Nutshell::getInstance()->config->plugin->Db->connections->{Nutshell::getInstance()->config->plugin->Mvc->connection}->database);
					}
				}

				printf("Test DB installed.\n");
			}
			catch(\Exception $e)
			{
				echo $e->__toString();
			}
		}
	}
}
