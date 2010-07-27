<?php
    App::import('Sanitize');
    class CriteresController extends AppController
    {
        var $name = 'Criteres';
        var $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Typeorient', 'Structurereferente', 'Contratinsertion', 'Option', 'Serviceinstructeur', 'Orientstruct', 'Critere', 'Zonegeographique', 'Referent' );
        //var $aucunDroit = array('index', 'menu', 'constReq');
        var $aucunDroit = array( 'constReq', 'ajaxstruc' );
        var $helpers = array( 'Csv', 'Ajax' );

		function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '1024M');
			parent::beforeFilter();
		}


        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }

        /**

        */

        protected function _setOptions() {
            $typeservice = $this->Serviceinstructeur->find( 'list', array( 'fields' => array( 'lib_service' ) ) );
            $this->set( 'typeservice', $typeservice );

            // Structures référentes
            $datas = Set::merge( $this->data, array_multisize( $this->params['named'] ) );
            $typeorient_id = Set::classicExtract( $datas, 'Critere.typeorient_id' );
            $conditions = array();
            if( !empty( $typeorient_id ) ) {
                $conditions = array(
                    'Structurereferente.typeorient_id' => $typeorient_id
                );
            }
            $sr = $this->Structurereferente->find( 'list', array( 'fields' => array( 'lib_struc' ), 'conditions' => $conditions ) );
            $this->set( 'sr', $sr );



            $this->set( 'typeorient', $this->Typeorient->listOptions() );
            $this->set( 'statuts', $this->Option->statut_orient() );
            $this->set( 'statuts_contrat', $this->Option->statut_contrat_insertion() );
            $this->set( 'natpf', $this->Option->natpf() );

            $this->set( 'referents', $this->Referent->find( 'list' ) );
        }

//         function beforeFilter() {
//             $return = parent::beforeFilter();
// 
//             $typeservice = $this->Serviceinstructeur->find( 'list', array( 'fields' => array( 'lib_service' ) ) );
//             $this->set( 'typeservice', $typeservice );
// 
// 			// Structures référentes
// 			$datas = Set::merge( $this->data, array_multisize( $this->params['named'] ) );
// 			$typeorient_id = Set::classicExtract( $datas, 'Critere.typeorient_id' );
// 			$conditions = array();
// 			if( !empty( $typeorient_id ) ) {
// 				$conditions = array(
// 					'Structurereferente.typeorient_id' => $typeorient_id
// 				);
// 			}
// 			$sr = $this->Structurereferente->find( 'list', array( 'fields' => array( 'lib_struc' ), 'conditions' => $conditions ) );
// 			$this->set( 'sr', $sr );
// 
// 
// 
//             $this->set( 'typeorient', $this->Typeorient->listOptions() );
//             $this->set( 'statuts', $this->Option->statut_orient() );
//             $this->set( 'statuts_contrat', $this->Option->statut_contrat_insertion() );
//             $this->set( 'natpf', $this->Option->natpf() );
// 
//             $this->set( 'referents', $this->Referent->find( 'list' ) );
// 
//             return $return;
//         }

        /** ********************************************************************
        *   Ajax pour la structure référente liée au type d'orientation
        *** *******************************************************************/
        function _selectStructs( $typeorientid = null ) {
			$conditions = array();

			if( !empty( $typeorientid ) ) {
				$conditions = array(
					'Structurereferente.typeorient_id' => $typeorientid
				);
			}

            $structs = $this->Orientstruct->Structurereferente->find(
                'all',
                array(
                    'conditions' => $conditions,
                    'recursive' => -1
                )
            );

            return $structs;

        }

        function ajaxstruc() { // FIXME
            Configure::write( 'debug', 0 );
            $structs = $this->_selectStructs( Set::classicExtract( $this->data, 'Critere.typeorient_id' ) );

            $options = array( '<option value=""></option>' );
            foreach( $structs as $struct ) {
                $options[] = '<option value="'.$struct['Structurereferente']['id'].'">'.$struct['Structurereferente']['lib_struc'].'</option>';
            }
            echo implode( '', $options );
            $this->render( null, 'ajax' );
        }


        function index() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );
            if( !empty( $this->data ) ) {
                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Critere->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );

                $this->paginate['limit'] = 10;
                $orients = $this->paginate( 'Orientstruct' );

                $this->Dossier->commit();

                $this->set( 'orients', $orients );
            }

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}
            $this->_setOptions();
        }

        /// Export du tableau en CSV
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );
            $this->set( 'typevoie', $this->Option->typevoie() );

            $querydata = $this->Critere->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );

            unset( $querydata['limit'] );
            $orients = $this->Orientstruct->find( 'all', $querydata );

            $this->layout = ''; // FIXME ?
            $this->_setOptions();
            $this->set( compact( 'orients' ) );
        }
    }
?>
