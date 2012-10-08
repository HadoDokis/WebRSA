<?php
 	App::import( array( 'Model', 'AppModel' ) );

	class IndexesShell extends Shell
	{
		/**
		*
		*/
		public $options = array(
			'all' => false,
		);

		/**
		*
		*/
		protected function _listTableConstraints( $schema, $table ) {
			$name = Inflector::classify($table);
			$Model = new AppModel(array( 'name' => $name, 'table' => $table, ));

			$fields = $Model->query( "SELECT column_name FROM information_schema.columns WHERE table_schema = '{$schema}' AND table_name = '{$table}' AND column_name ~ '_id$';" );
			$fields = Set::extract( $fields, '/0/column_name' );

			$indexes = $Model->getDataSource( $Model->useDbConfig )->index( $Model );
			$indexes = Set::classicExtract( $indexes, '{s}.column' );

			/*$Model->Behaviors->attach( 'Pgsqlcake.Schema' );
			$fkPresentes = $Model->foreignKeysFrom();
			$offsets = Set::extract( $fkPresentes, '/From/column' );*/

			$hr = str_pad( '-- ', 80, '-' );

			$buffer = array( $hr, "-- Ajout des indexes pour la table {$table}.", $hr );

			foreach( $fields as $fieldName ) {
				if( empty( $indexes ) || !in_array( $fieldName, $indexes ) ) {
					$buffer[] = "DROP INDEX IF EXISTS {$table}_{$fieldName}_idx;";
					$buffer[] = "CREATE INDEX {$table}_{$fieldName}_idx ON {$table}( {$fieldName} );";
				}
			}

			if( count( $buffer ) > 3 ) {
				$this->out( $buffer );
			}
		}

		/**
		*
		*/
		public function main() {
			if ($this->args && $this->args[0] == '?') {
				$this->out( 'Usage: ./cake/console/cake pgsqlcake.indexes <table> [-all]' );
				$this->_stop( 0 );
			}

			if( empty( $this->args ) && !isset( $this->options['all'] ) ) {
				$this->err( 'Usage: ./cake/console/cake pgsqlcake.indexes <table>' );
				$this->_stop( 1 );
			}

			foreach( $this->params as $key => $val ) {
				foreach( $this->options as $name => $option ) {
					if( isset($this->params[$name]) || isset($this->params['-'.$name]) || isset($this->params[$name{0}]) ) {
						$this->options[$name] = true;
					}
				}
			}

			$db = ConnectionManager::getDataSource('default');
			if( $this->options['all'] ) {
				$this->args = $db->listSources();
			}
			sort( $this->args );

			foreach( $this->args as $table ) {
				$this->_listTableConstraints( $db->config['schema'], $table );
			}
		}
	}
?>