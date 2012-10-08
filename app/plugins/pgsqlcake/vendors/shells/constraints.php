<?php
 	App::import( array( 'Model', 'AppModel' ) );

	class ConstraintsShell extends Shell
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

			$Model->Behaviors->attach( 'Pgsqlcake.Schema' );
			$fkPresentes = $Model->foreignKeysFrom();
			$offsets = Set::extract( $fkPresentes, '/From/column' );

			$hr = str_pad( '-- ', 80, '-' );

			$buffer = array( $hr, "-- Ajout des contraintes pour la table {$table}.", $hr );

			foreach( $fields as $fieldName ) {
				$parentTable = Inflector::tableize( substr( $fieldName, 0, -3 ) );

				$noAction = true;
				$fkIndex = array_search( $fieldName, $offsets );
				if( $fkIndex !== false ) {
					$noAction = ( ( $fkPresentes[$fkIndex]['Foreignkey']['onupdate'] == 'NO ACTION' ) || ( $fkPresentes[$fkIndex]['Foreignkey']['onupdate'] == 'NO ACTION' ) );
				}

				if( ( $fkIndex === false || $noAction ) && isset( $Model->_schema[$fieldName] ) ) {
					if( $fkIndex === false ) {
						$buffer[] = "-- La clé étrangère n'existe pas";
					}
					else if( $noAction ) {
						$buffer[] = "-- Aucune action n'était définie pour la clé étrangère";
						$buffer[] = "ALTER TABLE {$table} DROP CONSTRAINT {$fkPresentes[$fkIndex]['Foreignkey']['name']};";
						$parentTable = $fkPresentes[$fkIndex]['To']['table'];
					}

					$buffer[] = "SELECT public.add_missing_constraint( 'public', '{$table}', '{$table}_{$fieldName}_fkey', '{$parentTable}', '{$fieldName}' ".( !$Model->_schema[$fieldName]['null'] ? '' : ', FALSE' )." );";

					/*$buffer[] = "ALTER TABLE {$table} DROP CONSTRAINT {$table}_{$fieldName}_fkey;";

					if( !$Model->_schema[$fieldName]['null'] ) {
						$buffer[] = "ALTER TABLE {$table} ADD CONSTRAINT {$table}_{$fieldName}_fkey FOREIGN KEY ({$fieldName}) REFERENCES {$parentTable}(id) ON DELETE CASCADE ON UPDATE CASCADE;";
					}
					else {
						$buffer[] = "ALTER TABLE {$table} ADD CONSTRAINT {$table}_{$fieldName}_fkey FOREIGN KEY ({$fieldName}) REFERENCES {$parentTable}(id) ON DELETE SET NULL ON UPDATE CASCADE;";
					}*/
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
				$this->out( 'Usage: ./cake/console/cake pgsqlcake.constraints <table> [-all]' );
				$this->_stop( 0 );
			}

			if( empty( $this->args ) && !isset( $this->options['all'] ) ) {
				$this->err( 'Usage: ./cake/console/cake pgsqlcake.constraints <table>' );
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