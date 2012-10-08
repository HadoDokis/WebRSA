<?php
	App::uses( 'AppModel', 'Model' );
	App::uses( 'XShell', 'Console/Command' );
	/**
	 *
	 */
	class ConstraintsShell extends XShell
	{

		/**
		 *
		 * @var type
		 */
		public $output = array( );

		/**
		 *
		 */
		protected function _listTableConstraints( $schema, $table ) {
			$name = Inflector::classify( $table );
			$Model = new AppModel( array( 'name' => $name, 'table' => $table, ) );

			$fields = $Model->query( "SELECT column_name FROM information_schema.columns WHERE table_schema = '{$schema}' AND table_name = '{$table}' AND column_name ~ '_id$';" );
			$fields = Set::extract( $fields, '/0/column_name' );

			$Model->Behaviors->attach( 'Pgsqlcake.Schema' );
			$fkPresentes = $Model->foreignKeysFrom();
			$offsets = Set::extract( $fkPresentes, '/From/column' );

			$hr = str_pad( '-- ', 80, '-' );

			$output = array( $hr, "-- Ajout des contraintes pour la table {$table}.", $hr );

			foreach( $fields as $fieldName ) {
				$parentTable = Inflector::tableize( substr( $fieldName, 0, -3 ) );

				$noAction = true;
				$fkIndex = array_search( $fieldName, $offsets );
				if( $fkIndex !== false ) {
					$noAction = ( ( $fkPresentes[$fkIndex]['Foreignkey']['onupdate'] == 'NO ACTION' ) || ( $fkPresentes[$fkIndex]['Foreignkey']['onupdate'] == 'NO ACTION' ) );
				}

				if( ( $fkIndex === false || $noAction ) && isset( $Model->_schema[$fieldName] ) ) {
					if( $fkIndex === false ) {
						$output[] = "-- La clé étrangère n'existe pas";
					}
					else if( $noAction ) {
						$output[] = "-- Aucune action n'était définie pour la clé étrangère";
						$output[] = "ALTER TABLE {$table} DROP CONSTRAINT {$fkPresentes[$fkIndex]['Foreignkey']['name']};";
						$parentTable = $fkPresentes[$fkIndex]['To']['table'];
					}

					$output[] = "SELECT public.add_missing_constraint( 'public', '{$table}', '{$table}_{$fieldName}_fkey', '{$parentTable}', '{$fieldName}' ".(!$Model->_schema[$fieldName]['null'] ? '' : ', FALSE' )." );";
				}
			}

			if( count( $output ) > 3 ) {
				$this->output = array_merge( $this->output, $output );
			}
		}

		/**
		 *
		 * @return type
		 */
		public function getOptionParser() {
			$parser = parent::getOptionParser();
			$parser->addArgument( 'table', array( 'help' => 'Table à analyser (all: analyse toutes les tables)', 'required' => true ) );
			return $parser;
		}

		/**
		 *
		 */
		public function main() {
			$tables = array( );
			if( $this->args[0] != 'all' ) {
				$tables[] = $this->args[0];
			}
			else {
				$tables = $this->connection->listSources();
			}
			sort( $tables );


			$this->XProgressBar->start( count( $tables ) );
			foreach( $tables as $table ) {
				$this->XProgressBar->next( 1, '<info>Table en cours d\'analyse : </info><important>'.$table.'</important>' );
				$this->_listTableConstraints( $this->connection->config['schema'], $table );
			}

			if( count( $this->output ) > 3 ) {
				$this->out();
				$this->out( $this->output );
			}
		}

	}
?>