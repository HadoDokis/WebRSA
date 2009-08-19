<?php
    @set_time_limit( 0 );
    @ini_set( 'memory_limit', '128M' );

    class CohortesController extends AppController
    {
        var $name = 'Cohortes';
        var $uses = array( 'Cohorte', 'Dossier', 'Structurereferente', 'Option', 'Ressource', 'Adresse', 'Typeorient', 'Structurereferente', 'Contratinsertion', 'Detaildroitrsa', 'Zonegeographique', 'Adressefoyer', 'Dspf', 'Accoemploi', 'Personne', 'Orientstruct' );

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
        * INFO: Préprofessionnelle => Socioprofessionnelle --> mettre un type dans la table ?
        *
        */

        function _preOrientation( $element ) {
            $propo_algo = null;

            if( isset( $element['Dspp'] ) ) {
                $accoemploiCodes = Set::extract( 'Dspp.Accoemploi.{n}.code', $element );

                // Socioprofessionnelle, Social
                // 1°) Passé professionnel ? -> Emploi
                //     1901 : Vous avez toujours travaillé
                //     1902 : Vous travaillez par intermittence
                if( !empty( $element['Dspp']['hispro'] ) && ( $element['Dspp']['hispro'] == '1901' || $element['Dspp']['hispro'] == '1902' ) ) {
                    $propo_algo = 'Emploi'; // Emploi (Pôle emploi)
                }
                // 2°) Etes-vous accompagné dans votre recherche d'emploi ?
                //     1802 : Pôle Emploi
                else if( empty( $propo_algo ) && !empty( $accoemploiCodes ) && in_array( '1802', $accoemploiCodes ) ) {
                    $propo_algo = 'Emploi';
                }
                // 3°) Êtes-vous sans activité depuis moins de 24 mois ?
                //     Date éventuelle de cessation d’activité ?
                else if( empty( $propo_algo ) ) {
                    $dfderact = null;
                    if( !empty( $element['Dspp']['dfderact'] ) ) {
                        list( $year, $month, $day ) = explode( '-', $element['Dspp']['dfderact'] );
                        $dfderact = mktime( 0, 0, 0, $month, $day, $year );
                    }
                    if( !empty( $dfderact ) && ( $dfderact > strtotime( '-24 months' ) ) ) {
                        $propo_algo = 'Emploi';
                    }
                }

                if( empty( $propo_algo ) && isset( $element['Foyer']['Dspf'] ) && !empty( $element['Foyer']['Dspf'] ) ) {
                    $dspf = $this->Dossier->Foyer->Dspf->find(
                        'first',
                        array(
                            'conditions' => array( 'Dspf.id' => $element['Foyer']['Dspf']['id'] )
                        )
                    );
                    if( !empty( $dspf ) ) {
                        // FIXME: grosse requête pour pas grand-chose
                        if( $element['Foyer']['Dspf']['accosocfam'] == 'O' ) {
                            $propo_algo = 'Social'; // SSD (Service Social Départemental)
                        }
                        else {
                            $propo_algo = 'Socioprofessionnelle'; // PDV (Projet De Ville)
                        }
                    }
                }
            }

            if( empty( $propo_algo ) ) {
                $propo_algo = 'Emploi';
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
            $this->set( 'printed', $this->Option->printed() );

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

                //-------------------------------------------------------------

                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

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
                            if( $saved ) {
                                // FIXME ?
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

                    foreach( $cohorte as $personne_id ) {
                        $this->Jetons->get( array( 'Dossier.id' => $this->Dossier->Foyer->Personne->dossierId( $personne_id ) ) );
                    }

                    $this->Dossier->Foyer->Personne->bindModel( array( 'hasOne' => array( 'Dspp', 'Orientstruct' ), 'belongsTo' => array( 'Foyer' ) ) ); // FIXME
                    $cohorte = $this->Dossier->Foyer->Personne->find(
                        'all',
                        array(
                            'conditions' => array(
                                'Personne.id' => ( !empty( $cohorte ) ? $cohorte : null )
                            ),
                            'recursive' => 2,
                            'limit'     => $_limit
                        )
                    );

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
                        else {
                            $this->set( 'structuresReferentes', $this->Structurereferente->list1Options() );

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
//                     debug( $cohorte );
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

/************************************* Export des données en Xls *******************************/
        var $helpers = array( 'Csv'/*, 'Xls' */);

        function exportcsv() {
            $headers = array( 'Commune', 'Qual', 'Nom', 'Prénom', 'Date demande', 'Date ouverture', 'Service instructeur', 'Préorientation', 'Orientation', 'Structure', 'Décision', 'Date proposition', 'Date dernier CI' );

            $dataPers = $this->Personne->find( 'all', array( 'fields' => array( 'qual', 'nom', 'prenom' ), 'limit' => 20, 'recursive' => -1 ) );
            $dataPers = Set::extract( $dataPers, '{n}.Personne' );

            $dataDos = $this->Dossier->find( 'all', array( 'fields' => array( 'dtdemrsa', 'numdemrsa' ), 'limit' => 20, 'recursive' => -1 ) );
            $dataDos = Set::extract( $dataDos, '{n}.Dossier' );

            $dataAdr = $this->Adresse->find( 'all', array( 'fields' => array( 'locaadr' ), 'limit' => 20, 'recursive' => -1 ) );
            $dataAdr = Set::extract( $dataAdr, '{n}.Adresse' );

            $dataOri = $this->Orientstruct->find( 'all', array( 'fields' => array( 'structurereferente_id', 'propo_algo', 'date_propo', 'statut_orient' ), 'limit' => 20, 'recursive' => -1 ) );
            $dataOri = Set::extract( $dataOri, '{n}.Orientstruct' );

//             $dataStr = $this->Structurereferente->find( 'all', array( 'fields' => array( 'structure', 'date_propo', 'statut_orient' ), 'limit' => 20, 'recursive' => -1 ) );
//             $dataStr = Set::extract( $dataStr, '{n}.Structurereferente' );

            $this->layout = '';
            $data = $this->set( compact( 'dataPers', 'dataDos', 'dataAdr', 'dataOri' ) );

            $this->set( 'dataToExport', $data );
        }


        // Exports CSV
//         function exportCSV( $where = NULL, $delimeter = ',' )
//         {
//             $csv = '';
//             $this->recursive = -1;
//             $rows = $this->find($where);
//             foreach($rows as $row)
//             {
//                 $row[$this->name];
//                 $row[$this->name];
//                 $csv .= implode( $delimeter, $row[$this->name] ) . chr(13);
//             }
//
//             $csv = trim($csv);
//             return $csv;
//         }

        // Imports CSV
        function importCSV($csv, $delimeter = ',')
        {
            $keys = $this->getFieldNames();
            $rows = array();
            $csv = trim($csv);
            $csv_rows = explode(chr(13), $csv);

            foreach($csv_rows as $csv_row)
            {
                $row = explode($delimeter, $csv_row);
                $row = str_replace(chr(26), $delimeter, $row);
                $row = array_combine($keys, $row);
                $rows = array_merge_recursive($rows, array(array($this->name => $row)));
            }
            return $rows;
        }

        // Returns model fieldnames
        function getFieldNames()
        {

            $names = array();
            $fields = $this->_schema;

            foreach($fields as $key => $value)
                array_push($names, $key);

            return $names;
        }




        function export()
        {
//             $cohorte = $this->Cohorte->search( 'Orienté', array(), false, $this->data, array(), 10 );
//             if( !empty( $cohorte ) ) {
//                 $data = $this->Personne->find( 'all', array( 'fields' => array( 'qual', 'nom', 'prenom' ), 'limit' => 20, 'recursive' => -1, 'conditions' => array( 'Personne.id' => array_values( $cohorte ) ) ) );
//             }
//             else {
//                 $data = array();
//             }
// debug( $data );
// die();

            $headers = array( 'Commune', 'Qual', 'Nom', 'Prénom', 'Date demande', 'Date ouverture', 'Service instructeur', 'Préorientation', 'Orientation', 'Structure', 'Décision', 'Date proposition', 'Date dernier CI' );

            $data = $this->Personne->find( 'all', array( 'fields' => array( 'qual', 'nom', 'prenom' ), 'limit' => 20, 'recursive' => -1 ) );
            $data = Set::extract( $data, '{n}.Personne' );
            $this->set( compact( 'data', 'headers' ) );

            $filename  = 'export_' . strftime('%Y-%m-%d-%Hh%M') . '.xls';

            $this->autoLayout = false;

            App::import('Core', 'File');

            $file = new File( $filename, true );
            $file->write( $this->render() );

            $file->close();

            $this->Session->setFlash( "Nouveau fichier disponible.", 'flash/success' );
            $this->redirect($this->referer());

        }


        function export_download( $filename )
        {
            $this->view = 'Media';

            $params = array(
                'path'      => WWW_ROOT.'files/exports' . DS,
                'id'        => $filename,
                'name'      => substr($filename, 0, strpos($filename, '.xls')),
                'extension' => 'xls',
                'download'  => true
            );

            $this->set($params);
        }

       /* function exports_index()
        {
            App::import( 'Core', 'Folder' );

            $dir = new Folder(WWW_ROOT.'files/exports');

            $data = $dir->find('.+\.xls');

            rsort($data);

            $this->set(compact('data'));
        }

        function export_download( $filename )
        {
            $this->view = 'Media';
// debug( $filename );
            $params = array(
                'path'      => WWW_ROOT.'files/exports' . DS,
                'id'        => $filename,
                'name'      => substr($filename, 0, strpos($filename, '.xls')),
                'extension' => 'xls',
                'download'  => true
            );

            $this->set($params);
        }

        function export_delete( $filename )
        {
            App::import('Core', 'File');

            $file = new File( WWW_ROOT.'files/exports' . DS . $filename);

            if(!$file->delete())
            {
                $this->Session->setFlash("Impossible de supprimer le fichier '{$filename}'.", 'flash/error');
            }
            else
            {
                $this->Session->setFlash("Fichier '{$filename}' supprimé.", 'flash/success');
            }

            $this->redirect($this->referer());
        } */
    }
?>