<?php
    class CohortesController extends AppController
    {
        var $name = 'Cohortes';
        var $uses = array( 'Dossier', 'Structurereferente', 'Option', 'Ressource', 'Adresse', 'Typeorient', 'Structurereferente', 'Contratinsertion' );

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
                else if( $dfderact >= strtotime( '-24 months' ) ) {
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
                    $codes = Set::extract( $dspf, 'Nataccosocfam.{n}.code' );
                    if( in_array( '0410', $codes, true ) || in_array( '0411', $codes, true ) ) {
                        $propo_algo = 'Social';
                    }
                }
            }

            return $propo_algo;
        }

        function orientees() {
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

            $orientes = $this->Dossier->Foyer->Personne->Orientstruct->find(
                'list',
                array(
                    'fields' => array(
                        'Orientstruct.personne_id',
                        'Orientstruct.personne_id'
                    ),
                    'conditions' => array( 'Orientstruct.statut_orient' => 'Orienté' ),
                )
            );

            $cohorte = array();
            // INFO: 190509 - grille test web-rsa.doc
            if( !empty( $orientes ) ) {
                $cohorte = $this->Dossier->Foyer->Personne->find(
                    'all',
                    array(
                    'conditions' => array( 'Personne.id' => array_values( $orientes ) ),
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

                    unset( $cohorte[$key]['Foyer']['Adressefoyer'] );
                    unset( $cohorte[$key]['Foyer']['AdressesFoyer'] ); // FIXME
                    $cohorte[$key]['Foyer']['Adresse'] = $adresse['Adresse'];

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

            }
            $this->set( 'cohorte', $cohorte );
        }

        /**

        */
        function nouvelles() {
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
        }
    }
?>