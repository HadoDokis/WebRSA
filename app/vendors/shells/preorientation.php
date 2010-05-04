<?php
	/**
	*
	*/

    class PreorientationShell extends Shell
    {
        public $uses = array( 'Orientstruct', 'Cohorte', 'Typeorient' );
		public $script = null;

		/// Aide sur les paramètres
		public $help = array( /// FIXME: 100
			'limit' => "Nombre d'enregistrements à traiter. Doit être un nombre entier positif. Par défaut: 0. Utiliser 0 ou null pour ne pas avoir de limite et traiter tous les enregistrements."
		);

		public $possibleParams = array(
		);

		public $outfile = null;
		public $output = '';
		public $limit = 0;
		public $force = true; /// FIXME: param
		public $startTime = null;

        /**
        *
        */

        public function err( $string ) {
			parent::err( $string );

			if( !empty( $this->outfile ) ) {
				$this->output .= "Erreur: {$string}\n";
			}
		}

        /**
        *
        */

        public function out( $string ) {
			parent::out( $string );

			if( !empty( $this->outfile ) ) {
				$this->output .= "{$string}\n";
			}
		}

        /**
        *
        */

        public function exportlog() {
			file_put_contents( $this->outfile, $this->output );
		}

		/**
		* Affiche l'aide liée à un paramètre
		* @access protected
		*/

		public function _printHelpParam( $param ) {
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

		public function _printHelp() {
			$this->out( "Paramètres possibles pour le script {$this->script}:" );
			$this->hr();
			$params = array();
			foreach( $this->help as $param => $message ) {
				$this->_printHelpParam( $param );
				$defaultValue = $this->{$param};
				$params[] = '-'.$param.' '.( is_bool( $defaultValue ) ? ( $defaultValue ? 'true' : 'false' ) : $defaultValue );
			}
			$this->hr();

			$this->out( sprintf( "Exemple: cake/console/cake %s -limit 100", $this->script, implode( ' ', $params ) ) );
			$this->hr();
			exit( 0 );
		}

        /**
        *
        *
        */

        public function startup() {
			$this->script = strtolower( preg_replace( '/Shell$/', '', $this->name ) );

			/// Demande d'aide ?
			if( isset( $this->params['help'] ) ) {
				$this->_printHelp();
				exit( 0 );
			}

			/// Paramétrage
			// Limit
			if( isset( $this->params['limit'] ) ) {
				if( is_numeric( $this->params['limit'] ) && ( (int)$this->params['limit'] == ( $this->params['limit'] * 1 ) ) && ( $this->params['limit'] != 0 ) ) {
					$this->limit = $this->params['limit'];
				}
				else if( empty( $this->params['limit'] ) || ( $this->params['limit'] == 'null' ) ) {
					$this->limit = null;
				}
				else {
					$this->err( sprintf( "Veuillez entrer un nombre comme valeur du paramètre limit (valeur entrée: %s)", $this->params['limit'] ) );
					exit( 2 );
				}
			}

			/// Nom du fichier et titre de la page
			$this->outfile = sprintf( '%s-%s.log', $this->script, date( 'Ymd-His' ) );
			$this->outfile = APP_DIR.'/tmp/logs/'.$this->outfile;
        }

        /**
        *
        */

        public function main() {
			$success = true;
			$compteur = 0;
			$nSuccess = 0;
			$nUndefined = 0;
			$nErrors = 0;
			$this->startTime = microtime( true );
			$this->Orientstruct->begin();

			//------------------------------------------------------------------

			$typesOrient = $this->Typeorient->find(
				'list',
				array(
					'fields' => array(
						'Typeorient.id',
						'Typeorient.lib_type_orient'
					),
					'order' => 'Typeorient.lib_type_orient ASC'
				)
			);
			$typesOrient[null] = 'Non définissable';

			$countTypesOrient = array_combine( array_keys( $typesOrient ), array_pad( array(), count( $typesOrient ), 0 ) );
			$typesOrient = array_flip( $typesOrient );

			//------------------------------------------------------------------

			if( $this->force ) {
				$this->out( "Remise à zéro des propositions d'orientation pour les personnes non orientées." );

				$sql = "UPDATE orientsstructs
							SET propo_algo = NULL,
								date_propo = NULL
							WHERE statut_orient <> 'Orienté'";
				$t = $this->Orientstruct->query( $sql ); // FIXME
				debug( $t );
			}

			//------------------------------------------------------------------

			$sqlCommon = "FROM orientsstructs
						INNER JOIN prestations ON (
							orientsstructs.personne_id = prestations.personne_id
							AND prestations.natprest = 'RSA'
							AND prestations.rolepers IN ( 'DEM', 'CJT' )
						)
						INNER JOIN personnes ON ( orientsstructs.personne_id = personnes.id )
						INNER JOIN calculsdroitsrsa ON ( orientsstructs.personne_id = calculsdroitsrsa.personne_id )
					WHERE orientsstructs.propo_algo IS NULL
						AND calculsdroitsrsa.toppersdrodevorsa = '1'
						AND orientsstructs.statut_orient <> 'Orienté'
						".( !empty( $this->limit ) ? "LIMIT {$this->limit}" : "" );
			// FIXME: force

			//------------------------------------------------------------------

			$nPersonnes = $this->Orientstruct->query( "SELECT COUNT( personnes.id ) {$sqlCommon}" );
			$nPersonnes = $nPersonnes[0][0]['count'];
			$this->out( "{$nPersonnes} personnes à traiter" );
			$this->hr();

			$personnes = $this->Orientstruct->query(
				"SELECT
						personnes.id AS \"Personne__id\",
						personnes.dtnai AS \"Personne__dtnai\",
						orientsstructs.id AS \"Orientstruct__id\",
						orientsstructs.personne_id AS \"Orientstruct__personne_id\"
					{$sqlCommon}"
			);

			$tranche = ( 1 / 100 );
			$periode = max( 1, round( $nPersonnes * $tranche ) );

			foreach( $personnes as $personne ) {
				$preOrientationTexte = $this->Cohorte->preOrientation( $personne );
				$preOrientation = Set::enum( $preOrientationTexte, $typesOrient );
				$countTypesOrient[$preOrientation]++;

				$orientstruct = array( 'Orientstruct' => Set::classicExtract( $personne, 'Orientstruct' ) );
				$orientstruct['Orientstruct']['date_propo'] = date( 'Y-m-d' );
				$orientstruct['Orientstruct']['propo_algo_texte'] = $preOrientationTexte;
				$orientstruct['Orientstruct']['propo_algo'] = $preOrientation;

				if( empty( $orientstruct['Orientstruct']['propo_algo'] ) ) {
					$nUndefined++;
				}
				else {
					$nSuccess++;
				}

				$this->Orientstruct->create( $orientstruct );
				$this->Orientstruct->validate = array();
				$tmpSuccess = $this->Orientstruct->save();
				if( !$tmpSuccess ) {
					$nErrors++;
				}
				$success = $tmpSuccess && $success;
				$compteur++;

				if( ( $compteur % $periode ) == 0 ) {
					$this->out( sprintf( "%s %% des personnes traitées (%s).", ( round( $compteur / $nPersonnes * 100 ) ), $compteur ) );
				}
			}

			//------------------------------------------------------------------

			$this->hr();

			$endTime = number_format( microtime( true ) - $this->startTime, 2 );

            /// Fin de la transaction
			$message = "%s ({$compteur} enregistrements traités en {$endTime} secondes; {$nSuccess} préorientations calculées, {$nUndefined} préorietations incalculables ({$nErrors} erreurs)";
            if( $success ) {
                $this->out( sprintf( $message, "Script terminé avec succès" ) );
                $this->Orientstruct->commit();
            }
            else {
                $this->out( sprintf( $message, "Script terminé avec erreurs" ) );
                $this->Orientstruct->rollback();
            }

			$this->exportlog();

			$this->hr();
			$this->out( "Le fichier de log se trouve dans {$this->outfile}" );

			exit( ( $success ? 0 : 1 ) );
        }
    }
?>