<?php
    class CohortesController extends AppController
    {
        var $name = 'Cohortes';
        var $uses = array( 'Dossier', 'Structurereferente', 'Option', 'Ressource', 'Adresse' );

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

        /**

        */
        function index() {
            $this->set( 'options2', $this->Structurereferente->list1Options() );
            $services = array( // FIXME
                1 => 'Emploi',
                4 => 'Préprofessionnelle',
                6 => 'Social',
            );
            $this->set( 'services', $services );

            // INFO: 190509 - grille test web-rsa.doc
            $cohorte = $this->Dossier->Foyer->Personne->find(
                'all',
                array(
                    'recursive' => 2
                )
            );

            foreach( $cohorte as $key => $element ) {
                if( empty( $cohorte[$key]['Orientstruct']['statut_orient'] ) ) { // FIXME: bonne condition ?
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
                    $cohorte[$key]['Orientstruct']['propo_algo'] = $this->_preOrientation( $element );
                    // FIXME
                    $tmp = array_flip( $services );
                    $cohorte[$key]['Dossier']['preorientation_id'] = $tmp[$cohorte[$key]['Orientstruct']['propo_algo']];
                    $cohorte[$key]['Orientstruct']['date_propo'] = date( 'Y-m-d' );

                    // Statut suivant ressource
                    $ressource = $this->Ressource->find(
                        'first',
                        array(
                            'conditions' => array(
                                'Ressource.personne_id' => $element['Personne']['id']
                            ),
                            'recursive' => -1
                        )
                    );
                    $cohorte[$key]['Dossier']['statut'] = 'Diminution des ressource';
                    if( !empty( $ressource ) ) {
                        list( $year, $month, $day ) = explode( '-', $cohorte[$key]['Dossier']['dtdemrsa'] );
                        $dateOk = ( mktime( 0, 0, 0, $month, $day, $year ) > mktime( 0, 0, 0, 6, 1, 2009 ) );
                        if( ( $ressource['Ressource']['mtpersressmenrsa'] / 3 ) < 500 && $dateOk ) {
                            $dossiers[$key]['Dossier']['statut'] = 'Nouvelle demande';
                        }
                    }
                }
            }

            $this->set( 'cohorte', $cohorte );
        }

        function xxx() {
            // TODO: par 15
            $services = array(
                1 => 'Association agréée',
                2 => 'Pôle Emploi',
                3 => 'Service Social du Département',
            );

            $this->set( 'options2', $this->Structurereferente->list1Options() );

            $dossiers = $this->Dossier->find(
                'all',
                array(
                    'fields' => array(
                        'Dossier.id',
                        'Dossier.numdemrsa',
                        'Personne.dtnai',
                        'Personne.nir',
                        'Dossier.dtdemrsa',
                        'Dossier.matricule',
                        'Adresse.codepos',
                        'Adresse.locaadr',
                        'Adresse.canton',
                        'Personne.id',
                        'Personne.nom',
                        'Personne.prenom',
                        'Contratinsertion.id',
                        'Structurereferente.id',
                        'Structurereferente.lib_struc',
                        // FIXME
                        'Suiviinstruction.etatirsa',
                        'Suiviinstruction.date_etat_instruction',
                        'Suiviinstruction.nomins',
                        'Suiviinstruction.prenomins',
                        'Suiviinstruction.numdepins',
                        'Suiviinstruction.typeserins',
                        'Suiviinstruction.numcomins',
                        'Suiviinstruction.numagrins'
                    ),
                    'joins' => array(
                        array(
                            'table' => 'foyers',
                            'alias' => 'Foyer',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'Dossier.id = Foyer.dossier_rsa_id' )
                        ),
                        array(
                            'table' => 'suivisinstruction',
                            'alias' => 'Suiviinstruction',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'Dossier.id = Suiviinstruction.dossier_rsa_id' )
                        ),
                        array(
                            'table' => 'adresses_foyers',
                            'alias' => 'AdresseFoyer',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'AdresseFoyer.foyer_id = Foyer.id', 'AdresseFoyer.rgadr = \'01\'' )
                        ),
                        array(
                            'table' => 'adresses',
                            'alias' => 'Adresse',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'AdresseFoyer.adresse_id = Adresse.id' )
                        ),
                        array(
                            'table' => 'personnes',
                            'alias' => 'Personne',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'Personne.foyer_id = Foyer.id', 'or' => array( 'Personne.rolepers = \'DEM\'', 'Personne.rolepers = \'CJT\'' ) )
                        ),
                        array(
                            'table' => 'contratsinsertion',
                            'alias' => 'Contratinsertion',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'Contratinsertion.personne_id = Personne.id' )
                        ),
                        array(
                            'table' => 'structuresreferentes',
                            'alias' => 'Structurereferente',
                            'type'  => 'LEFT OUTER',
                            'conditions' => array( 'Personne.id = Structurereferente.id' )
                        ),
                    ),
                    'recursive' => -1
                )
            );

            foreach( $dossiers as $key => $dossier ) {
                $ressource = $this->Ressource->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Ressource.personne_id' => $dossier['Personne']['id']
                        ),
                        'recursive' => -1
                    )
                );
                // FIXME!
                /**
                    Préorientation
                */
                $dossiers[$key]['Dossier']['statut'] = 'Diminution des ressource';
                if( !empty( $ressource ) ) {
                    list( $year, $month, $day ) = explode( '-', $dossier['Dossier']['dtdemrsa'] );
                    $dateOk = ( mktime( 0, 0, 0, $month, $day, $year ) > mktime( 0, 0, 0, 6, 1, 2009 ) );
                    if( ( $ressource['Ressource']['mtpersressmenrsa'] / 3 ) < 500 && $dateOk ) {
                        $dossiers[$key]['Dossier']['statut'] = 'Nouvelle demande';
                    }
                }

                $i = rand( 1, count( $services ) );
                $dossiers[$key]['Dossier']['preorientation'] = $services[$i];
                $dossiers[$key]['Dossier']['preorientation_id'] = $i;
            }
debug( $dossiers );
            $this->set( 'services', $services );
            $this->set( 'dossiers', $dossiers );
        }
    }
?>