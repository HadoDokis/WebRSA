<?php
//     @ini_set( 'memory_limit', '128M' );
    App::import( 'Sanitize' );

    class DossiersController extends AppController
    {
        var $name = 'Dossiers';
        var $uses = array( 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Structurereferente', 'Orientstruct', 'Typeorient', 'Contratinsertion', 'Detaildroitrsa', 'Detailcalculdroitrsa', 'Option', 'Dspp', 'Dspf', 'Infofinanciere', 'Modecontact','Typocontrat', 'Creance', 'Adressefoyer', 'Dossiercaf', 'Serviceinstructeur', 'Jeton' , 'Referent');
        var $aucunDroit = array( 'menu' );

        var $paginate = array(
            // FIXME
            'limit' => 20
        );

        /**
        */
        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }

        /**
        */
        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'natpf', $this->Option->natpf() );
            $this->set( 'decision_ci', $this->Option->decision_ci() );
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
            $this->set( 'moticlorsa', $this->Option->moticlorsa() );
            $this->set( 'typeserins', $this->Option->typeserins() );
            $this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
            $this->set( 'typevoie', $this->Option->typevoie() );
            $this->set( 'sitfam', $this->Option->sitfam() );
            $this->set( 'couvsoc', $this->Option->couvsoc() );
            return $return;
        }

        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */
        function index() {
            $params = $this->data;
            if( !empty( $params ) ) {
                $filters = array();

                // Critères sur le dossier - numéro de dossier
                if( isset( $params['Dossier']['numdemrsa'] ) && !empty( $params['Dossier']['numdemrsa'] ) ) {
                    $filters[] = "Dossier.numdemrsa ILIKE '%".Sanitize::paranoid( $params['Dossier']['numdemrsa'] )."%'";
                }

                // Critères sur le dossier - date de demande
                if( isset( $params['Dossier']['dtdemrsa'] ) && !empty( $params['Dossier']['dtdemrsa'] ) ) {
                    $valid_from = ( valid_int( $params['Dossier']['dtdemrsa_from']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['day'] ) );
                    $valid_to = ( valid_int( $params['Dossier']['dtdemrsa_to']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['day'] ) );
                    if( $valid_from && $valid_to ) {
                        $filters[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $params['Dossier']['dtdemrsa_from']['year'], $params['Dossier']['dtdemrsa_from']['month'], $params['Dossier']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $params['Dossier']['dtdemrsa_to']['year'], $params['Dossier']['dtdemrsa_to']['month'], $params['Dossier']['dtdemrsa_to']['day'] ) ).'\'';
                    }
                }

                // Critères sur une personne du foyer - nom, prénom, nom de jeune fille
                $filtersPersonne = array();
                foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                    if( isset( $params['Personne'][$criterePersonne] ) && !empty( $params['Personne'][$criterePersonne] ) ) {
                        $filtersPersonne['Personne.'.$criterePersonne.' ILIKE'] = '%'.$params['Personne'][$criterePersonne].'%';
                    }
                }

                // Critères sur une personne du foyer - date de naissance
                if( isset( $params['Personne']['dtnai'] ) && !empty( $params['Personne']['dtnai'] ) ) {
                    if( valid_int( $params['Personne']['dtnai']['year'] ) ) {
                        $filtersPersonne['EXTRACT(YEAR FROM Personne.dtnai) ='] = $params['Personne']['dtnai']['year'];
                    }
                    if( valid_int( $params['Personne']['dtnai']['month'] ) ) {
                        $filtersPersonne['EXTRACT(MONTH FROM Personne.dtnai) ='] = $params['Personne']['dtnai']['month'];
                    }
                    if( valid_int( $params['Personne']['dtnai']['day'] ) ) {
                        $filtersPersonne['EXTRACT(DAY FROM Personne.dtnai) ='] = $params['Personne']['dtnai']['day'];
                    }
                }

                // Recherche des foyers suivant les critères sur les personnes
                if( count( $filtersPersonne ) > 0 ) {
                    $foyers = $this->Personne->find(
                        'list',
                        array(
                            'fields' => array(
                                'Personne.id',
                                'Personne.foyer_id',
                            ),
                            'conditions' => array( $filtersPersonne ),
                            'recursive' => -1
                        )
                    );
                    // Critères sur les dossiers suivant les numéros de foyers retournés
                    $filters[] = ( count( $foyers ) > 0 ) ? 'Foyer.id IN ( '.implode( ',', $foyers ).' )' : 'FALSE';
                }

                // INFO: seulement les dossiers qui sont dans ma zone géographique
                $filters['Dossier.id'] =  $this->Dossier->findByZones( $this->Session->read( 'Auth.Zonegeographique' ), $this->Session->read( 'Auth.User.filtre_zone_geo' ) );

                // Recherche
                $this->Dossier->recursive = 2;
                $dossiers = $this->paginate( 'Dossier', array( $filters ) );

                foreach( $dossiers as $key => $dossier ) {
                    // Personnes
                    foreach( $dossier['Foyer']['Personne'] as $iPersonne => $personne ) {
                        $dossier['Foyer']['Personne'][$iPersonne] = Set::merge( $dossier['Foyer']['Personne'][$iPersonne], $this->Personne->Prestation->findByPersonneId( $personne['id'], null, null, -1 ) );
                    }
// 'return strcmp( $a["Prestation"]["rolepers"], $b["Prestation"]["rolepers"] );'
                    usort( $dossier['Foyer']['Personne'], create_function( '$a,$b', 'if( $a["Prestation"]["rolepers"] == "DEM" ) return -1; else if( $b["Prestation"]["rolepers"] == "DEM" ) return 1; else return strcmp( $a["Prestation"]["rolepers"], $b["Prestation"]["rolepers"] );' ) );
// debug( $dossier['Foyer'] );

                    // Dernière adresse
                    $derniereadresse = array();
                    foreach( $dossier['Foyer']['Adressefoyer'] as $adressefoyer ) {
                        if( $adressefoyer['rgadr'] == '01' ) {
                            $adresse = $this->Adresse->find(
                                'first',
                                array(
                                    'conditions' => array(
                                        'Adresse.id' => $adressefoyer['adresse_id']
                                    ),
                                    'recursive' => -1
                                )
                            );
                            $derniereadresse['Adressefoyer'] = $adressefoyer;
                            $derniereadresse['Adresse'] = $adresse['Adresse'];
                        }
                    }
                    $dossiers[$key]['Derniereadresse'] = $derniereadresse;

                    // Dossier verrouillé
                    $dossiers[$key]['Dossier']['locked'] = $this->Jetons->locked( $dossier['Foyer']['dossier_rsa_id'] );
//                     $lock = $this->Jeton->find( 'list', array( 'conditions' => array( 'Jeton.dossier_id' => $dossier['Foyer']['dossier_rsa_id'] ) ) );
//                     if( !empty( $lock ) ) {
//                         $dossiers[$key]['Dossier']['locked'] = true;
//                     }
//                     else {
//                         $dossiers[$key]['Dossier']['locked'] = false;
//                     }
                }

                $this->set( 'dossiers', $dossiers );
                $this->data['Search'] = $params;
            }
        }

        /**
        */
        function menu() {
            // Ce n'est pas un appel par une URL
            $this->assert( isset( $this->params['requested'] ), 'error404' );

            $conditions = array();

            if( !empty( $this->params['id'] ) && is_numeric( $this->params['id'] ) ) {
                $conditions['"Dossier"."id"'] = $this->params['id'];
            }
            else if( !empty( $this->params['foyer_id'] ) && is_numeric( $this->params['foyer_id'] ) ) {
                $conditions['"Foyer"."id"'] = $this->params['foyer_id'];
            }
            else if( !empty( $this->params['personne_id'] ) && is_numeric( $this->params['personne_id'] ) ) {
                $personne = $this->Dossier->Foyer->Personne->find(
                    'first', array(
                        'conditions' => array(
                            'Personne.id' => $this->params['personne_id']
                        )
                    )
                );

                $this->assert( !empty( $personne ), 'invalidParameter' );

                $conditions['"Foyer"."id"'] = $personne['Personne']['foyer_id'];
            }

            $this->assert( !empty( $conditions ), 'invalidParameter' );

            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => $conditions,
                    'recursive'  => 2
                )
            );

            $this->assert( !empty( $dossier ), 'invalidParameter' );

            usort( $dossier['Foyer']['AdressesFoyer'], create_function( '$a,$b', 'return strcmp( $a["rgadr"], $b["rgadr"] );' ) );

            foreach( $dossier['Foyer']['AdressesFoyer'] as $key => $AdressesFoyer ) {
                $adresses = $this->Adresse->find(
                    'all',
                    array(
                        'conditions' => array(
                            'Adresse.id' => $AdressesFoyer['adresse_id'] )
                    )
                );
                $dossier['Foyer']['AdressesFoyer'][$key] = array_merge( $dossier['Foyer']['AdressesFoyer'][$key], $adresses[0] );
            }

            foreach( $dossier['Foyer']['Personne'] as $iPersonne => $personne ) {
                $this->Dossier->Foyer->Personne->unbindModelAll();
                $this->Dossier->Foyer->Personne->bindModel( array( 'hasOne' => array( 'Prestation' ) ));
                $prestation = $this->Dossier->Foyer->Personne->findById( $personne['id'] );
                $dossier['Foyer']['Personne'][$iPersonne]['Prestation'] = $prestation['Prestation'];
            }

            // Dossier verrouillé
