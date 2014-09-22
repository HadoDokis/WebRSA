<?php
	/**
	 * Code source de la classe TablesTask.
	 *
	 * PHP 5.3
	 *
	 * @package Graphviz
	 * @subpackage Console.Command.Task
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConnectionManager', 'Model' );
//	App::uses( 'Model', 'Model' );

	/**
	 * La classe TablesTask ...
	 *
	 * @package Graphviz
	 * @subpackage Console.Command.Task
	 */
	class GraphvizTablesTask extends AppShell
	{
		/**
		 *
		 * @var DataSource
		 */
		public $Dbo = null;

//		public function execute() {}

		public function initialize() {
			parent::initialize();
			$this->Dbo = ConnectionManager::getDataSource( 'default' );
		}

		public function getNames() {
			$tables = $this->Dbo->listSources();
			sort( $tables );

			$regex = Hash::get( $this->params, 'tables' );
			if( !empty( $regex ) ) {
				foreach( $tables as $key => $table ) {
					if( !preg_match( $regex, $table ) ) {
						unset( $tables[$key] );
					}
				}
			}

			return $tables;
		}

		public function getFields( $tableName ) {
			$schema = $this->Dbo->describe( $tableName );
			return array_combine( array_keys( $schema ), Hash::extract( $schema, '{s}.type' ) );
		}

		public function getRelations( $tableName ) {
			$foreignKeys = $this->Dbo->getPostgresForeignKeys(
				array( "\"From\".\"table_name\" = '{$tableName}'" )
			);

			return $foreignKeys;
		}

		public function getIndexes( $tableName ) {
			return $this->Dbo->index( $tableName );
		}

		public function getSummary( $tableName ) {
			// TODO: une fonction
//			$modelName = Inflector::classify( $tableName );
//			$Model = $object = new Model( array('name' => $modelName, 'table' => $tableName, 'ds' => 'default' ) );

			$summary = array(
				'Table' => array(
					'name' => $tableName,
					'fields' => $this->getFields( $tableName ),
					'relations' => $this->getRelations( $tableName ),
					'indexes' => $this->getIndexes( $tableName )
				)
			);

			return $summary;
		}

		public function getSummaries() {
			$summaries = array();

			$tableNames = $this->getNames();

			foreach( $tableNames as $tableName ) {
				$summaries[] = $this->getSummary( $tableName );
			}

			return $summaries;
		}
	}
?>