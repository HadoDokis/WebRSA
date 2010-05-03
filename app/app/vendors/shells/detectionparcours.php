<?php
	/**
	*
	*/

    class DetectionparcoursShell extends Shell
    {
        var $uses = array( 'Parcoursdetecte' );
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
			$this->Parcoursdetecte->begin();

			$sql = 'SELECT orientsstructs.*, age( orientsstructs.date_valid )
				FROM personnes
					INNER JOIN prestations ON (
						prestations.personne_id = personnes.id
						AND prestations.natprest = \'RSA\'
						AND ( prestations.rolepers = \'DEM\' OR prestations.rolepers = \'CJT\' )
					)
					INNER JOIN orientsstructs ON (
						orientsstructs.personne_id = personnes.id
						AND orientsstructs.statut_orient = \'Orienté\'
						AND orientsstructs.typeorient_id IN (
							SELECT typesorients.id
								FROM typesorients
								WHERE typesorients.lib_type_orient IN (
									\'Social\',
									\'Socioprofessionnelle\'
								)
						)
						AND age( orientsstructs.date_valid ) > \'6 mons\'
						AND orientsstructs.id IN (
							SELECT orientsstructs.id
								FROM orientsstructs
								WHERE orientsstructs.date_valid IS NOT NULL
									AND orientsstructs.statut_orient = \'Orienté\'
								GROUP BY orientsstructs.personne_id, orientsstructs.id, orientsstructs.date_valid
								ORDER BY orientsstructs.date_valid DESC
								-- LIMIT 1
						)
						AND orientsstructs.id NOT IN (
							SELECT parcoursdetectes.orientstruct_id
								FROM parcoursdetectes
						)
					)
				WHERE personnes.id NOT IN (
					SELECT contratsinsertion.personne_id
						FROM contratsinsertion
						WHERE contratsinsertion.dd_ci <= NOW()
							AND contratsinsertion.df_ci >= NOW()
				);';

			$this->hr();

			$orientsstructs = $this->Parcoursdetecte->query( $sql );
			if( !empty( $orientsstructs ) ) {
				foreach( $orientsstructs as $orientstruct ) {
					$parcoursdetecte = array(
						'Parcoursdetecte' => array(
							'orientstruct_id' => $orientstruct[0]['id']
						)
					);
					$this->Parcoursdetecte->create( $parcoursdetecte );
					$tmpSuccess = $this->Parcoursdetecte->save();
					if( $tmpSuccess ) {
						$this->out( "Succès pour l'ajout d'une détection pour l'orientstruct {$orientstruct[0]['id']}" );
					}
					else {
						$this->err( "Erreur lors de l'ajout d'une détection pour l'orientstruct {$orientstruct[0]['id']}" );
					}
					$success = $tmpSuccess && $success;
					$compteur++;
				}
			}

			$this->hr();

            /// Fin de la transaction
			$message = "%s ({$compteur} parcours détectés)";
            if( $success ) {
                $this->out( sprintf( $message, "Script terminé avec succès" ) );
                $this->Parcoursdetecte->commit();
            }
            else {
                $this->out( sprintf( $message, "Script terminé avec erreurs" ) );
                $this->Parcoursdetecte->rollback();
            }

			$this->exportlog();

			$this->hr();
			$this->out( "Le fichier de log se trouve dans {$this->outfile}" );

			exit( ( $success ? 0 : 1 ) );
        }
    }
?>