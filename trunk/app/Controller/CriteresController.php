<?php
	App::import('Sanitize');

	class CriteresController extends AppController
	{
		public $name = 'Criteres';
		public $uses = array( 'Critere', 'Typeorient', 'Option', 'Orientstruct', );

		public $aucunDroit = array( 'constReq', 'ajaxstruc' );

		public $helpers = array( 'Csv', 'Ajax', 'Search' );

		public $components = array( 'Gestionzonesgeos', 'RequestHandler',  'Search.Prg' => array( 'actions' => array( 'index' ) )  );

		/**
		*
		*/

		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '1024M');
			return parent::beforeFilter();
		}


		/**
		 *
		 */
		protected function _setOptions() {
			$typeservice = ClassRegistry::init( 'Serviceinstructeur' )->find( 'list', array( 'fields' => array( 'lib_service' ) ) );
			$this->set( 'typeservice', $typeservice );
			$this->set( 'rolepers', $this->Option->rolepers() );
			// Structures référentes
			$datas = Set::merge( $this->request->data, Xset::bump( $this->request->params['named'], '__' ) );
			$typeorient_id = Set::classicExtract( $datas, 'Critere.typeorient_id' );
			$conditions = array();
			$conditions = array( 'Structurereferente.actif' => 'O' );
			if( !empty( $typeorient_id ) ) {
				$conditions = array(
					'Structurereferente.typeorient_id' => $typeorient_id
				);
			}
			$sr = $this->Typeorient->Structurereferente->find( 'list', array( 'fields' => array( 'lib_struc' ), 'conditions' => $conditions ) );
			$this->set( 'sr', $sr );

			$this->set( 'typeorient', $this->Typeorient->find( 'list' ) );

			$this->set( 'statuts', $this->Option->statut_orient() );
			$this->set( 'statuts_contrat', $this->Option->statut_contrat_insertion() );
			$this->set( 'natpf', $this->Option->natpf() );

			$this->set( 'referents', ClassRegistry::init( 'Referent' )->find( 'list', array( 'conditions' => array( 'Referent.actif' => 'O' ) ) ) );
			$this->set( 'options', $this->Orientstruct->enums() );


			//Ajout des structures et référents orientants
			$this->set( 'refsorientants', ClassRegistry::init( 'Referent' )->listOptions() );
			$this->set( 'structsorientantes', ClassRegistry::init( 'Structurereferente' )->listOptions( array( 'orientation' => 'O' ) ) );
		}

		/**
		* Ajax pour la structure référente liée au type d'orientation
		*/

		protected function _selectStructs( $typeorientid = null ) {
			$conditions = array();

			if( !empty( $typeorientid ) ) {
				$conditions = array(
					'Structurereferente.typeorient_id' => $typeorientid
				);
			}

			$structs = $this->Orientstruct->Structurereferente->find(
				'all',
				array(
					'fields' => array( 'Structurereferente.id', 'Structurereferente.lib_struc' ),
					'conditions' => $conditions,
					'recursive' => -1
				)
			);

			return $structs;
		}

		/**
		*
		*/

		public function ajaxstruc() { // FIXME
			Configure::write( 'debug', 0 );
			$structs = $this->_selectStructs( Set::classicExtract( $this->request->data, 'Critere.typeorient_id' ) );

			$options = array( '<option value=""></option>' );
			foreach( $structs as $struct ) {
				$options[] = '<option value="'.$struct['Structurereferente']['id'].'">'.$struct['Structurereferente']['lib_struc'].'</option>';
			}
			echo implode( '', $options );
			$this->render( null, 'ajax' );
		}

		/**
		*
		*/

		public function index() {
			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->request->data ) ) {
				$paginate = $this->Critere->search(
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data,
					false
				);

				$paginate = $this->_qdAddFilters( $paginate );
				$paginate['limit'] = 10;

				$this->paginate = $paginate;
				$orients = $this->paginate( 'Orientstruct' );

				$this->set( 'orients', $orients );
			}

			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			$this->_setOptions();
		}

		/**
		 * Export du tableau en CSV
		 */
		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$this->set( 'typevoie', $this->Option->typevoie() );

			$querydata = $this->Critere->search(
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->request->params['named'], '__' ),
				false
			);

			unset( $querydata['limit'] );

			$querydata = $this->_qdAddFilters( $querydata );

			$orients = $this->Orientstruct->find( 'all', $querydata );

			$this->layout = '';
			$this->_setOptions();
			$this->set( compact( 'orients' ) );
		}
	}
?>