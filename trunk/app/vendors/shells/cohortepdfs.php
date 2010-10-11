<?php
	/**
	*
	*/

    class CohortepdfsShell extends Shell
    {
        var $uses = array( 'Pdf', 'Orientstruct', 'User' );
		var $script = null;

		/// Aide sur les paramètres
		var $help = array(
			'username' => "L'identifiant de l'utilisateur qui sera utilisé pour la récupération d'informations lors de l'impression. Pas de défaut.",
			'limit' => "Nombre d'enregistrements à traiter. Doit être un nombre entier positif. Par défaut: 10. Utiliser 0 ou null pour ne pas avoir de limite et traiter tous les enregistrements.",
			'order' => "Permet de trier les enregistrements à traiter par date de validation de l'orentation (date_valid) en ordre ascendant ou descendant. Valeurs possibles: asc ou desc. Par défaut: asc."
		);

		var $possibleParams = array(
			'order' => array( 'asc', 'desc' )
		);

		var $outfile = null;
		var $output = '';
		var $limit = 10;
		var $order = 'asc';
		var $startTime = null;
		var $username = null;
		var $user_id = null;

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

			$this->out( sprintf( "Exemple: cake/console/cake %s -limit 10 -order asc -userame cbuffin", $this->script, implode( ' ', $params ) ) );
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
					$this->err( sprintf( "Veuillez entrer un nombre comme valeur du paramètre -limit (valeur entrée: %s)", $this->params['limit'] ) );
					exit( 2 );
				}
			}
			// Order
			if( isset( $this->params['order'] ) ) {
				if( is_string( $this->params['order'] ) && in_array( strtolower( trim( $this->params['order'] ) ), array( 'asc', 'desc' ) ) ) {
					$this->order = strtolower( trim( $this->params['order'] ) );
				}
				else {
					$this->err( sprintf( "Veuillez entrer asc ou desc comme valeur du paramètre -order (valeur entrée: %s)", $this->params['order'] ) );
					exit( 2 );
				}
			}
			// Username
			$user_id = $this->User->field( 'id', array( 'username' => Set::classicExtract( $this->params, 'username' ) ) );
			if( isset( $this->params['username'] ) && is_string( $this->params['username'] ) && !empty( $user_id ) ) {
					$this->user_id = $user_id;
			}
			else {
				$this->err( sprintf( "Veuillez entrer un identifiant valide comme valeur du paramètre -username (valeur entrée: %s)", Set::classicExtract( $this->params, 'username' ) ) );
				exit( 2 );
			}

			/// Nom du fichier et titre de la page
			$this->outfile = sprintf( '%s-%s.log', $this->script, date( 'Ymd-His' ) );
			$this->outfile = APP_DIR.'/tmp/logs/'.$this->outfile;
        }

        /**
        *
        */

        function main() {
			$success = true;
			$compteur = 0;
			$nSuccess = 0;
			$nErrors = 0;
			$this->startTime = microtime( true );
// 			$this->Orientstruct->begin();

			App::import( 'Core', 'Component' );
			App::import( 'Component', 'Gedooo' );
			$this->Gedooo =& new GedoooComponent( null );

			$orientsstructsQuerydatas = array(
				'conditions' => array(
					'Orientstruct.statut_orient' => 'Orienté',
					'Orientstruct.id NOT IN ( SELECT pdfs.fk_value FROM pdfs WHERE pdfs.modele = \'Orientstruct\' )'
				),
				'order' => array( 'Orientstruct.date_valid '.$this->order )
			);

			if( !empty( $this->limit ) ) {
				$orientsstructsQuerydatas['limit'] = $this->limit;
			}

			$orientsstructs_ids = $this->Orientstruct->find( 'list', $orientsstructsQuerydatas );

			$this->out( sprintf( "%d enregistrements à traiter.", count( $orientsstructs_ids ) ) );
			$this->hr();

			foreach( $orientsstructs_ids as $orientstruct_id ) {
	 			$this->Orientstruct->begin();

				$this->out( sprintf( "Génération du PDFs %d/%d (Orientstruct.id=%s)", ( $compteur + 1 ), count( $orientsstructs_ids ), $orientstruct_id ) );

				$orientstruct = $this->Orientstruct->getDataForPdf( $orientstruct_id, $this->user_id );
				$modele = $orientstruct['Typeorient']['modele_notif'];

				$modeledoc = 'Orientation/'.$modele.'.odt';
				$pdfContent = $this->Gedooo->getPdf( $orientstruct, $modeledoc );
				if( !is_bool( $pdfContent ) ) {
					$this->Pdf->create(
						array(
							$this->Pdf->alias => array(
								'modele' => 'Orientstruct',
								'modeledoc' => $modeledoc,
								'fk_value' => $orientstruct_id,
								'document' => $pdfContent
							)
						)
					);

					$tmpSuccess = $this->Pdf->save();
					if( $tmpSuccess ) {
						$nSuccess++;
					}
					else {
						$nErrors++;
					}

					$success = $tmpSuccess && $success;
				}
				else {
					$tmpSuccess = false;
					$nErrors++;
				}

				if( $tmpSuccess ) {
					$this->Orientstruct->commit();
				}
				else {
					$this->Orientstruct->rollback();
				}

				$compteur++;
			}

			$this->hr();

			$endTime = number_format( microtime( true ) - $this->startTime, 2 );

            /// Fin de la transaction
			$message = "%s ({$compteur} pdfs d'orientation à générer, {$nSuccess} succès, {$nErrors} erreurs) en {$endTime} secondes";
            if( $success ) {
                $this->out( sprintf( $message, "Script terminé avec succès" ) );
//                 $this->Orientstruct->commit();
            }
            else {
                $this->out( sprintf( $message, "Script terminé avec erreurs" ) );
//                 $this->Orientstruct->rollback();
            }

			$this->exportlog();

			$this->hr();
			$this->out( "Le fichier de log se trouve dans {$this->outfile}" );

			exit( ( $success ? 0 : 1 ) );
        }
    }
?>