//             $lock = $this->Jeton->find( 'list', array( 'conditions' => array( 'Jeton.dossier_id' => $dossier['Foyer']['dossier_rsa_id'] ) ) );
//             if( !empty( $lock ) ) {
//                 $dossier['Dossier']['locked'] = true;
//             }
//             else {
//                 $dossier['Dossier']['locked'] = false;
//             }
            $dossier['Dossier']['locked'] = $this->Jetons->locked( $dossier['Foyer']['dossier_rsa_id'] );

            return $dossier;
        }

        /**
        */
        function view( $id = null ) {
            $this->assert( valid_int( $id ), 'invalidParameter' );
            /**
                OK -> Dossier
                OK -> Foyer
                OK -> Situationdossierrsa
                OK -> Adresse
                OK -> Detaildroitrsa
                    OK -> Detailcalculdroitrsa
                OK -> Suiviinstruction
                OK -> Infofinanciere
                OK -> Creance
                OK -> Dossiercaf
                OK -> Personne (DEM/CJT)
                    OK -> Personne
                    OK -> Prestation
                    OK -> Orientstruct (premier/dernier)
                        //Typeorient
                    OK -> Dspp
                    OK -> Contratinsertion
            */
            $details = array();

            $tDossier = $this->Dossier->findById( $id, null, null, -1 );
            $details = Set::merge( $details, $tDossier );

            $tFoyer = $this->Dossier->Foyer->findByDossierRsaId( $id, null, null, -1 );
            $details = Set::merge( $details, $tFoyer );

            $tDetaildroitrsa = $this->Detaildroitrsa->findByDossierRsaId( $id, null, null, 1 );
            $details = Set::merge( $details, $tDetaildroitrsa );

            $tSituationdossierrsa = $this->Dossier->Situationdossierrsa->findByDossierRsaId( $id, null, null, -1 );
            $details = Set::merge( $details, $tSituationdossierrsa );

            // FIXME
//             $tCreance = $this->Dossier->Foyer->Creance->find(
//                 'first',
//                 array(
//                     'conditions' => array( 'Creance.foyer_id' => $id ),
//                     'recursive' => -1,
//                     'order' => array( 'Creance.dtimplcre DESC' )
//                 )
//             );
//             $details = Set::merge( $details, $tCreance );

            $tSuiviinstruction = $this->Dossier->Suiviinstruction->find(
                'first',
                array(
                    'conditions' => array( 'Suiviinstruction.dossier_rsa_id' => $id ),
                    'recursive' => -1,
                    'order' => array( 'Suiviinstruction.date_etat_instruction DESC' )
                )
            );
            $details = Set::merge( $details, $tSuiviinstruction );

            $tInfofinanciere = $this->Dossier->Infofinanciere->find(
                'first',
                array(
                    'conditions' => array( 'Infofinanciere.dossier_rsa_id' => $id ),
                    'recursive' => -1,
                    'order' => array( 'Infofinanciere.moismoucompta DESC' )
                )
            );
            $details = Set::merge( $details, $tInfofinanciere );

            $adresseFoyer = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $details['Foyer']['id'],
                        'Adressefoyer.rgadr'    => '01'
                    ),
                    'recursive' => 1
                )
            );
            $details = Set::merge( $details, array( 'Adresse' => $adresseFoyer['Adresse'] ) );

            /**
                Personnes
            */
            $bindPrestation = $this->Personne->hasOne['Prestation'];
            $this->Personne->unbindModelAll();
            $this->Personne->bindModel( array( 'hasOne' => array( 'Dossiercaf', 'Dspp', 'Prestation' => $bindPrestation ) ) );
            $personnesFoyer = $this->Personne->find(
                'all',
                array(
                    'conditions' => array(
                        'Personne.foyer_id' => $id,
                        'Prestation.rolepers' => array( 'DEM', 'CJT' )
                    ),
                    'recursive' => 0
                )
            );

            $roles = Set::extract( '{n}.Prestation.rolepers', $personnesFoyer );
            foreach( $roles as $index => $role ) {
                $tContratinsertion = $this->Contratinsertion->find(
                    'first',
                    array(
                        'conditions' => array( 'Contratinsertion.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
                        'recursive' => -1,
                        'order' => array( 'Contratinsertion.rg_ci DESC' )
                    )
                );
                $personnesFoyer[$index]['Contratinsertion'] = $tContratinsertion['Contratinsertion'];

                $tOrientstruct = $this->Orientstruct->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id']
                        ),
                        'order' => 'Orientstruct.date_valid ASC',
                        'recursive' => -1
                    )
                );
                $personnesFoyer[$index]['Orientstruct']['premiere'] = $tOrientstruct['Orientstruct'];

                $tOrientstruct = $this->Orientstruct->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id']
                        ),
                        'order' => 'Orientstruct.date_valid DESC',
                        'recursive' => -1
                    )
                );
                $personnesFoyer[$index]['Orientstruct']['derniere'] = $tOrientstruct['Orientstruct'];

                $details[$role] = $personnesFoyer[$index];
            }

