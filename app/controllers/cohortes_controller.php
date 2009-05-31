<?php
    class CohortesController extends AppController
    {
        var $name = 'Cohortes';
        var $uses = array( 'Dossier', 'Structurereferente', 'Option', 'Ressource', 'Adresse', 'Typeorient', 'Structurereferente', 'Contratinsertion', 'Detaildroitrsa', 'Zonegeographique', 'Adressefoyer' );

        //*********************************************************************

        /**
        */
        function _preOrientation( $element ) {
            $propo_algo = 'Préprofessionnelle';

            $dspp = array_filter( $element['Dspp'] );
            if( !empty( $dspp ) ) { // FIXME
                list( $year, $month, $day ) = explode( '-', $element['Dspp']['dfderact'] );
                $dfderact = mktime( 0, 0, 0, $month, $day, $year );
                // Préprofessionnelle, Social
                // 1°) Passé professionnel ? -> Emploi
                //     1901 : Vous avez toujours travaillé
                //     1902 : Vous travaillez par intermittence
                if( $element['Dspp']['hispro'] == '1901' || $element['Dspp']['hispro'] == '1902' ) {
                    $propo_algo = 'Emploi';
                }
                // 2°) Etes-vous accompagné dans votre recherche d'emploi ?
                //     1802 : Pôle Emploi
                else if( $element['Dspp']['accoemploi'] == '1802' ) {
                    $propo_algo = 'Emploi';
                }
                // 3°) Êtes-vous sans activité depuis moins de 24 mois ?
                //     Date éventuelle de cessation d’activité ?
                else if( ( !empty( $element['Dspp']['dfderact'] ) ) && ( $dfderact < strtotime( '-24 months' ) ) ) {
                    $propo_algo = 'Emploi';
                }
                // Votre famille fait-elle l’objet d’un accompagnement ?
                //     0410: Logement
                //     0411: Endettement
                //     FIXME: Santé
                else {
                    $dspf = $this->Dossier->Foyer->Dspf->find(
                        'first',
                        array(
                            'conditions' => array( 'Dspf.id' => $element['Foyer']['Dspf']['id'] )
                        )
                    );
                    // FIXME: grosse requête pour pas grand-chose
//                     $codes = Set::extract( $dspf, 'Nataccosocfam.{n}.code' );
                    if( $element['Foyer']['Dspf']['accosocfam'] == true ) {
                        $propo_algo = 'Social';
                    }
                    else {
                        $propo_algo = 'Préprofessionnelle';
                    }
                }
            }

            return $propo_algo;
        }

        //*********************************************************************

        // FIXME: performances, nommage
        function index( $statutOrientation = null ) {
            $mesCodesInsee = $this->Zonegeographique->find(
                'list',
                array(
                    'fields' => array(
                        'Zonegeographique.id',
                        'Zonegeographique.codeinsee'
                    ),
                    'conditions' => array( 'Zonegeographique.id' => $this->Session->read( 'Auth.Zonegeographique' ) )
                )
            );


            $this->assert( !empty( $statutOrientation ), 'error404' );
            $this->set( 'oridemrsa', $this->Option->oridemrsa() );

            $filtreFoyers = array();
            if( !empty( $this->data ) ) {
                // Tentative de sauvegarde des orientations
                // FIXME: la structure est obligatoire pour les dossiers validés, sinon afficher une erreur
                if( !empty( $this->data['Orientstruct'] ) ) {
                    $valid = $this->Dossier->Foyer->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'only' ) );
                    if( $valid ) {
                        $this->Dossier->begin();
                        $saved = true;
                        foreach( $this->data['Orientstruct'] as $key => $value ) {
                            // FIXME: date_valid et pas date_propo ?
                            if( $statutOrientation == 'Non orienté' ) {
                                $this->data['Orientstruct'][$key]['date_propo'] = date( 'Y-m-d' );
                            }
                            $this->data['Orientstruct'][$key]['date_valid'] = date( 'Y-m-d' );
                            $saved = $this->Dossier->Foyer->Personne->Orientstruct->save( $this->data['Orientstruct'][$key] ) && $saved;
                        }
                        if( $saved ) {
                            $this->Dossier->commit();
                        }
                        else {
                            $this->Dossier->rollback();
                        }
                    }
                }
                // Recherche suivant les critères de filtre
                else {
                    $filters = array();
                    // Critères sur le dossier - date de demande
                    if( isset( $this->data['Filtre']['dtdemrsa'] ) && !empty( $this->data['Filtre']['dtdemrsa'] ) ) {
                        $valid_from = ( valid_int( $this->data['Filtre']['dtdemrsa_from']['year'] ) && valid_int( $this->data['Filtre']['dtdemrsa_from']['month'] ) && valid_int( $this->data['Filtre']['dtdemrsa_from']['day'] ) );
                        $valid_to = ( valid_int( $this->data['Filtre']['dtdemrsa_to']['year'] ) && valid_int( $this->data['Filtre']['dtdemrsa_to']['month'] ) && valid_int( $this->data['Filtre']['dtdemrsa_to']['day'] ) );
                        if( $valid_from && $valid_to ) {
                            $filters[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $this->data['Filtre']['dtdemrsa_from']['year'], $this->data['Filtre']['dtdemrsa_from']['month'], $this->data['Filtre']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $this->data['Filtre']['dtdemrsa_to']['year'], $this->data['Filtre']['dtdemrsa_to']['month'], $this->data['Filtre']['dtdemrsa_to']['day'] ) ).'\'';
                        }
                    }
                    // FIXME: seulement si limitation plus haut
                    $filtreDossiersIds = $this->Dossier->find(
                        'list',
                        array(
                            'fields' => array(
                                'Dossier.id',
                                'Dossier.id'
                            ),
                            'conditions' => $filters
                        )
                    );

                    if( isset( $this->data['Filtre']['oridemrsa'] ) ) {
                        $conditions = array();
                        if( !empty( $filtreDossiersIds ) ) {
                            $conditions['Detaildroitrsa.dossier_rsa_id'] = $filtreDossiersIds;
                        }
                        if( !empty( $this->data['Filtre']['oridemrsa'] ) ) {
                            $conditions['Detaildroitrsa.oridemrsa'] = array_values( $this->data['Filtre']['oridemrsa'] );
                        }

                        $filtreDossiersIds = $this->Detaildroitrsa->find(
                            'list',
                            array(
                                'fields' => array(
                                    'Detaildroitrsa.dossier_rsa_id',
                                    'Detaildroitrsa.dossier_rsa_id'
                                ),
                                'conditions' => $conditions
                            )
                        );
                    }
                }

                if( !empty( $filtreDossiersIds ) ) {
                    $filtreFoyers = $this->Foyer->find(
                        'list',
                        array(
                            'fields' => array(
                                'Foyer.dossier_rsa_id',
                                'Foyer.dossier_rsa_id'
                            ),
                            'conditions' => array(
                                'Foyer.dossier_rsa_id' => $filtreDossiersIds
                            )
                        )
                    );
                }
            }

            $xFoyers = $this->Adressefoyer->find(
                'list',
                array(
                    'fields' => array(
                        'Adressefoyer.id',
                        'Adressefoyer.foyer_id'
                    ),
                    'conditions' => array(
                        'Adresse.numcomptt'  => ( !empty( $mesCodesInsee ) ? $mesCodesInsee : null ),
                        'Adressefoyer.rgadr' => '01'
                    ),
                    'recursive' => 2
                )
            );

            //-----------------------------------------------------------------

            $typesOrient = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient'
                    )
                )
            );
            $this->set( 'typesOrient', $typesOrient );

            //-----------------------------------------------------------------

            $personnesIds = array();
            if( !empty( $filtreFoyers ) ) {
                $personnesIds = $this->Dossier->Foyer->Personne->find(
                    'list',
                    array(
                        'fields' => array(
                            'Personne.id',
                            'Personne.id'
                        ),
                        'conditions'    => array(
                            'Personne.foyer_id' => $filtreFoyers
                        ),
                        'recursive'     => -1
                    )
                );
            }

            //-----------------------------------------------------------------

            $conditions = array( 'Orientstruct.statut_orient' => $statutOrientation );

            if( !empty( $personnesIds ) ) { // FIXME: si c'est vide -> rien!?!
                $conditions['Orientstruct.personne_id'] = $personnesIds;
            }

            $personnesIds = $this->Dossier->Foyer->Personne->Orientstruct->find(
                'list',
                array(
                    'fields' => array(
                        'Orientstruct.personne_id',
                        'Orientstruct.personne_id'
                    ),
                    'conditions'    => $conditions,
                    'recursive'     => -1
                )
            );

            // Ces personnes sont-elles soumises à droits et devoirs ?
            if( !empty( $personnesIds ) ) {
                // FIXME -> est-ce que mtpersressmenrsa existe bien -> à l'importation des données, faire la moyenne ?
                $personnesIds = $this->Ressource->find(
                    'list',
                    array(
                        'fields' => array(
                            'Ressource.personne_id',
                            'Ressource.personne_id'
                        ),
                        'conditions' => array(
                            '"Ressource.mtpersressmenrsa" <' => 500,
                            'Ressource.personne_id' => array_values( $personnesIds ),
                        ),
                        'recursive' => -1
                    )
                );
            }

            //-----------------------------------------------------------------

            $cohorte = array();
            // INFO: 190509 - grille test web-rsa.doc
            if( !empty( $personnesIds ) ) {
                $conditions = array(
                    'Personne.id' => ( !empty( $personnesIds ) ? array_values( $personnesIds ) : null ),
                    'Personne.foyer_id' => ( !empty( $xFoyers ) ? array_values( $xFoyers ) : null )
                );

                $cohorte = $this->Dossier->Foyer->Personne->find(
                    'all',
                    array(
                    'conditions' => $conditions,
                        'recursive' => 2
                    )
                );

                foreach( $cohorte as $key => $element ) {
                    // Dossier
                    $dossier = $this->Dossier->find(
                        'first',
                        array(
                            'conditions' => array( 'Dossier.id' => $element['Foyer']['dossier_rsa_id'] ),
                            'recursive' => 1
                        )
                    );
                    $cohorte[$key] = Set::merge( $cohorte[$key], $dossier );

                    // Adresse -> FIXME ?
                    $adresses = Set::combine( $cohorte[$key]['Foyer']['Adressefoyer'], '{n}.rgadr', '{n}.adresse_id' );
                    $adresse = $this->Adresse->find(
                        'first',
                        array(
                            'conditions' => array(
                                'Adresse.id' => $adresses['01']
                            ),
                            'recursive' => 2
                        )
                    );

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
                    }

                    unset( $cohorte[$key]['Foyer']['Adressefoyer'] );
                    unset( $cohorte[$key]['Foyer']['AdressesFoyer'] ); // FIXME
                    $cohorte[$key]['Foyer']['Adresse'] = $adresse['Adresse'];

                    if( $statutOrientation !== 'Orienté' ) {
                        $structuresReferentes = $this->Structurereferente->find(
                            'list',
                            array(
                                'fields' => array(
                                    'Structurereferente.id',
                                    'Structurereferente.lib_struc'
                                )
                            )
                        );
                        $this->set( 'structuresReferentes', $structuresReferentes );

                        $cohorte[$key]['Orientstruct']['propo_algo_texte'] = $this->_preOrientation( $element );
                        $tmp = array_flip( $typesOrient );
                        $cohorte[$key]['Orientstruct']['propo_algo'] = $tmp[$cohorte[$key]['Orientstruct']['propo_algo_texte']];
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
                        $cohorte[$key]['Dossier']['statut'] = 'Diminution des ressource';
                        if( !empty( $ressource ) ) {
                            list( $year, $month, $day ) = explode( '-', $cohorte[$key]['Dossier']['dtdemrsa'] );
                            $dateOk = ( mktime( 0, 0, 0, $month, $day, $year ) >= mktime( 0, 0, 0, 6, 1, 2009 ) );

                            if( $dateOk ) {
                                $cohorte[$key]['Dossier']['statut'] = 'Nouvelle demande';
                            }
                        }
                    }
                }

            }

            $this->set( 'cohorte', $cohorte );

            //-----------------------------------------------------------------

            switch( $statutOrientation ) {
                case 'En attente':
                    $this->set( 'pageTitle', 'Nouvelles demandes à orienter' );
                    $this->render( $this->action, null, 'nouvelles' );
                    break;
                case 'Non orienté':
                    $this->set( 'pageTitle', 'Demandes non orientées' );
                    $this->render( $this->action, null, 'nouvelles' );
                    break;
                case 'Orienté':
                    $this->set( 'pageTitle', 'Demandes orientées' );
                    $this->render( $this->action, null, 'orientees' );
                    break;
            }
        }

        /**

        */
        /*function nouvelles() {
            $typesOrient = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient'
                    ),
                    'conditions' => array(
                        'Typeorient.parentid' => null
                    )
                )
            );
            $this->set( 'typesOrient', $typesOrient );

            $structuresReferentes = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.id',
                        'Structurereferente.lib_struc'
                    )
                )
            );
            $this->set( 'structuresReferentes', $structuresReferentes );

            if( !empty( $this->data ) ) {
                $this->Dossier->begin();
                $saved = true;
                foreach( $this->data['Orientstruct'] as $key => $value ) {
                    $this->data['Orientstruct'][$key]['date_propo'] = date( 'Y-m-d' );
                    $saved = $this->Dossier->Foyer->Personne->Orientstruct->save( $this->data['Orientstruct'][$key] ) && $saved;
                }
                if( $saved ) {
                    $this->Dossier->commit();
                }
                else {
                    $this->Dossier->rollback();
                }
            }

            $nonOrientes = $this->Dossier->Foyer->Personne->Orientstruct->find(
                'list',
                array(
                    'fields' => array(
                        'Orientstruct.personne_id',
                        'Orientstruct.personne_id'
                    ),
                    'conditions' => array( 'Orientstruct.statut_orient' => 'Non orienté' ),
                )
            );

            $cohorte = array();
            // INFO: 190509 - grille test web-rsa.doc
            if( !empty( $nonOrientes ) ) {
                $cohorte = $this->Dossier->Foyer->Personne->find(
                    'all',
                    array(
                    'conditions' => array( 'Personne.id' => array_values( $nonOrientes ) ),
                        'recursive' => 2
                    )
                );
            }

            foreach( $cohorte as $key => $element ) {
                // Dossier
                $dossier = $this->Dossier->find(
                    'first',
                    array(
                        'conditions' => array( 'Dossier.id' => $element['Foyer']['dossier_rsa_id'] ),
                        'recursive' => 1
                    )
                );
                $cohorte[$key] = Set::merge( $cohorte[$key], $dossier );

                // Adresse -> FIXME ?
                $adresses = Set::combine( $cohorte[$key]['Foyer']['Adressefoyer'], '{n}.rgadr', '{n}.adresse_id' );
                $adresse = $this->Adresse->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Adresse.id' => $adresses['01']
                        ),
                        'recursive' => 2
                    )
                );

                unset( $cohorte[$key]['Foyer']['Adressefoyer'] );
                unset( $cohorte[$key]['Foyer']['AdressesFoyer'] ); // FIXME
                $cohorte[$key]['Foyer']['Adresse'] = $adresse['Adresse'];
                $cohorte[$key]['Orientstruct']['propo_algo_texte'] = $this->_preOrientation( $element );
                // FIXME
                $tmp = array_flip( $typesOrient );
                $cohorte[$key]['Orientstruct']['propo_algo'] = $tmp[$cohorte[$key]['Orientstruct']['propo_algo_texte']];
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
                $cohorte[$key]['Dossier']['statut'] = 'Diminution des ressource';

                if( !empty( $ressource ) ) {
                    list( $year, $month, $day ) = explode( '-', $cohorte[$key]['Dossier']['dtdemrsa'] );
                    $dateOk = ( mktime( 0, 0, 0, $month, $day, $year ) > mktime( 0, 0, 0, 6, 1, 2009 ) );

                    $mtpersressmenrsa = 0;
                    if( $ressource['Ressource']['topressnul'] != 0 && !empty( $ressource['Ressourcemensuelle'] ) ) {
                        // FIXME: à réparer
                        //debug( Set::extract( $ressource, 'Ressourcemensuelle.{n}.Detailressourcemensuelle.{n}.mtnatressmen' ) );
                        $mtpersressmenrsa = number_format( array_sum( Set::extract( $ressource, '{n}.Ressourcemensuelle.{n}.Detailressourcemensuelle.{n}.mtnatressmen' ) ) / 3, 2 );

                    }
                    if( $mtpersressmenrsa < 500 && $dateOk ) {
                        $dossiers[$key]['Dossier']['statut'] = 'Nouvelle demande';
                    }
                }
            }

            $this->set( 'cohorte', $cohorte );
        }*/
    }
?>