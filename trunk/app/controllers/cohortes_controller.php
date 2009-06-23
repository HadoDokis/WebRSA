<?php
    class CohortesController extends AppController
    {
        var $name = 'Cohortes';
        var $uses = array( 'Cohorte', 'Dossier', 'Structurereferente', 'Option', 'Ressource', 'Adresse', 'Typeorient', 'Structurereferente', 'Contratinsertion', 'Detaildroitrsa', 'Zonegeographique', 'Adressefoyer', 'Dspf', 'Accoemploi', 'Personne' );

        //*********************************************************************

//         var $paginate = array(
//             // FIXME
//             'limit' => 20
//         );
//
//         /**
//         */
//         function __construct() {
//             $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
//             parent::__construct();
//         }

        //*********************************************************************

        function __construct() {
            parent::__construct();
            $this->components[] = 'Jetons';
        }

        /**
        *
        * INFO: Préprofessionnelle => Socioprofessionnelle --> mette un type dans la table ?
        *
        */
        function _preOrientation( $element ) {
            $propo_algo = 'Emploi';
            if( isset( $element['Dspp'] ) ) {
                $dspp = array_filter( $element['Dspp'] );
                if( !empty( $dspp ) && isset( $element['Dspp']['dfderact'] ) ) { // FIXME
                    $propo_algo = 'Socioprofessionnelle';
                    list( $year, $month, $day ) = explode( '-', $element['Dspp']['dfderact'] );
                    $dfderact = mktime( 0, 0, 0, $month, $day, $year );
                    // Socioprofessionnelle, Social
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
                            $propo_algo = 'Socioprofessionnelle';
                        }
                    }
                }
            }

            return $propo_algo;
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
            $this->assert( !empty( $statutOrientation ), 'error404' );
            $this->set( 'oridemrsa', $this->Option->oridemrsa() );
            $this->set( 'typeserins', $this->Option->typeserins() );
            $this->set( 'accoemplois', $this->Accoemploi->find( 'list' ) );

            // Un des formulaires a été renvoyé
            if( !empty( $this->data ) ) {

                //-------------------------------------------------------------

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

                //-------------------------------------------------------------

                /*$mesCodesInsee = $this->Zonegeographique->find( // FIXME -> voir Auth.zonegeographique
                    'list',
                    array(
                        'fields' => array(
                            'Zonegeographique.id',
                            'Zonegeographique.codeinsee'
                        ),
                        'conditions' => array( 'Zonegeographique.id' => array_keys( $this->Session->read( 'Auth.Zonegeographique' ) ) )
                    )
                );*/
                $mesCodesInsee = array_values( $this->Session->read( 'Auth.Zonegeographique' ) );

                // --------------------------------------------------------

                if( !empty( $this->data ) ) { // FIXME: déjà fait plus haut ?
// debug( $this->data );
                    if( !empty( $this->data['Orientstruct'] ) ) {
                        $valid = $this->Dossier->Foyer->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'only' ) );
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
// debug( $this->data['Orientstruct'] );
                            $saved = $this->Dossier->Foyer->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'first' ) );
                            if( $saved ) {
//                                 // FIXME ?
//                                 foreach( array_unique( Set::extract( $this->data, 'Orientstruct.{n}.dossier_id' ) ) as $dossier_id ) {
//                                     $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
//                                 }
//                                 $this->Dossier->commit();
//                             }
//                             else {
                                $this->Dossier->rollback();
                            }
                        }
                    }

                    // --------------------------------------------------------

                    $this->Dossier->begin(); // Pour les jetons

                    $_limit = 10;
                    $cohorte = $this->Cohorte->search( $statutOrientation, $mesCodesInsee, $this->data, $this->Jetons->ids(), $_limit );
                    $this->Dossier->Foyer->Personne->bindModel( array( 'hasOne' => array( 'Orientstruct' ) ) ); // FIXME
                    $cohorte = $this->Dossier->Foyer->Personne->find(
                        'all',
                        array(
                            'conditions' => array(
                                'Personne.id' => ( !empty( $cohorte ) ? $cohorte : null )
                            ),
                            'recursive' => 0,
                            'limit'     => $_limit
                        )
                    );
// echo '<pre>';
// var_dump( $cohorte );
// echo '</pre>';
                    // --------------------------------------------------------

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

                        if( $statutOrientation !== 'Orienté' ) {
//                             $structuresReferentes = $this->Structurereferente->find(
//                                 'list',
//                                 array(
//                                     'fields' => array(
//                                         'Structurereferente.id',
//                                         'Structurereferente.lib_struc'
//                                     )
//                                 )
//                             );
//                             $this->set( 'structuresReferentes', $structuresReferentes );
                            $this->set( 'structuresReferentes', $this->Structurereferente->list1Options() );
//debug( $cohorte );
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
                    $this->set( 'cohorte', $cohorte );
                }
                $this->Dossier->commit(); // Pour les jetons + FIXME: bloquer maintenant les ids dont on s'occupe
            }

            //-----------------------------------------------------------------

            if( ( $statutOrientation == 'En attente' ) || ( $statutOrientation == 'Non orienté' ) ) {
                // FIXME ?
                if( !empty( $cohorte ) && is_array( $cohorte ) ) {
                    foreach( array_unique( Set::extract( $cohorte, '{n}.Dossier.id' ) ) as $dossier_id ) {
                        $this->Jetons->get( array( 'Dossier.id' => $dossier_id ) );
                    }
                }
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
    }
?>