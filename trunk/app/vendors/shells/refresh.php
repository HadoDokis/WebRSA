<?php
	/**
	*
	* INFO:
	*	- base de données d'environ 200 Mo, datée du 04/11/2009
	*		* 103664 dossiers
	*		* 282952 personnes
	*		* 124276 orientstructs non orientés
	*		=> refresh -soumis false -preorientation true -force true -ressources false: 26,23 min. (1.574,66 s.)
	*		=> refresh -soumis false -preorientation true -force false -ressources false: 26,02 min. (1.561,31 s.)
	*		en cause: pas de DSP
	*/

    class RefreshShell extends Shell
    {
        var $uses = array( 'Foyer', 'Cohorte', 'Typeorient', 'Orientstruct' );
		var $limit = PHP_INT_MAX; // FIXME: PHP_INT_MAX ? -> en paramètre
		var $force = true;
		var $ressources = true;
		var $soumis = true;
		var $preorientation = true;
		var $help = array(
			'limit' => "Nombre de foyers à traiter. Doit être un nombre entier positif. Par défaut: pas de limite. Utiliser 0 ou null pour ne pas avoir de limite et traiter tous les foyers.",
			'ressources' => 'Doit-on recalculer la moyenne des ressources mensuelles des demandeurs et des conjoints ?',
			'soumis' => 'Doit-on recalculer si les demandeurs et les conjoints sont soumis à droits et devoirs ?',
			'preorientation' => 'Doit-on calculer et sauvegarder une préorientation pour les demandeurs et les conjoints qui ne sont pas encore orientés ni préorientés ?',
			'force' => 'Doit-on forcer le calcul de la préorientation même si une préorientation a déjà été calculée ?',
		);
		var $messageErreur = "\tVeuillez renseigner le paramètre -%s (soit true, soit false - valeur par défaut %s).";

		var $outfile = null;
		var $output = '';

        /**
        *
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
        *
        */

        function exportlog() {
			file_put_contents( $this->outfile, $this->output );
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
				$this->out( "-{$param}" );
				$this->out( "\t{$message}" );
				$params[] = '-'.$param.' '.( $this->{$param} ? 'true' : 'false' );
			}
			$this->hr();
			$this->out( sprintf( "Exemple (avec la valeur par défaut): cake/console/cake %s %s", $this->script, implode( ' ', $params ) ) );
			$this->hr();
			exit( 0 );
		}

		/**
		*
		*/

        function startup() {
			$this->script = preg_replace( '/shell$/', '', strtolower( $this->name ) );

			/// Demande d'aide ?
			if( isset( $this->params['help'] ) ) {
				$this->_printHelp();
				exit( 0 );
			}

			/// Vérifcation des paramètres
			$success = true;
			foreach( $this->help as $param => $message ) {
				// Limit
				if( $param == 'limit' ) {
					if( is_numeric( $this->params['limit'] ) && ( (int)$this->params['limit'] == ( $this->params['limit'] * 1 ) ) && ( $this->params['limit'] != 0 ) ) {
						$this->limit = $this->params['limit'];
					}
					else if( empty( $this->params['limit'] ) || ( $this->params['limit'] == 'null' ) ) {
						$this->limit = PHP_INT_MAX;
					}
					else {
						$this->err( sprintf( "Veuillez entrer un nombre comme valeur du paramètre limit (valeur entrée: %s)", $this->params['limit'] ) );
						exit( 2 );
					}
				}
				else {
					if( isset( $this->params[$param] ) && !in_array( $this->params[$param], array( 'true', 'false' ) ) ) {
						$this->out( '-'.$param );
						$this->out( sprintf( $this->messageErreur, $param, ( $this->{$param} ? 'true' : 'false' ) ) );
						$this->out( "\t{$message}" );
						$success = false;
						$this->hr();
					}
					if( isset( $this->params[$param] ) ) {
						$this->{$param} = ( $this->params[$param] == 'true' );
					}
				}
			}

			if( !$success ) {
				exit( 1 );
			}

			$this->outfile = APP_DIR.sprintf( '/tmp/logs/%s-%s.log', $this->script, date( 'Ymd-His' ) );
		}

		/**
		*
		*/

        function main() {
            /// Démarrage du script

            $this_start = microtime( true );
            $this->out( "Demarrage du script de rafraichissement: ".date( 'Y-m-d H:i:s' ) );

            $this->Foyer->begin();
            $success = true;

            /** ****************************************************************
            *   Réparation des données du flux CAF (les rgadr ne sont pas sur deux chiffres)
            *   Si le rang est bien formé, il n'y a pas de mise à jour
            *** ***************************************************************/
//             $this->hr();
//
//             $this->out( 'Debut de la mise a jour des rangs adresse: '.number_format( microtime( true ) - $this_start, 2 ) );
//
//             $adressesFoyers = $this->Foyer->Adressefoyer->find( 'list', array( 'fields' => array( 'Adressefoyer.id', 'Adressefoyer.rgadr' ) ) );
//             foreach( $adressesFoyers as $id => $rgadr ) {
//                 $rgadr = trim( $rgadr );
//                 if( strlen( $rgadr ) == 1 ) {
//                     $rgadr = '0'.$rgadr;
//                     $this->Foyer->Adressefoyer->create( array( 'Adressefoyer' => array( 'id' => $id, 'rgadr' => $rgadr ) ) );
//                     $success = $this->Foyer->Adressefoyer->save() && $success;
//                 }
//             }
//
//             $this->out( 'Fin de la mise a jour des rangs adresse: '.number_format( microtime( true ) - $this_start, 2 ) );

            /** ****************************************************************
            *   Rafraichissement de "soumis à droits et devoirs" pour la table
            *   orientsstructs
            *** ***************************************************************/
            // FIXME: ajouter une entrée dans la table orientsstructs ?

			/// Doit-on forcer le calcul des préorientations
			if( $this->preorientation == true && $this->force == true ) {
				$this->hr();
				$this->out( 'Suppression de la valeur de la préorientation pour les personnes en attente ou non orientées afin de forcer le calcul de cette valeur.' );

				$sql = "UPDATE orientsstructs
							SET propo_algo = NULL
							WHERE
								propo_algo IS NOT NULL
								AND statut_orient <> 'Orienté';";
				$result = $this->Foyer->query( $sql );
			}

            $this->hr();

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

			$nbFoyersTraites = 0;

			$nbFoyers = min( $this->Foyer->find( 'count' ), $this->limit );
			$this->out( sprintf( "%d foyers à traiter", $nbFoyers ) );
			$this->hr();

			$trancheFoyer = ( 1 / 100 );
			$periode = max( 1, round( $nbFoyers * $trancheFoyer ) );

            $foyers = $this->Foyer->find(
				'list',
				array(
					'fields' => array(
						'Foyer.id', 'Foyer.id'
					),
					'order' => 'Foyer.id ASC',
					'limit' => $this->limit
				)
			);

			$tRefreshRessources = 0;
			$tRefreshSoumis = 0;
			$tPreorientation = 0;

			$success = true;

            foreach( $foyers as $foyer_id ) {
                $tBoucle0 = $tRefreshRessources0 = microtime( true );

				/// Rafraîchissement des ressources
				if( $this->ressources == true ) {
					$refreshRessources = $this->Foyer->refreshRessources( $foyer_id );
					if( !$refreshRessources ) {
						$this->err( "Foyer->refreshRessources pour l\'id $foyer_id" );
					}
					$tRefreshRessources += ( microtime( true ) - $tRefreshRessources0 );
					$success = $refreshRessources && $success;
				}

				/// Rafraîchissement des "soumis à droits et devoirs"
				if( $this->soumis == true ) {
					$tRefreshSoumis0 = microtime( true );
					$refreshSoumisADroitsEtDevoirs = $this->Foyer->refreshSoumisADroitsEtDevoirs( $foyer_id );
					$tRefreshSoumis += ( microtime( true ) - $tRefreshSoumis0 );

					if( !$refreshRessources ) {
						$this->err( "Foyer->refreshSoumisADroitsEtDevoirs pour l\'id $foyer_id" );
					}
					$success = $refreshSoumisADroitsEtDevoirs && $success;
				}

				// Calcul et sauvegarde des pré-orientations
				/// Rafraîchissement de la préorientation
				if( $this->preorientation == true ) {
					$tPreorientation0 = microtime( true );
					$this->Foyer->Personne->unbindModelAll();
					$this->Foyer->Personne->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) );
					$this->Foyer->unbindModelAll();

					$personnes = $this->Foyer->Personne->find(
						'all',
						array(
							'fields' => array(
								'Personne.id',
								'Personne.foyer_id',
								'Personne.qual',
								'Personne.nom',
								'Personne.prenom',
								'Personne.nomnai',
								'Personne.prenom2',
								'Personne.prenom3',
								'Personne.nomcomnai',
								'Personne.dtnai',
								'Personne.rgnai',
								'Personne.typedtnai',
								'Personne.nir',
								'Personne.topvalec',
								'Personne.sexe',
								'Personne.nati',
								'Personne.dtnati',
								'Personne.pieecpres',
								'Personne.idassedic',
								'Foyer.id',
								'Foyer.dossier_rsa_id',
								'Foyer.sitfam',
								'Foyer.ddsitfam',
								'Foyer.typeocclog',
								'Foyer.mtvallocterr',
								'Foyer.mtvalloclog',
								'Foyer.contefichliairsa',
								'Orientstruct.id',
								'Orientstruct.personne_id',
								'Orientstruct.typeorient_id',
								'Orientstruct.structurereferente_id',
								'Orientstruct.propo_algo',
								'Orientstruct.valid_cg',
								'Orientstruct.date_propo',
								'Orientstruct.date_valid',
								'Orientstruct.statut_orient',
								'Orientstruct.date_impression',
								'Orientstruct.daterelance',
								'Orientstruct.statutrelance'
							),
							'joins' => array(
								array(
									'table'      => 'prestations',
									'alias'      => 'Prestation',
									'type'       => 'INNER',
									'foreignKey' => false,
									'conditions' => array(
										'Prestation.personne_id = Personne.id',
										'Prestation.natprest = \'RSA\'',
										'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )'
									)
								),
								array(
									'table'      => 'orientsstructs',
									'alias'      => 'Orientstruct',
									'type'       => 'INNER',
									'foreignKey' => false,
									'conditions' => array(
										'Orientstruct.personne_id = Personne.id',
										'Orientstruct.id = Orientstruct.id',
										'Orientstruct.statut_orient <> \'Orienté\'',
										'Orientstruct.propo_algo' => null
									)
								),
							),
							'conditions' => array( 'Personne.foyer_id' => $foyer_id ),
							'recursive' => 2
						)
					);

					foreach( $personnes as $personne ) {
						$preOrientationTexte = $this->Cohorte->preOrientation( $personne );
						$preOrientation = Set::enum( $preOrientationTexte, $typesOrient );
						$countTypesOrient[$preOrientation]++;

						$orientstruct = array( 'Orientstruct' => Set::classicExtract( $personne, 'Orientstruct' ) );
						$orientstruct['Orientstruct']['propo_algo_texte'] = $preOrientationTexte;
						$orientstruct['Orientstruct']['propo_algo'] = $preOrientation;

						$this->Orientstruct->create( $orientstruct );
						$this->Orientstruct->validate = array();
						$success = $this->Orientstruct->save() && $success;
					}
					$tPreorientation += ( microtime( true ) - $tPreorientation0 );
				}

				$nbFoyersTraites++;
				if( ( $nbFoyersTraites % $periode ) == 0 ) {
					$this->out( sprintf( "%s %% des foyers traités (%s).", ( round( $nbFoyersTraites / $nbFoyers * 100 ) ), $nbFoyersTraites ) );
				}

                // avant 17/08/2009 entre 0.20 et 0.40 secondes
                // le 17/08/2009 entre 0.10 et 0.20 secondes
				if( Configure::read( 'debug' ) && ( microtime( true ) - $tBoucle0 ) > 0.5 ) {
					$this->out( 'passage couteux dans la boucle (foyer '.$foyer_id.'): '.number_format( microtime( true ) - $tBoucle0, 2 ) );
				}
            }

			// Temps passé dans les différentes parties du script
			$this->hr();
			$tTotal = ( $tRefreshRessources + $tRefreshSoumis + $tPreorientation );
            $this->out( "Temps ressources:\t\t".number_format( $tRefreshRessources / $tTotal * 100, 2 )." % =>\t".number_format( $tRefreshRessources, 2 ) );
            $this->out( "Temps droits et devoirs:\t".number_format( $tRefreshSoumis / $tTotal * 100, 2 )." % =>\t".number_format( $tRefreshSoumis, 2 ) );
            $this->out( "Temps préorientation:\t\t".number_format( $tPreorientation / $tTotal * 100, 2 )." % =>\t".number_format( $tPreorientation, 2 ) );

            /** ****************************************************************
            *   Fin du script
            *** ***************************************************************/

            $this->hr();

            if( $success ) {
				if( $this->preorientation == true ) {
					foreach( $typesOrient as $label => $key ) {
						$this->out( sprintf( '%s préorientations %s.', $countTypesOrient[$key], $label ) );
					}
					$this->hr();
				}
                $this->Foyer->commit();
                $this->out( "Script de rafraicissement termine avec succes: ".date( 'Y-m-d H:i:s' ).'( en '.number_format( microtime( true ) - $this_start, 2 ).' secondes )' );
            }
            else {
                $this->Foyer->rollback();
                $this->out( "Script de rafraicissement termine avec erreurs: ".date( 'Y-m-d H:i:s' ).'( en '.number_format( microtime( true ) - $this_start, 2 ).' secondes )' );
            }

			$this->exportlog();

			return ( $success ? 0 : 1 );
        }
    }
?>