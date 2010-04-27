<?php
    @set_time_limit( 0 );
    //@ini_set( 'memory_limit', '128M' ); // INFO: à 100, ça casse -> headers 000
    //@ini_set( 'memory_limit', '256M' ); // INFO: à 100, ça casse -> headers 000
    @ini_set( 'memory_limit', '512M' );
    App::import('Sanitize');
    class CohortesController extends AppController
    {
        var $name = 'Cohortes';
        var $uses = array( 'Canton', 'Cohorte', 'Dossier', 'Structurereferente', 'Option', 'Ressource', 'Adresse', 'Typeorient', 'Structurereferente', 'Contratinsertion', 'Detaildroitrsa', 'Zonegeographique', 'Adressefoyer', 'Dsp', 'Accoemploi', 'Personne', 'Orientstruct', 'PersonneReferent', 'Referent' );
        var $helpers = array( 'Csv', 'Paginator', 'Ajax', 'Default' );
        var $components = array( 'Gedooo' );
        var $aucunDroit = array( 'progression' );

        var $paginate = array(
            // FIXME
            'limit' => 20,
        );
//
//        /**
//        */
//         function __construct() {
//             $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
//             parent::__construct();
//         }

        //*********************************************************************

        function __construct() {
            parent::__construct();
            $this->components[] = 'Jetons';
        }

        //*********************************************************************

        function nouvelles() {
            $this->_index( 'Non orienté' );
        }

        //---------------------------------------------------------------------

        function orientees() {
            $this->_index( 'Orienté' );
        }

        //---------------------------------------------------------------------

        function enattente() {
            $this->_index( 'En attente' );
        }

        //*********************************************************************


        /**
        */
        function _index( $statutOrientation = null ) {
            $this->assert( !empty( $statutOrientation ), 'invalidParameter' );
            $this->set( 'oridemrsa', $this->Option->oridemrsa() );
            $this->set( 'typeserins', $this->Option->typeserins() );
            $this->set( 'accoemplois', $this->Accoemploi->find( 'list' ) );
            $this->set( 'printed', $this->Option->printed() );
            $this->set( 'structuresAutomatiques', $this->Cohorte->structuresAutomatiques() );
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

            $this->set(
                'modeles',
                $this->Typeorient->find(
                    'list',
                    array(
                        'fields' => array( 'lib_type_orient' ),
                        'conditions' => array( 'Typeorient.parentid IS NULL' )
                    )
                )
            );
            //-------------------------------------------------------------

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );


            // Un des formulaires a été renvoyé
            if( !empty( $this->data ) ) {

                //-------------------------------------------------------------

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
                $this->set( 'typesOrient', $typesOrient );
                // --------------------------------------------------------

                if( !empty( $this->data ) ) { // FIXME: déjà fait plus haut ?
                    if( !empty( $this->data['Orientstruct'] ) ) { // Formulaire du bas
                        $valid = $this->Dossier->Foyer->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'only', 'atomic' => false ) );
                        $valid = ( count( $this->Dossier->Foyer->Personne->Orientstruct->validationErrors ) == 0 );
                        if( $valid ) {
                            $this->Dossier->begin();
                            foreach( $this->data['Orientstruct'] as $key => $value ) {
                                // FIXME: date_valid et pas date_propo ?
                                if( $statutOrientation == 'Non orienté' ) {
                                    $this->data['Orientstruct'][$key]['date_propo'] = date( 'Y-m-d' );
                                }
                                $this->data['Orientstruct'][$key]['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Orientstruct'][$key]['structurereferente_id'] );
                                $this->data['Orientstruct'][$key]['date_valid'] = date( 'Y-m-d' );
                            }
                            $saved = $this->Dossier->Foyer->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'first', 'atomic' => false ) );

							// Création des PDF pour les orientations effectives
                            if( $saved ) {
                                foreach( $this->data['Orientstruct'] as $element ) {
									if( $element['statut_orient'] == 'Orienté' ) {
										$saved = $this->Gedooo->mkOrientstructPdf( $element['id'] ) && $saved;
									}
								}
							}

                            if( $saved ) {
                                foreach( array_unique( Set::extract( $this->data, 'Orientstruct.{n}.dossier_id' ) ) as $dossier_id ) {
                                    $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
                                }
                                $this->Dossier->commit();
                                $this->data['Orientstruct'] = array();
                            }
                            else {
                                $this->Dossier->rollback();
                            }
                        }
                    }

                    // --------------------------------------------------------

                    $this->Dossier->begin(); // Pour les jetons

                    $_limit = 10;

                    $cohorte = $this->Cohorte->search( $statutOrientation, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids(), $_limit );

                    $count = count( $this->Cohorte->search( $statutOrientation, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() ) );
                    $this->set( 'count', $count );
                    $this->set( 'pages', ceil( $count / $_limit ) );

                    foreach( $cohorte as $orientstruct_id ) {
                        //$this->Jetons->get( array( 'Dossier.id' => $this->Orientstruct->dossierId( $orientstruct_id ) ) );
						$dossier_id = $this->Orientstruct->dossierId( $orientstruct_id );

						$this->Jetons->get( $dossier_id ); // TODO: vérifier ?
                    }

					if( !empty( $cohorte ) ) {
						$this->Dossier->Foyer->Personne->bindModel(
							array(
								'hasOne' => array( 'Dsp', 'Orientstruct' ),
								'belongsTo' => array( 'Foyer' )
							)
						);
						$cohorte = $this->Orientstruct->find(
							'all',
							array(
								'conditions' => array(
									'Orientstruct.id' => $cohorte
								),
								'recursive' => 2,
								'limit'     => $_limit
							)
						);
					}

                    // --------------------------------------------------------

					/// FIXME: beaucoup trop de requètes
                    foreach( $cohorte as $key => $element ) {
						$cohorte[$key]['Foyer'] = $element['Foyer'] = $element['Personne']['Foyer'];
						unset( $element['Personne']['Foyer'], $cohorte[$key]['Personne']['Foyer'] );

						// ----------------------------------------------------

                        // Dossier
                        $dossier = $this->Dossier->find(
                            'first',
                            array(
                                'conditions' => array( 'Dossier.id' => $element['Foyer']['dossier_rsa_id'] ),
                                'recursive' => 1
                            )
                        );
                        $cohorte[$key] = Set::merge( $cohorte[$key], $dossier );

                        // ----------------------------------------------------

                        // Adresse foyer
                        $adresseFoyer = $this->Adressefoyer->find(
                            'first',
                            array(
                                'conditions' => array(
                                    'Adressefoyer.foyer_id' => $element['Foyer']['id'],
                                    'Adressefoyer.rgadr'    => '01'
                                ),
                                'recursive' => 1
                            )
                        );
                        $cohorte[$key] = Set::merge( $cohorte[$key], array( 'Adresse' => $adresseFoyer['Adresse'] ) );

                        // ----------------------------------------------------

                        // Dsp ?
                        $dsp = $this->Dossier->Foyer->Personne->Dsp->find(
                            'count',
                            array(
                                'conditions' => array(
                                    'Dsp.personne_id' => $element['Personne']['id']
                                )
                            )
                        );
                        $cohorte[$key] = Set::merge( $cohorte[$key], array( 'Dsp' => $dsp ) );

                        // ----------------------------------------------------
                        // TODO: continuer le nettoyage à partir d'ici
                        if( $statutOrientation == 'Orienté' ) {
                            $contratinsertion = $this->Contratinsertion->find(
                                'first',
                                array(
                                    'conditions' => array(
                                        'Contratinsertion.personne_id' => $element['Personne']['id']
                                    ),
                                    'recursive' => -1,
                                    'order' => array( 'Contratinsertion.dd_ci DESC' )
                                )
                            );
                            $cohorte[$key]['Contratinsertion'] = $contratinsertion['Contratinsertion'];

                            $Structurereferente = $this->Structurereferente->find(
                                'first',
                                array(
                                    'conditions' => array(
                                        'Structurereferente.id' => $cohorte[$key]['Orientstruct']['structurereferente_id']
                                    )
                                )
                            );
                            $cohorte[$key]['Orientstruct']['Structurereferente'] = $Structurereferente['Structurereferente'];
                        }
                        else {
                            $this->set( 'structuresReferentes', $this->Structurereferente->list1Options() );

                            $cohorte[$key]['Orientstruct']['propo_algo_texte'] = $this->Cohorte->preOrientation( $element );

                            $tmp = array_flip( $typesOrient );
                            $cohorte[$key]['Orientstruct']['propo_algo'] = Set::enum( $cohorte[$key]['Orientstruct']['propo_algo_texte'], $tmp );
                            $cohorte[$key]['Orientstruct']['date_propo'] = date( 'Y-m-d' );

                            // Statut suivant ressource
                            $ressource = $this->Ressource->find(
                                'first',
                                array(
                                    'conditions' => array(
                                        'Ressource.personne_id' => $element['Personne']['id']
                                    ),
                                    'recursive' => 2
                                )
                            );
                            $cohorte[$key]['Dossier']['statut'] = 'Diminution des ressources';
                            if( !empty( $ressource ) ) {
                                list( $year, $month, $day ) = explode( '-', $cohorte[$key]['Dossier']['dtdemrsa'] );
                                $dateOk = ( mktime( 0, 0, 0, $month, $day, $year ) >= mktime( 0, 0, 0, 6, 1, 2009 ) );

                                if( $dateOk ) {
                                    $cohorte[$key]['Dossier']['statut'] = 'Nouvelle demande';
                                }
                            }
                        }
                    }
                    $this->set( 'cohorte', $cohorte );
                }

				if( ( $statutOrientation == 'En attente' ) || ( $statutOrientation == 'Non orienté' ) ) {
					if( !empty( $cohorte ) && is_array( $cohorte ) ) {
						foreach( array_unique( Set::extract( $cohorte, '{n}.Dossier.id' ) ) as $dossier_id ) {
							//$this->Jetons->get( array( 'Dossier.id' => $dossier_id ) );
							$this->Jetons->get( $dossier_id );
						}
					}
				}

                $this->Dossier->commit();
            }

            //-----------------------------------------------------------------

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}

            switch( $statutOrientation ) {
                case 'En attente':
                    $this->set( 'pageTitle', 'Nouvelles demandes à orienter' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Non orienté':
                    $this->set( 'pageTitle', 'Demandes non orientées' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Orienté': // FIXME: pas besoin de locker
                    $this->set( 'pageTitle', 'Demandes orientées' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }
        }

/************************************* Export des données en Xls *******************************/


      function exportcsv(){
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $_limit = 10;
            $params = $this->Cohorte->search( 'Orienté', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );

            unset( $params['limit'] );
            $cohortes = $this->Dossier->find( 'all', $params );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'cohortes' ) );
        }


        function _get( $personne_id ) {
            $this->Personne->unbindModel(
                array(
                    'hasMany' => array(
                        'Contratinsertion', 'Rendezvous', 'Referent'
                    ),
                    'hasAndBelongsToMany' => array(
                        'PersonneReferent'
                    )
                )
            );
//             $this->Personne->unbindModel(
//                 array(
//                     'hasMany' => array( 'Contratinsertion', 'Rendezvous' ),
//                     'hasOne' => array( 'Avispcgpersonne', 'Dsp', 'Dossiercaf', 'TitreSejour' )
//                 )
//             );
            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    )
                )
            );

            $contratinsertion = $this->Personne->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.personne_id' => $personne_id
                    ),
                    'order' => array(
                        'Contratinsertion.dd_ci DESC'
                    ),
                    'recursive' => -1
                )
            );
            $personne = Set::merge( $personne, $contratinsertion );

            // Récupération de l'adresse lié à la personne
            $this->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );
            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $personne['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $personne['Adresse'] = $adresse['Adresse'];

            // Récupération de l'utilisateur
            $user = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $this->Session->read( 'Auth.User.id' )
                    )
                )
            );
            $personne['User'] = $user['User'];

            // Récupération de la structure referente liée à la personne
            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        // 'Orientstruct.id' => $personne['Orientstruct']['id']
                        'Orientstruct.personne_id' => $personne['Personne']['id'] // FIXME
                    )
                )
            );
            $personne['Orientstruct'] = $orientstruct['Orientstruct'];
            $personne['Structurereferente'] = $orientstruct['Structurereferente'];

            //Ajout pour le numéro de poste du référent de la structure
            $referent = $this->Referent->find(
                'first',
                array(
                    'conditions' => array(
                        'Referent.structurereferente_id' => $personne['Structurereferente']['id']
                    ),
                    'recursive' => -1
                )
            );

            if( !empty( $referent['Referent'] ) ) {
                $personne['Referent'] = $referent['Referent'];
            }
            else if( !Set::check( $personne, 'Referent' ) ) {
                $personne['Referent'] = Set::normalize( array_keys( $this->Referent->schema() ) );
            }

            $personne_referent = $this->PersonneReferent->find(
                'first',
                array(
                    'conditions' => array(
                        'PersonneReferent.personne_id' => Set::classicExtract( $orientstruct, 'Personne.id' )
                    )
                )
            );
            $personne = Set::merge( $personne, $personne_referent );

			// ----------------------------------------------------

			// Dossier
			$dossier = $this->Dossier->find(
				'first',
				array(
					'conditions' => array( 'Dossier.id' => $personne['Foyer']['dossier_rsa_id'] ),
					'recursive' => -1
				)
			);

            $personne = Set::merge( $personne, $dossier );

			// ----------------------------------------------------

            return $personne;

        }

        /**
        *
        */

        function cohortegedooo( $personne_id = null ) {
			$this->Dossier->begin();

            $AuthZonegeographique = $this->Session->read( 'Auth.Zonegeographique' );
            if( !empty( $AuthZonegeographique ) ) {
                $AuthZonegeographique = array_values( $AuthZonegeographique );
            }
            else {
                $AuthZonegeographique = array();
            }

            $limit = Configure::read( 'nb_limit_print' );

            $cohorte = $this->Cohorte->search( 'Orienté', $AuthZonegeographique, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids(), $limit );

            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();

            $cohorteDatas = array();

			/// FIXME: supprimer si besoin dans UsersController::login et UsersController::logout

			$content = $this->Gedooo->getCohortePdfForClient(
				array(
					'conditions' => array(
						'modele' => 'Orientstruct',
						'Orientstruct.id' => $cohorte
					),
					'joins' => array(
						array(
							'table'      => 'orientsstructs',
							'alias'      => 'Orientstruct',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Pdf.fk_value = Orientstruct.id'
							)
						)
					)
				)
			);

			$success = ( $content !== false ) && $this->Orientstruct->updateAll(
				array( 'Orientstruct.date_impression' => date( "'Y-m-d'" ) ),
				array( 'Orientstruct.id' => $cohorte, 'Orientstruct.date_impression IS NULL' )
			);

			if( $content !== false ) { // date_impression
				$this->Dossier->commit();
				$this->Gedooo->sendPdfContentToClient( $content, sprintf( "cohorte-orientations-%s.pdf", date( "Ymd-H\hi" ) ) );
				die();
			}
			else {
				$this->Dossier->rollback();
				// redirect referer
				debug( $this->referer );
			}
        }
    }
?>