/*debug( $details );*/

            $structuresreferentes = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $typesorient = $this->Typeorient->find( 'list', array( 'fields' => array( 'id', 'lib_type_orient' ) ) );
            $typoscontrat = $this->Typocontrat->find( 'list', array( 'fields' => array( 'id', 'lib_typo' ) ) );

            $this->set( 'structuresreferentes', $structuresreferentes );
            $this->set( 'typesorient', $typesorient );
            $this->set( 'typoscontrat', $typoscontrat );

            $this->set( 'details', $details );

// // -----------------------------------------------------------------------------
// 
//             $dsp = array();
//             // DSP foyer
// //             $dsp['foyer'] = $this->Dspf->find(
// //                 'first',
// //                 array(
// //                     'conditions' => array(
// //                         'Dspf.foyer_id' => $id
// //                     )
// //                 )
// //             ) ;
// 
//             // Dspf + Foyer
//             $dsp['foyer'] = $this->Dspf->findByFoyerId( $id, null, null, 0 );
// 
//             // Ajout des DSP demandeur et conjoint
//             $personnesFoyer = $this->Personne->find(
//                 'all',
//                 array(
//                     'conditions' => array(
//                         'Personne.foyer_id' => $id,
//                         'Prestation.rolepers' => array( 'DEM', 'CJT' )
//                     ),
//                     'recursive' => 2
//                 )
//             );
// 
//             foreach( $personnesFoyer as $personneFoyer ) {
//                 $dsp[$personneFoyer['Prestation']['rolepers']] = $personneFoyer;
//                 $dsp_personne = $this->Dspp->findByPersonneId( $personneFoyer['Personne']['id'], null, null, 1 );
//                 if( !empty( $dsp_personne ) ) {
//                     $dsp[$personneFoyer['Prestation']['rolepers']] = array_merge( $dsp[$personneFoyer['Prestation']['rolepers']], $dsp_personne );
//                 }
//                 else {
//                     unset( $dsp[$personneFoyer['Prestation']['rolepers']]['Dspp'] );
//                 }
// 
//                 //-----------------------------------------------------------------
// 
//                 $contratinsertion = $this->Contratinsertion->find(
//                     'first',
//                     array(
//                         'conditions' => array( 'Contratinsertion.personne_id' => $personneFoyer['Personne']['id'] ),
//                         'recursive' => -1,
//                         'order' => array( 'Contratinsertion.rg_ci DESC' )
//                     )
//                 );
//                 //$this->assert( !empty( $contratinsertion ), 'invalidParameter' );
//                 $dsp[$personneFoyer['Prestation']['rolepers']]['Contratinsertion'] = $contratinsertion['Contratinsertion'];
//             }
// 
//             $this->set( 'dsp', $dsp );
// 
//             /*-*************************************************************************/
// 
//             $tc = $this->Typocontrat->find(
//                 'list',
//                 array(
//                     'fields' => array(
//                         'Typocontrat.lib_typo'
//                     ),
//                 )
//             );
//             $this->set( 'tc', $tc );
// 
// 
//             $dossier = $this->Dossier->find(
//                 'first',
//                 array(
//                     'conditions' => array( 'Dossier.id' => $id ),
//                     'recursive' => 0
//                 )
//             );
//             $this->assert( !empty( $dossier ), 'invalidParameter' );
// 
//             //*****************************************************************
// 
//             $personne = $this->Personne->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Personne.foyer_id' => $dossier['Foyer']['id'],
//                         'Prestation.rolepers' => array( 'DEM' )
//                     ),
//                     'recursive' => 0
//                 )
//             );
//             $this->assert( !empty( $personne ), 'invalidParameter' );
// 
//             //-----------------------------------------------------------------
// 
//             $orientStruct = $this->Orientstruct->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Orientstruct.personne_id' => $personne['Personne']['id']
//                     ),
//                     'order' => 'Orientstruct.date_valid ASC',
//                     'recursive' => -1
//                 )
//             );
//             $personne['Personne']['Orientstruct']['premiere'] = $orientStruct['Orientstruct'];
// 
//             $orientStruct = $this->Orientstruct->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Orientstruct.personne_id' => $personne['Personne']['id']
//                     ),
//                     'order' => 'Orientstruct.date_valid DESC',
//                     'recursive' => -1
//                 )
//             );
//             $personne['Personne']['Orientstruct']['derniere'] = $orientStruct['Orientstruct'];
// 
//             //-----------------------------------------------------------------
// 
//             //$personne['Personne']['Typeorient'] = $orientStruct['Typeorient'];
//             //$personne['Personne']['Structurereferente'] = $orientStruct['Structurereferente'];
//             $dossier = Set::merge( $dossier, $personne );
// // debug( $personne['Personne']['Contratinsertion'] );
//             //*****************************************************************
// 
//             $adresseFoyer = $this->Adressefoyer->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Adressefoyer.foyer_id' => $dossier['Foyer']['id'],
//                         'Adressefoyer.rgadr'    => '01'
//                     ),
//                     'recursive' => 1
//                 )
//             );
//             //$this->assert( !empty( $adresseFoyer ), 'invalidParameter' );
//             $dossier = Set::merge( $dossier, array( 'Adresse' => $adresseFoyer['Adresse'] ) );
// 
//             //-----------------------------------------------------------------
// 
//             $creance = $this->Creance->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Creance.id' => $dossier['Foyer']['id'], // WTF ??
//                     ),
//                     'recursive' => 2
//                 )
//             );
//             $dossier = Set::merge( $dossier, array( 'Creance' => $creance['Creance'] ) );
// 
// 
//             $modes = $this->Modecontact->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Modecontact.foyer_id' => $dossier['Foyer']['id'],
//                     ),
//                     'recursive' => 2
//                 )
//             );
//             $dossier = Set::merge( $dossier, array( 'Modecontact' => $modes['Modecontact'] ) );
// 
//             //-----------------------------------------------------------------
// /*
//             $struct = $this->Structurereferente->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Structurereferente.id' => $orientStruct['Structurereferente']['id'], // WTF ??
//                     ),
//                     'recursive' => -1
//                 )
//             );
//             $dossier = Set::merge( $dossier, array( 'Structurereferente' => $struct['Structurereferente'] ) );*/
// 
//             //-----------------------------------------------------------------
// 
//             $typeorient = $this->Typeorient->findById( $dossier['Foyer']['id'], null, null, -1 );
//             $dossier = Set::merge( $dossier, array( 'Typeorient' => $typeorient['Typeorient'] ) );
// 
//             //-----------------------------------------------------------------
// 
//             $detail = $this->Detailcalculdroitrsa->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'Detailcalculdroitrsa.detaildroitrsa_id' => $dossier['Detaildroitrsa']['id'],
//                     ),
//                     'order' => 'dtderrsavers DESC',
//                     'recursive' => -1
//                 )
//             );
// 
//             $dossier['Detaildroitrsa']['Detailcalculdroitrsa'] = $detail['Detailcalculdroitrsa']; // FIXME: vérifier avec plusieurs
// 
//             //-----------------------------------------------------------------
// 
//             $dsp = $this->Dspp->findByPersonneId( $personne['Personne']['id'], null, null, 0 );
//             $dossier = Set::merge( $dossier, array( 'Dspp' => $dsp['Dspp'] ) );
// 
//             //-----------------------------------------------------------------
// 
//             $caf = $this->Dossiercaf->findByPersonneId( $personne['Personne']['id'], null, null, 0 );
//             $dossier = Set::merge( $dossier, array( 'Dossiercaf' => $caf['Dossiercaf'] ) );
// // debug( $dossier );
//             //-----------------------------------------------------------------
// 
//             $this->assert( !empty( $dossier ), 'error404' );
// 
// // debug( Set::extract( 'Personne.Contratinsertion', $dossier ) );
// 
// 
//             $this->set( 'dossier', $dossier );
        }
    }
?>
