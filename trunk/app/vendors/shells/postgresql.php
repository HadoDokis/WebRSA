<?php
	// CakePHP 1.2 fix
	App::import( 'Core', 'ConnectionManager' );

    class PostgresqlShell extends Shell
    {
		protected $_method = null;
		protected $_outfile = null;
		protected $_output = '';
		protected $_script = null;

        /**
        *
        */

        public function err( $string = null ) {
			parent::err( $string );

			if( !empty( $this->_outfile ) ) {
				$this->_output .= "Erreur: {$string}\n";
			}
		}

        /**
        *
        */

        public function out( $string = null ) {
			parent::out( $string );

			if( !empty( $this->_outfile ) ) {
				$this->_output .= "{$string}\n";
			}
		}

        /**
        *
        */

        public function exportlog() {
			file_put_contents( $this->_outfile, $this->_output );
		}

        /**
        *
        */

        protected function _connections() {
			return array_keys( ConnectionManager::enumConnectionObjects() );
		}

        /**
        *
        *
        */

        public function startup() {
			$this->_script = strtolower( preg_replace( '/Shell$/', '', $this->name ) );

            if( count( $this->args ) > 0 ) {
                $connection = $this->args[0];
            }

            if( count( $this->args ) > 1 ) {
                $this->_method = $this->args[1];
            }

			if( ( count( $this->args ) == 0 ) || Set::classicExtract( $this->params, 'help' ) == true ) {
				$this->_help();
				exit( 0 );
			}

			/// Nom du fichier et titre de la page
			$this->_outfile = sprintf( '%s-%s-%s.log', $this->_script, $this->_method, date( 'Ymd-His' ) );
			$this->_outfile = APP_DIR.'/tmp/logs/'.$this->_outfile;

			if( !in_array( $connection, $this->_connections() ) ) {
				$this->err( "La connection {$connection} n'existe pas dans votre database.php (utilisez une valeur parmi ".implode( ", ", $this->_connections() ).")" );
				exit( 1 );
			}

			$subTasks = array();
			foreach( get_class_methods( $this ) as $method ) {
				if( preg_match( '/^_subtask(.*)$/', $method, $matches ) ) {
					$subTasks[] = Inflector::underscore( $matches[1] );
				}
			}

			$this->_method = '_subtask'.Inflector::camelize( $this->_method );
			if( empty( $this->_method ) || !method_exists( $this, $this->_method ) ) {
				$this->err( "Le paramètre {$this->args[1]} est incorrect (utilisez une valeur parmi ".implode( ", ", $subTasks ).")" );
				exit( 1 );
			}

			$this->conn = ConnectionManager::getDataSource( $connection );
			if( $this->conn->config['driver'] != 'postgres' ) {
				$this->err( "La connection {$connection} n'utilise pas le driver postgres" );
				exit( 1 );
			}
        }

        /**
        * Met à jour les séquences de l'ensemble de la base de données
        */

        protected function _subtaskSequences() {
			$this->out( 'BEGIN;' );
			$this->conn->query( 'BEGIN;' );

			$success = true;

			$sql = "SELECT table_name AS \"Model__table\",
						column_name	AS \"Model__column\",
						column_default AS \"Model__sequence\"
						FROM information_schema.columns
						WHERE table_schema = 'public'
							AND column_default LIKE 'nextval(%::regclass)'
						ORDER BY table_name, column_name";

			foreach( $this->conn->query( $sql ) as $model ) {
				$sequence = preg_replace( '/^nextval\(\'(.*)\'.*\)$/', '\1', $model['Model']['sequence'] );
				$sql = "SELECT setval('{$sequence}', max({$model['Model']['column']})) FROM {$model['Model']['table']};";
				$result = $this->conn->query( $sql );
				$success = $result && $success;
				$this->out( "$sql\t-- {$result[0][0]['setval']}" );
			}

			if( $success ) {
				$this->out( 'COMMIT;' );
				$this->conn->query( 'COMMIT;' );
				return true;
			}
			else {
				$this->err( 'ROLLBACK;' );
				$this->conn->query( 'ROLLBACK;' );
				return false;
			}
		}

        /**
        *
        */

        public function main() {
			$success = call_user_func_array( array( $this, $this->_method ), array_slice( $this->args, 2 ) );

			$this->hr();

            if( $success ) {
                $this->out( "Script terminé avec succès" );
            }
            else {
                $this->out( "Script terminé avec erreurs" );
            }

			$this->exportlog();
			$this->out( "Le fichier de log se trouve dans {$this->_outfile}" );

			exit( ( $success ? 0 : 1 ) );
        }

		/**
		* Displays help contents
		*
		* @access public
		*/

		protected function _help() {
			$this->out('Postgresql:');
			$this->hr();
			$this->out('Le script Postgresql permet de réaliser certaines opérations de maintenance');
			$this->out('sur une base de données PostgreSQL dont les paramètres de connection sont');
			$this->out('définis dans app/config/database.php');
			$this->hr();
			$this->out("Usage: cake/console/cake {$this->_script} <connection> <commande>...");
			$this->hr();
			$this->out('Params:');
			$this->out("\t-help affiche cette aide\n");
			$this->out('Commandes:');
			$this->out("\n\tsequences\n\t\tmet à jour toutes les séquences pour la connection.\n");
			$this->out('Connections:');
			$this->out("\n\t<connection>\n\t\tpeut prendre une des valeurs suivantes: ".implode( ', ', $this->_connections() ).".\n");
			$this->out();
		}
    }
?>