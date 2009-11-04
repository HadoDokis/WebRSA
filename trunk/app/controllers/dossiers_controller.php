<?php
    @ini_set( 'max_execution_time', 0 );
    @ini_set( 'memory_limit', '512M' );
    App::import( 'Sanitize' );

    class DossiersController extends AppController
    {
        var $name = 'Dossiers';
        var $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Structurereferente', 'Orientstruct', 'Typeorient', 'Contratinsertion', 'Detaildroitrsa', 'Detailcalculdroitrsa', 'Option', 'Dspp', 'Dspf', 'Infofinanciere', 'Modecontact','Typocontrat', 'Creance', 'Adressefoyer', 'Dossiercaf', 'Serviceinstructeur', 'Jeton' , 'Indu', 'Referent', 'Zonegeographique' );
        var $aucunDroit = array( 'menu' );
        var $helpers = array( 'Csv' );

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
            $this->set( 'categorie', $this->Option->categorie() );
            return $return;
        }

        /**
        */
        function index() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->data;
            if( !empty( $params ) ) {
                $this->paginate = $this->Dossier->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
                $dossiers = $this->paginate( 'Dossier' );

                foreach( $dossiers as $key => $dossier ) {
                    $dossiers[$key]['Dossier']['locked'] = $this->Jetons->locked( $dossier['Dossier']['id'] );
                }

                $this->set( 'dossiers', $dossiers );

            }
            $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
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

            $this->Dossier->Foyer->bindModel(
                array(
                    'hasMany' => array(
                        'Adressefoyer' => array(
                            'classname'     => 'Adressefoyer',
                            'foreignKey'    => 'foyer_id'
                        ),
                        'Personne' => array(
                            'classname'     => 'Personne',
                            'foreignKey'    => 'foyer_id'
                        )
                    )
                )
            );
            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => $conditions,
                    'recursive'  => 2
                )
            );
            $this->assert( !empty( $dossier ), 'invalidParameter' );

            usort( $dossier['Foyer']['Adressefoyer'], create_function( '$a,$b', 'return strcmp( $a["rgadr"], $b["rgadr"] );' ) );

            foreach( $dossier['Foyer']['Adressefoyer'] as $key => $Adressefoyer ) {
                $adresses = $this->Adresse->find(
                    'all',
                    array(
                        'conditions' => array(
                            'Adresse.id' => $Adressefoyer['adresse_id'] )
                    )
                );
                $dossier['Foyer']['Adressefoyer'][$key] = array_merge( $dossier['Foyer']['Adressefoyer'][$key], $adresses[0] );
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
            /** Tables necessaire à l'ecran de synthèse

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
                    'conditions' => array(
                        'Infofinanciere.dossier_rsa_id' => $id,
                        'Infofinanciere.type_allocation' => 'IndusConstates'
                    ),
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
            $this->Personne->bindModel( array( 'hasOne' => array( 'Dossiercaf', 'Dspp', 'Infopoleemploi', 'Prestation' => $bindPrestation ) ) );
            $personnesFoyer = $this->Personne->find(
                'all',
                array(
                    'conditions' => array(
                        'Personne.foyer_id' => $tFoyer['Foyer']['id'],
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



            $structuresreferentes = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $typesorient = $this->Typeorient->find( 'list', array( 'fields' => array( 'id', 'lib_type_orient' ) ) );
            $typoscontrat = $this->Typocontrat->find( 'list', array( 'fields' => array( 'id', 'lib_typo' ) ) );

            $this->set( 'structuresreferentes', $structuresreferentes );
            $this->set( 'typesorient', $typesorient );
            $this->set( 'typoscontrat', $typoscontrat );


            $this->set( 'details', $details );

// debug( $details );
        }

        /// Export du tableau en CSV

        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );
            $querydata = $this->Dossier->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ) );
            unset( $querydata['limit'] );
            $dossiers = $this->Dossier->find( 'all', $querydata );
            $this->layout = ''; // FIXME ?
            $this->set( compact( 'headers', 'dossiers' ) );
        }
    }
?>
