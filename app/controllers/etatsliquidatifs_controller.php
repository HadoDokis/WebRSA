<?php
    @set_time_limit( 0 );
    @ini_set( 'memory_limit', '1024M' );
    @ini_set( 'default_socket_timeout', 3660 );
	App::import('Sanitize');

	class EtatsliquidatifsController extends AppController
	{
		var $name = 'Etatsliquidatifs';

		var $uses = array( 'Etatliquidatif', 'Parametrefinancier', 'Suiviaideapretypeaide', 'Apre', 'Option', 'Adressefoyer', 'ApreEtatliquidatif' );
        var $components = array( 'Gedooo' );
		var $helpers = array( 'Xform', 'Locale', 'Paginator', 'Apreversement' );
        var $aucunDroit = array( 'ajaxmontant' );


		/**
		*
		*/

		public function index() {
			$conditions = array();

			$budgetapre_id = Set::classicExtract( $this->params, 'named.budgetapre_id' );
			if( !empty( $budgetapre_id ) ) {
				$conditions["{$this->modelClass}.budgetapre_id"] = $budgetapre_id;
			}

			$this->paginate[$this->modelClass] = array(
				'limit' => 10,
				'order' => array( "{$this->modelClass}.id ASC" ),
				'conditions' => $conditions,
				'recursive' => 0,
			);

			$etatsliquidatifs = $this->paginate( $this->modelClass );

            if( !empty( $etatsliquidatifs ) ){
                $apres_etatsliquidatifs = $this->{$this->modelClass}->ApreEtatliquidatif->find(
                    'all',
                    array(
                        'conditions' => array(
                            'ApreEtatliquidatif.etatliquidatif_id' => Set::extract( $etatsliquidatifs, '/Etatliquidatif/id' )
                        ),
                        'recursive' => -1
                    )
                );
                $this->set( 'apres_etatsliquidatifs', $apres_etatsliquidatifs);
            }
//             debug($etatsliquidatifs);

			$this->set( compact( 'etatsliquidatifs' ) );
		}

        /**
        *
        */

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }


        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /**
        *
        */

        function _add_edit( $id = null ) {
			$parametrefinancier = $this->Parametrefinancier->find( 'first' );
			if( empty( $parametrefinancier ) ) {
				$this->Session->setFlash( __( 'Impossible de créer ou de modifier un état liquidatif si les paramètres financiers ne sont pas enregistrés.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
			}

			$budgetsapres = $this->{$this->modelClass}->Budgetapre->find( 'list' );
			if( empty( $budgetsapres ) ) {
				$this->Session->setFlash( __( 'Impossible de créer ou de modifier un état liquidatif s\'il n\'existe pas de budget APRE.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
			}

			if( $this->action == 'edit' ) {
				$etatliquidatif = $this->{$this->modelClass}->findById( $id, null, null, -1 );
				$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );
			}
			else {
				// Aucun autre état liquidatif ouvert
				$nEtatsliquidatifs = $this->{$this->modelClass}->find( 'count', array( 'conditions' => array( 'Etatliquidatif.datecloture IS NULL' ) ) );
				if( $nEtatsliquidatifs > 0 ) {
					$this->Session->setFlash( __( 'Impossible de créer un état liquidatif lorsqu\'il existe un autre état liquidatif non validé.', true ), 'flash/error' );
					$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
				}
			}

			if( !empty( $this->data ) ) {
				$parametrefinancier = $this->Parametrefinancier->find( 'first' );

				// Copie
				$etatliquidatifFields = array_keys( $this->{$this->modelClass}->schema() );
				foreach( $parametrefinancier['Parametrefinancier'] as $field => $value ) {
					if( ( $field != 'id' ) && in_array( $field, $etatliquidatifFields ) ) {
						$this->data[$this->modelClass][$field] = $value;
					}
				}
				$this->data[$this->modelClass]['operation'] = ( ( $this->data[$this->modelClass]['typeapre'] == 'forfaitaire' ) ? $this->data[$this->modelClass]['apreforfait'] : $this->data[$this->modelClass]['aprecomplem'] );

				$this->{$this->modelClass}->create( $this->data );
				if( $this->{$this->modelClass}->save() ) {
					$this->Session->setFlash( __( 'Enregistrement effectué', true ), 'flash/success' );
					$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $etatliquidatif;
			}

			$this->set( 'typesapres', array( 'forfaitaire' => 'APREs forfaitaires', 'complementaire' => 'APREs complémentaires' ) ); // TODO: enum
			$this->set( 'budgetsapres', $budgetsapres );
            $this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		function selectionapres( $id = null ) {
			$etatliquidatif = $this->{$this->modelClass}->findById( $id, null, null, -1 );
			$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

            // Retour à la liste en cas d'annulation
            if( /*!empty( $this->data ) && */isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }

			// État liquidatif pas encore validé
			if( !empty( $etatliquidatif['Etatliquidatif']['datecloture'] ) ) {
				$this->Session->setFlash( __( 'Impossible de sélectionner des APREs pour un état liquidatif validé.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index' ) );
			}


			if( !empty( $this->data ) ) {
				foreach( $this->data['Apre']['Apre'] as $i => $value ) {
					if( empty( $value ) ) {
						unset( $this->data['Apre']['Apre'][$i] );
					}
				}

				if( $this->{$this->modelClass}->saveAll( $this->data ) ) {
					$this->Session->setFlash( __( 'Enregistrement effectué', true ), 'flash/success' );
					$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );

				}
			}

			$typeapre = ( ( Set::classicExtract( $etatliquidatif, 'Etatliquidatif.typeapre' ) == 'forfaitaire' ) ? 'F' : 'C' );

             $queryData = $this->Etatliquidatif->listeApresPourEtatLiquidatif( $id, array( 'Apre.statutapre' => $typeapre ) );

			$etatliquidatifLimit = Configure::read( 'Etatliquidatif.limit' );
			if( !empty( $etatliquidatifLimit ) ) {
				$queryData['limit'] = $etatliquidatifLimit;
			}

			$this->{$this->modelClass}->Apre->unbindModelAll();
			$apres = $this->{$this->modelClass}->Apre->find( 'all', $queryData );

			$apres_etatsliquidatifs = $this->{$this->modelClass}->ApreEtatliquidatif->find(
				'all',
				array(
					'conditions' => array( 'ApreEtatliquidatif.etatliquidatif_id' => $id ),
					'recursive' => -1
				)
			);
			$this->data['Apre']['Apre'] = Set::extract( $apres_etatsliquidatifs, '/ApreEtatliquidatif/apre_id' );

			$this->set( compact( 'apres', 'typeapre' ) );
		}

        /**
        *
        */

        function visualisationapres( $id = null ) {
            $etatliquidatif = $this->{$this->modelClass}->findById( $id, null, null, -1 );
            $this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

            $natureAidesApres = $this->Option->natureAidesApres();
            if( !empty( $this->data ) ) {
                foreach( $this->data['Apre']['Apre'] as $i => $value ) {
                    if( empty( $value ) ) {
                        unset( $this->data['Apre']['Apre'][$i] );
                    }
                }

                if( $this->{$this->modelClass}->saveAll( $this->data ) ) {
                    $this->Session->setFlash( __( 'Enregistrement effectué', true ), 'flash/success' );
                    $this->redirect( array( 'action' => 'index' ) );
                }
            }

            $typeapre = ( ( Set::classicExtract( $etatliquidatif, 'Etatliquidatif.typeapre' ) == 'forfaitaire' ) ? 'F' : 'C' );
            $this->set( 'typeapre', $typeapre );
            $queryData = $this->Etatliquidatif->listeApresEtatLiquidatif( array( 'Apre.statutapre' => $typeapre ), $id );
            $queryData['limit'] = 100;

            $this->{$this->modelClass}->Apre->unbindModelAll( false );
            $this->paginate['Apre'] = $queryData;

            $apres = $this->paginate( 'Apre' );
// debug( $apres );

            if( $typeapre  == 'C' ) {
                foreach( $apres as $i => $apre ) {
                    $apre_etatliquidatif = $this->ApreEtatliquidatif->find(
                        'first',
                        array(
                            'conditions' => array(
                                'ApreEtatliquidatif.etatliquidatif_id' => $id,
                                'ApreEtatliquidatif.apre_id' => Set::extract( $apre, '/Apre/id' )
                            ),
                            'recursive' => -1
                        )
                    );
                    $apre = Set::merge( $apre, $apre_etatliquidatif );
// debug( $apre );
                    $apres[$i] = $apre;
                }
                if( !empty( $apre_etatliquidatif ) ){
                    $this->set( 'apre_etatliquidatif', $apre_etatliquidatif );
                }

// 		$aidesApre = array();
// 		foreach( $apres as $key => $apre ) {
// 		  $modelLie = Set::classicExtract( $apre, 'Apre.Natureaide' );
//
// 		    foreach( $modelLie as $natureaide => $nombre ) {
// 		      if( $nombre > 0 ) {
// 			$aidesApre = $natureaide;
// // 			foreach( $natureaide as $model ){
// 			  if( in_array( $natureaide, $this->Apre->modelsFormation ) ){
// //       		      debug($natureaide);
// 			      $dest = 'tiersprestataire';
// 			  }
// 			  else{
// 			      $dest = 'beneficiaire';
// 			  }
// 		    $this->set( 'dest', $dest );
//
// 			}
//
//                     }
// 		  }
// 		}

            }

// debug($apres);
            $this->set( compact( 'apres' ) );
        }

        /**
        *
        */

        function impressiongedoooapres( $apre_id, $etatliquidatif_id ) {
            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();
            $natureAidesApres = $this->Option->natureAidesApres();

            $apre = $this->Apre->donneesForfaitaireGedooo( $apre_id, $etatliquidatif_id );
            $this->assert( !empty( $apre ), 'invalidParameter' );



            ///Données nécessaire pour savoir quelles sont les aides liées à l'APRE Complémentaire + la pers chargée du suivi
            $apre['Dataperssuivi'] = array();

            foreach( $this->Apre->aidesApre as $model ) {
                if( isset( $apre['Apre']['Natureaide'][$model] ) && ( $apre['Apre']['Natureaide'][$model] == 0 ) ){
                   unset( $apre['Apre']['Natureaide'][$model] );

                }
            }

            $model = null;
            if( isset( $apre['Apre']['Natureaide'][0] ) ) {
                $model = $apre['Apre']['Natureaide'][0];
                $personne = $this->Suiviaideapretypeaide->findByTypeaide( $model );
                if( !empty( $personne['Suiviaideapre'] ) ){
                    foreach( array_keys( $personne['Suiviaideapre'] ) as $key ) {
                        if( $key != 'id' ) {
                            $apre['Dataperssuivi']["{$key}suivi"] = $personne['Suiviaideapre'][$key];
                        }
                    }
                }
            }

            $aidesApre = array();
            $isTiers = false;

            $modelsFormation = array( 'Formqualif', 'Formpermfimo', 'Permisb', 'Actprof' );
            $modelLie = Set::classicExtract( $apre, 'Apre.Natureaide' );


            ///Paramètre nécessaire pour connaitre le type de formation du bénéficiaire (Formation / Hors Formation )
            if( isset( $apre['Apre']['Natureaide'][0] ) && in_array( $apre['Apre']['Natureaide'][0], $modelsFormation ) ) {
                $typeformation = 'formation';
            }
            else {
                $typeformation = 'horsformation';
            }
            $this->set( 'typeformation', $typeformation );

            /*
                Traduction des codes en français
            */
            $typeapre = Set::classicExtract( $apre, 'Apre.statutapre' );

            $apre['Personne']['qual'] = Set::classicExtract( $qual, Set::classicExtract( $apre, 'Personne.qual' ) );
            $apre['Adresse']['typevoie'] = Set::classicExtract( $typevoie, Set::classicExtract( $apre, 'Adresse.typevoie' ) );

            /**
            *   Partie nécessaire en cas d'APRE Complémentaire
            */
            ///Paramètre nécessaire pour le bon choix du document à éditer
            $dest = Set::classicExtract( $this->params, 'named.dest' );

            ///Traduction des intitulés des noms des adresses + bon format de date
            if( !empty( $apre['Tiersprestataireapre'] ) ) {
                $apre['Tiersprestataireapre']['typevoie'] = Set::classicExtract( $typevoie, Set::classicExtract( $apre, 'Tiersprestataireapre.typevoie' ) );
            }
            $apre['Modellie']['ddform'] = date_short( Set::classicExtract( $apre, 'Modellie.ddform' ) );
            $apre['Modellie']['dfform'] = date_short( Set::classicExtract( $apre, 'Modellie.dfform' ) );
            $apre['Comiteapre']['datecomite'] = date_short( Set::classicExtract( $apre, 'Comiteapre.datecomite' ) );

            ///Traduction des noms de table en libellés de l'aide
            foreach( $apre['Apre']['Natureaide'] as $i => $aides ){
                $apre['Apre']['Natureaide'][$i] = Set::enum( $aides, $natureAidesApres );
            }
            $apre['Apre']['Natureaide'] = '  '.implode( "\n  - ", $apre['Apre']['Natureaide'] )."\n";


            $etatliquidatif = $this->Etatliquidatif->findById( $etatliquidatif_id, null, null, -1 );
            $etatliquidatif['Etatliquidatif']['datecloture'] = date_short( Set::classicExtract( $etatliquidatif, 'Etatliquidatif.datecloture' ) );
            $apre = Set::merge( $apre, $etatliquidatif );


// debug( Set::flatten( $apre, '_' ) );



            if( $typeapre == 'F' ) {
                $this->Gedooo->generate( $apre, 'APRE/apreforfaitaire.odt' );
            }
            else if( $typeapre == 'C' && $dest == 'tiersprestataire' ) {
                // FIXME: dans le modèle
                $apre['Apre']['pourcentallocation'] = round( Set::classicExtract( $apre, 'Apre.allocation' ) / Set::classicExtract( $apre, 'Apre.montantaverser' ) * 100, 0 );
                //$apre['Apre']['restantallocation'] = number_format( Set::classicExtract( $apre, 'Apre.montantaverser' ) - Set::classicExtract( $apre, 'Apre.allocation' ), 2 );
                $apre['Apre']['restantallocation'] = number_format( Set::classicExtract( $apre, 'Apre.montantdejaverse' ) - Set::classicExtract( $apre, 'Apre.montantaverser' ), 2 );
                $this->Gedooo->generate( $apre, 'APRE/Paiement/paiement_'.$dest.'.odt' );
            }
            else if( $typeapre == 'C' && $dest == 'beneficiaire' ) {
                // FIXME: dans le modèle
                $apre['Apre']['pourcentallocation'] = round( Set::classicExtract( $apre, 'Apre.allocation' ) / Set::classicExtract( $apre, 'Apre.montantaverser' ) * 100, 0 );
                //$apre['Apre']['restantallocation'] = number_format( Set::classicExtract( $apre, 'Apre.montantaverser' ) - Set::classicExtract( $apre, 'Apre.allocation' ), 2 );
                $apre['Apre']['restantallocation'] = number_format( Set::classicExtract( $apre, 'Apre.montantdejaverse' ) - Set::classicExtract( $apre, 'Apre.montantaverser' ), 2 );
                $this->Gedooo->generate( $apre, 'APRE/Paiement/paiement_'.$typeformation.'_'.$dest.'.odt' );
            }
        }

        /**
        *
        **/
        function impressioncohorte( $id ) {
            $etatliquidatif = $this->{$this->modelClass}->findById( $id, null, null, -1 );

            $typeapre = ( ( Set::classicExtract( $etatliquidatif, 'Etatliquidatif.typeapre' ) == 'forfaitaire' ) ? 'F' : 'C' );

            $queryData = $this->Etatliquidatif->listeApresEtatLiquidatif(
                array(
                    'Apre.statutapre' => $typeapre // FIXME
                ),
                $id
            );

            $queryData['limit'] = 100;

            $this->{$this->modelClass}->Apre->unbindModelAll( false );
            $this->{$this->modelClass}->Apre->bindModel( array( 'hasOne' => array( 'ApreEtatliquidatif' ) ) );
            $this->paginate['Apre'] = $queryData;
            $apres = $this->paginate( 'Apre' );
            $params = array_multisize( $this->params['named'] );

            //------------------------------------------------------------------

            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();

            if( $typeapre  == 'C' ) {
                    $apre_etatliquidatif = $this->ApreEtatliquidatif->find(
                        'all',
                        array(
                            'conditions' => array(
                                'ApreEtatliquidatif.etatliquidatif_id' => $id,
                                'ApreEtatliquidatif.apre_id' => Set::extract( $apres, '/Apre/id' )
                            )
                        )
                    );
                    $this->set( 'apre_etatliquidatif', $apre_etatliquidatif );
                    $apres= Set::merge( $apres, $apre_etatliquidatif );
            }

            foreach( $apres as $key => $datas ) {
                unset( $datas['Apre']['Piecemanquante'] );
                unset( $datas['Apre']['Piecepresente'] );
                unset( $datas['Apre']['Piece'] );
                unset( $datas['Pieceapre'] );
                unset( $datas['Comiteapre'] );
                unset( $datas['Relanceapre'] );
//                 unset( $datas['Apre']['Natureaide'] );

                if( $datas['Apre']['statutapre'] == 'F' ) {
                    $datas['Apre']['allocation'] = $datas['Apre']['mtforfait'];
                }
                else if( $datas['Apre']['statutapre'] == 'C' ) {
                    $datas['Apre']['allocation'] = $datas['ApreEtatliquidatif']['montantattribue'];
                }
                else {
                    $this->cakeError( 'error500' );
                }

////////////////////////////////////////////////////////////////////////////////////////
                $aidesApre = array();
                $modelsFormation = array( 'Formqualif', 'Formpermfimo', 'Permisb', 'Actprof' );
                $modelLie = Set::classicExtract( $datas, 'Apre.Natureaide' );
    //             debug($modelLie);
                foreach( $modelLie as $natureaide => $nombre ) {
                    if( $nombre > 0 ) {
                        $aidesApre = $natureaide;
                        if( in_array( $natureaide, $modelsFormation ) ){
                            $dest = 'tiersprestataire';
                        }
                        else{
                            $dest = 'beneficiaire';
                        }
                    }
                }
////////////////////////////////////////////////////////////////////////////////////////



                /*
                    Traduction des codes en français
                */

                $datas['Personne']['qual'] = Set::enum( Set::classicExtract( $datas, 'Personne.qual' ),$qual );
                $datas['Adresse']['typevoie'] = Set::enum( Set::classicExtract( $datas, 'Adresse.typevoie' ),$typevoie );
//                 $datas['Apre']['paiement'] = $dest;
                $apres[$key] = $datas;
            }

            //------------------------------------------------------------------
// $dests = ( Set::extract( $apres, '/Apre/paiement' ) );
//
// debug($typeapre);
// die();
            if( $typeapre  == 'F' ) {
                $this->Gedooo->generateCohorte( 'forfaitaire', $apres, 'APRE/apreforfaitaire.odt', null );
            }
            else if( $typeapre  == 'C' && !empty( $dest ) ) {
                if( $dest == 'tiersprestataire' ) {
                    $this->Gedooo->generateCohorte( 'etatliquidatif_tiers', $apres, 'APRE/Paiement/paiement_'.$dest.'.odt', null );
                }
                else if( $dest == 'beneficiaire' ) {
                    $this->Gedooo->generateCohorte( 'apreforfaitaire', $apres, 'APRE/Paiement/paiement_'.$dest.'.odt', null );
                }
            }
        }

		/**
		*
		*/

		function validation( $id = null ) {
			$etatliquidatif = $this->{$this->modelClass}->findById( $id, null, null, -1 );
			$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

			// État liquidatif pas encore validé
			if( !empty( $etatliquidatif['Etatliquidatif']['datecloture'] ) ) {
				$this->Session->setFlash( __( 'Impossible de valider un état liquidatif déjà validé.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index' ) );
			}

			// État liquidatif sans APRE ?
			// FIXME: doit-il y avoir obligatoirement des apres dans un état liquidatif
			$nApres = $this->{$this->modelClass}->ApreEtatliquidatif->find( 'count', array( 'conditions' => array( 'ApreEtatliquidatif.etatliquidatif_id' => $id ) ) );
			if( $nApres == 0 ) {
				$this->Session->setFlash( __( 'Impossible de valider un état liquidatif n\'étant associé à aucune APRE.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index' ) );
			}

			// TODO -> dans le modèle
			$this->{$this->modelClass}->Apre->unbindModelAll();
			$montanttotalapre = $this->{$this->modelClass}->Apre->find(
				'all',
				array(
					'fields' => array(
						'Apre.mtforfait',
                        'ApreEtatliquidatif.montantattribue',
					),
					'joins' => array(
						array(
							'table'      => 'apres_etatsliquidatifs',
							'alias'      => 'ApreEtatliquidatif',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Apre.id = ApreEtatliquidatif.apre_id' )
						),
					),
					'recursive' => 1,
					'conditions' => array( 'ApreEtatliquidatif.etatliquidatif_id' => $id ),
				)
			);

			$etatliquidatif['Etatliquidatif']['datecloture'] = date( 'Y-m-d' );

            if( $etatliquidatif['Etatliquidatif']['typeapre'] == 'forfaitaire' ) {
                $etatliquidatif['Etatliquidatif']['montanttotalapre'] = array_sum( Set::extract( $montanttotalapre, '/Apre/mtforfait' ) );
            }
            else if( $etatliquidatif['Etatliquidatif']['typeapre'] == 'complementaire' ) {
                $etatliquidatif['Etatliquidatif']['montanttotalapre'] = array_sum( Set::extract( $montanttotalapre, '/ApreEtatliquidatif/montantattribue' ) );
            }
            else {
                $this->cakeError( 'error500' );
            }

			$this->{$this->modelClass}->create( $etatliquidatif );
			if( $this->{$this->modelClass}->save() ) {
				$this->Session->setFlash( __( 'Enregistrement effectué', true ), 'flash/success' );
				$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
			}
		}

		/**
		*
		*/

		function hopeyra( $id = null ) {
			$etatliquidatif = $this->{$this->modelClass}->findById( $id, null, null, -1 );
			$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

			// État liquidatif pas encore validé
			if( empty( $etatliquidatif['Etatliquidatif']['datecloture'] ) ) {
				$this->Session->setFlash( __( 'Impossible de générer le fichier HOPEYRA pour un état liquidatif pas encore validé.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index' ) );
			}

            $apres = $this->{$this->modelClass}->hopeyra( $id, $etatliquidatif['Etatliquidatif']['typeapre'] );

            $this->set( compact( 'apres' ) );

            $this->render( null, 'ajax' ); // FIXME: pas ajax
		}

		/**
		*   PDF pour les APREs Forfaitaires
		*/

		function pdf( $id = null ) {
			$etatliquidatif = $this->{$this->modelClass}->findById( $id, null, null, 0 );
			$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

			// État liquidatif pas encore validé
			if( empty( $etatliquidatif['Etatliquidatif']['datecloture'] ) ) {
				$this->Session->setFlash( __( 'Impossible de générer le fichier PDF pour un état liquidatif pas encore validé.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index' ) );
			}

            $elements = $this->{$this->modelClass}->pdf( $id, $etatliquidatif['Etatliquidatif']['typeapre'] );

            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();

            $this->set( compact( 'elements', 'etatliquidatif', 'qual', 'typevoie' ) );

			Configure::write( 'debug', 0 );
		}

        /**
        *
        */

        function ajaxmontant( $etatliquidatif_id, $apre_id, $index ) { // FIXME
            Configure::write( 'debug', 0 );
            $nbpaiementsouhait = $this->data['Apre'][$index]['nbpaiementsouhait'];

            $queryData = $this->Etatliquidatif->listeApresEtatLiquidatifNonTermine( array( 'Apre.statutapre' => 'C', 'Apre.id' => $apre_id ), $etatliquidatif_id );
            $queryData['recursive'] = -1;

            $apre = $this->Apre->find( 'first', $queryData );

            $apre_etatliquidatif = $this->ApreEtatliquidatif->find(
                'first',
                array(
                    'conditions' => array(
                        'ApreEtatliquidatif.etatliquidatif_id' => $etatliquidatif_id,
                        'ApreEtatliquidatif.apre_id' => $apre_id
                    )
                )
            );
            $this->set( 'apre_etatliquidatif', $apre_etatliquidatif );

            // Calcul -> FIXME: dans le modèle
            $montanttotal = Set::classicExtract( $apre, 'Apre.montantaverser' );
            if( $nbpaiementsouhait == 1 ) {
                /*$apre['Apre']['montantaverser'] = */$apre_etatliquidatif['ApreEtatliquidatif']['montantattribue'] /*= $apre['Apre']['montantdejaverse']*/ = $montanttotal;
            }
            else if( $nbpaiementsouhait == 2 ) {
//                 /*$apre['Apre']['montantaverser'] = */$apre_etatliquidatif['ApreEtatliquidatif']['montantattribue'] = 40 * ( Set::classicExtract( $apre, 'Apre.montantaverser' ) ) / 100;

            /**
            *   INFO: remplacement du pourcentage de 40 -> 60 % pour les versements en 2 fois (du coup ajout d'un paramétrage)
            */
                $apre_etatliquidatif['ApreEtatliquidatif']['montantattribue'] = Configure::read( 'Apre.pourcentage.montantversement' ) * ( Set::classicExtract( $apre, 'Apre.montantaverser' ) ) / 100;
            }

            $nbpaiementsouhait = array( '1' => 1, '2' => 2 );// FIXME: dans le modèle et au pluriel

            $apre = Set::merge( $apre, $apre_etatliquidatif );
            $this->set( 'apre', $apre );

            $this->set( 'i', $index );
            $this->set( 'nbpaiementsouhait', $nbpaiementsouhait );

            $this->render( null, 'ajax' );
        }

        /*function test() {
            $this->data = array(
                0 => array(
                    'Apre' => array(
                        'id' => 126,
                        'personne_id' => 1,
                        'nbpaiementsouhait' => 2,
                    ),
                    'ApreEtatliquidatif' => array (
                        'id' => 183,
                        'etatliquidatif_id' => 87,
                        'apre_id' => 126,
                        'montanttotal' => 1542,
                        'montantattribue' => 1//'choucroute'
                    )
                ),
            );

            debug( $this->data );
            $this->ApreEtatliquidatif->begin();

            //$this->ApreEtatliquidatif->create( $this->data );
            debug( $this->ApreEtatliquidatif->saveAll( $this->data, array( 'atomic' => false ) ) );

            $this->ApreEtatliquidatif->rollback();

            //debug( $this->ApreEtatliquidatif->validate );
            debug( $this->ApreEtatliquidatif->validationErrors );
        }*/

        /**
        *
        */

        function versementapres( $id = null ) {
            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }

            $etatliquidatif = $this->{$this->modelClass}->findById( $id, null, null, -1 );
            $this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

            $nbpaiementsouhait = array( '1' => 1, '2' => 2 );
            $this->set( 'nbpaiementsouhait', $nbpaiementsouhait );


            $queryData = $this->Etatliquidatif->listeApresEtatLiquidatifNonTerminePourVersement( array( 'Apre.statutapre' => 'C' ), $id );
            $queryData['limit'] = 100;

            $this->{$this->modelClass}->Apre->unbindModelAll( false );
            $this->paginate['Apre'] = $queryData;
            $apres = $this->paginate( 'Apre' );
            $this->set( compact( 'apres', 'queryData' ) );


            if( !empty( $this->data ) ) {
                $this->ApreEtatliquidatif->begin();

                $apre_ids = Set::extract( $this->data, '/ApreEtatliquidatif/apre_id' );
                $apres = Set::extract( $this->data, '/Apre' );
                $this->data = Set::extract( $this->data, '/ApreEtatliquidatif' );
                $return = $this->ApreEtatliquidatif->saveAll( $this->data, array( 'atomic' => false ) );
                if( $return ) {
                    // FIXME: dans le afterSave de ApreEtatliquidatif ?
                    $return = $this->Apre->saveAll( $apres, array( 'atomic' => false ) );
                    if( $return ) {
                        $this->Apre->calculMontantsDejaVerses( $apre_ids );
                        $this->ApreEtatliquidatif->commit();
                        $this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );

                    }
                    else {
                        $this->ApreEtatliquidatif->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
                else {
                    $this->ApreEtatliquidatif->rollback();
                    $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                }

                /*if( !empty( $this->data['Apre'] ) && !empty( $this->data['ApreEtatliquidatif'] ) ) {

                    $valid = $this->Dossier->Foyer->Personne->Apre->saveAll( $this->data['Apre'], array( 'validate' => 'only', 'atomic' => false ) );
                    $valid = $this->ApreEtatliquidatif->saveAll( $this->data['ApreEtatliquidatif'], array( 'validate' => 'only', 'atomic' => false ) ) && $valid;

debug($valid);
                    if( $valid ) {
                        $data = Set::extract( $this->data, '/Apre' );
                        $dataApreLiquidatif = Set::extract( $this->data, '/ApreEtatliquidatif' );

                        $saved = $this->Dossier->Foyer->Personne->Apre->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) );
                        $saved = $this->ApreEtatliquidatif->saveAll( $dataApreLiquidatif, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;

                        $apre_ids = Set::extract( $this->data, '/Apre/id' );
                        $saved = $this->Apre->calculMontantsDejaVerses( $apre_ids ) && $saved;
debug($saved);
                        //Mise à jour des montants déjà versés pour chacune des APREs
                        if( $saved ) {

                            $this->Dossier->commit();
                            $this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
                        }
                        else {
                            $this->Dossier->rollback();
                            $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                        }
                    }
                }*/
            }
        }
	}
?>