<?php
	/**
	*	FIXME: vérifier le format du fichier, ...
	*/

    class ImportcsvinfopeShell extends Shell
    {
        var $uses = array( 'Tempinscription', 'Tempcessation', 'Tempradiation' );
		var $csv = null;
		var $schema = array();
		var $type = null; // {inscription|cessation|radiation} <--- FIXME

		var $map = array(
			'Tempinscription' => array(
				1 => 'nir',
				2 => 'identifiantpe',
				3 => 'nom',
				4 => 'prenom',
				5 => 'dtnai',
				6 => 'dateinscription',
				7 => 'categoriepe'
			),
			'Tempcessation' => array(
				1 => 'nir',
				2 => 'identifiantpe',
				3 => 'nom',
				4 => 'prenom',
				5 => 'dtnai',
				6 => 'datecessation',
				7 => 'motifcessation'
			),
			'Tempradiation' => array(
				1 => 'nir',
				2 => 'identifiantpe',
				3 => 'nom',
				4 => 'prenom',
				5 => 'dtnai',
				6 => 'dateradiation',
				7 => 'motifradiation'
			),
		);

		var $modelClass = null;
		var $script = null;

		/// Aide sur les paramètres
		var $help = array(
			'type' => "Type de fichier CSV (inscription, cessation, radiation)."
		);

		/// Paramètres
		var $possibleParams = array(
			'type' => array( 'inscription', 'cessation', 'radiation' )
		);

		var $outfile = null;
		var $output = '';

        /**
        *
        */

        function err( $string ) {
			parent::err( $string );

			if( !empty( $this->outfile ) ) {
				$this->output .= "Erreur: {$string}\n";
			}
		}

        /**
        *
        */

        function out( $string ) {
			parent::out( $string );

			if( !empty( $this->outfile ) ) {
				$this->output .= "{$string}\n";
			}
		}

        /**
        *
        */

        function exportlog() {
			file_put_contents( $this->outfile, $this->output );
		}

		/**
		* Affiche l'aide liée à un paramètre
		* @access protected
		*/

		function _printHelpParam( $param ) {
			$message = $this->help[$param];
			$this->out( "-{$param}" );
			$this->out( "\t{$message}" );
			$defaultValue = $this->{$param};
			$params[] = '-'.$param.' '.( is_bool( $defaultValue ) ? ( $defaultValue ? 'true' : 'false' ) : $defaultValue );
		}

		/**
		* Affiche l'aide liée au script (les paramètes possibles)
		* @access protected
		*/

		function _printHelp() {
			$this->out( "Paramètres possibles pour le script {$this->script}:" );
			$this->hr();
			$params = array();
			foreach( $this->help as $param => $message ) {
				$this->_printHelpParam( $param );
				$defaultValue = $this->{$param};
				$params[] = '-'.$param.' '.( is_bool( $defaultValue ) ? ( $defaultValue ? 'true' : 'false' ) : $defaultValue );
			}
			$this->hr();

			$this->out( sprintf( "Exemple: cake/console/cake %s -type inscription ~/RSA_INSCRIPTION_201002_DPT34.csv", $this->script, implode( ' ', $params ) ) );
			$this->hr();
			exit( 0 );
		}

        /**
        *
        *
        */

        function startup() {
			$this->script = strtolower( preg_replace( '/Shell$/', '', $this->name ) );

			/// Demande d'aide ?
			if( isset( $this->params['help'] ) ) {
				$this->_printHelp();
				exit( 0 );
			}

			$continue = true;

			if( !isset( $this->params['type'] ) ) {
				$this->err( "Veuillez entrer une valeur pour le paramètre -type" );
				$this->_printHelpParam( 'type' );
				$continue = false;
			}

            if( count( $this->args ) == 0 ) {
                echo "Veuillez entrer un fichier CSV en paramètre.\n";
				$continue = false;
            }

			if( count( $this->args ) > 0 ) {
				$this->csv = new File( $this->args[0] );
				if( !$this->csv->exists() ) {
					echo "Le fichier ".$this->args[0]." n'existe pas.\n";
					$continue = false;
				}
				else if( !$this->csv->readable() ) {
					echo "Le fichier ".$this->args[0]." n'est pas lisible.\n";
					$continue = false;
				}
			}

			if( !$continue ) {
				exit( 1 );
			}

			/// Paramétrage
			$continue = true;
			foreach( $this->possibleParams as $param => $possibleValues ) {
				if( isset( $this->params[$param] ) ) {
					if( is_string( $this->params[$param] ) && in_array( $this->params[$param], $possibleValues ) ) {
						$defaultValue = $this->{$param};
						$value = ( is_bool( $defaultValue ) ? ( ( $this->params[$param] == 'true' ) ? true : false ) : $this->params[$param] );
						$this->{$param} = $value;
					}
					else {
						$continue = false;
						$this->err( "Valeur erronée pour le paramètre -{$param} ({$this->params[$param]})" );
						$this->_printHelpParam( $param );
					}
				}
			}
			if( $continue == false ) {
				exit( 1 );
			}

			$this->modelClass = "Temp{$this->type}";
			$this->schema = $this->{$this->modelClass}->schema();

			/// Nom du fichier et titre de la page
			$this->outfile = sprintf( '%s-%s-%s.log', $this->script, date( 'Ymd-His' ), $this->type );
			$this->outfile = APP_DIR.'/tmp/logs/'.$this->outfile;

			$this->out( sprintf( "Exécution du script %s (%s) avec le fichier %s", $this->script, $this->type, $this->args[0] ) );
			$this->hr();
        }

		/**
		*
		*/

		function map( $entry ) {
			$return = array();
			foreach( $this->map[$this->modelClass] as $index => $column ) {
				$return[$column] = trim( $entry[$index], '"' );

				$dataType = Set::classicExtract( $this->schema, "{$column}.type" );
				if( $dataType == 'date' ) {
					list( $day, $month, $year ) = explode( '/', $return[$column] );
					$return[$column] = implode( '-', array( $year, $month, $day ) );
				}
			}
			return $return;
		}

        /**
        *
        */

        function main() {
			$this->{$this->modelClass}->begin();

			$fields = array();
			$nLignes = 0;
			$nLignesTraitees = 0;
			$nLignesPresentes = 0;
			$nLignesErreur = 0;
			$success = true;

			$lines = explode( "\n", $this->csv->read() );

			foreach( $lines as $i => $line ) {
				$parts = explode( ';', $line );
				$cleanedParts = Set::filter( $parts );

				if( !empty( $cleanedParts ) ) {
					if( $i == 0 ) {
						$this->out( "Ligne n°".( $i + 1 )." passée (ligne d'en-têtes)." );
					}
					else {
						$nLignes++;

						$item = array( $this->modelClass => $this->map( $parts ) );

						$ItemFound = $this->{$this->modelClass}->find(
							'count',
							array(
								'conditions' => $item[$this->modelClass],
								'recursive' => -1
							)
						);

						if( $ItemFound == 0 ) {
							/// Insertion
							$this->{$this->modelClass}->create( $item );
							$tmpSuccess = $this->{$this->modelClass}->save();
							if( $tmpSuccess ) {
								$this->out( "Ligne n°{$i} traitée." );
								$nLignesTraitees++;
							}
							else {
								$this->err( "Impossible de traiter la ligne n°".( $i + 1 )."." );
								$nLignesErreur++;
								die();
							}
							$success = $tmpSuccess && $success;
						}
						else {
							$this->out( "Ligne n°".( $i + 1 )." déjà trouvée." );
							$nLignesPresentes++;
						}
					}
				}
			}

			$this->hr();

            $message = "%s: $nLignes lignes trouvées, $nLignesTraitees lignes traitées, $nLignesPresentes lignes déjà présentes.";

            /// Fin de la transaction
            if( $success ) {
                $this->out( sprintf( $message, "Script terminé avec succès" ) );
                $this->{$this->modelClass}->commit();
            }
            else {
                $this->out( sprintf( $message, "Script terminé avec erreurs" ) );
                $this->{$this->modelClass}->rollback();
            }

			$this->exportlog();

			$this->hr();
			$this->out( "Le fichier de log se trouve dans {$this->outfile}" );
			$this->hr();

			exit( ( $success ? 0 : 1 ) );
        }
    }
?>