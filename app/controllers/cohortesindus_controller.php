<?php
	App::import('Sanitize');

	class CohortesindusController extends AppController
	{
		public $name = 'Cohortesindus';
		public $uses = array( 'Canton', 'Cohorteindu', 'Option',  'Structurereferente', 'Infofinanciere', 'Dossier', 'Zonegeographique', 'Situationdossierrsa' );
		public $helpers = array( 'Csv', 'Paginator', 'Locale', 'Search' );

		public $paginate = array(
			// FIXME
			'limit' => 20,
		);

		public $components = array( 'Jetons', 'Prg' => array( 'actions' => array( 'index' ) ) );

		/**
		*
		*/

//		public function __construct() {
//			$this->components = Set::merge( $this->components, array( 'Jetons', 'Prg' => array( 'actions' => array( 'index' ) ) ) );
//			parent::__construct();
//		}

		public function beforeFilter() {
			$sr = $this->Structurereferente->find(
				'list',
				array(
					'fields' => array(
						'Structurereferente.lib_struc'
					),
				)
			);
			$this->set( 'sr', $sr );

			$return = parent::beforeFilter();
				$this->set( 'natpfcre', $this->Option->natpfcre( 'autreannulation' ) );
				$this->set( 'typeparte', $this->Option->typeparte() );
				//$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
				$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $this->Situationdossierrsa->etatOuvert()) );
				$this->set( 'natpf', $this->Option->natpf() );
				$this->set( 'type_allocation', $this->Option->type_allocation() );
				$this->set( 'dif', $this->Option->dif() );
				$this->set( 'rolepers', $this->Option->rolepers() );
			return $return;
		}

		/**
		*
		*/

		public function index() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}
			$comparators = array( '<' => '<' ,'>' => '>','<=' => '<=', '>=' => '>=' );

			$cmp = Set::extract( $this->data, 'Cohorteindu.compare' );
			$this->assert( empty( $cmp ) || in_array( $cmp, array_keys( $comparators ) ), 'invalidParameter' );
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->data ) ) {
				$this->Cohorteindu->create( $this->data );
				if( $this->Cohorteindu->validates() ) {
					$this->Dossier->begin(); // Pour les jetons

					$paginate = $this->Cohorteindu->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
					$paginate['limit'] = 10;
					$paginate = $this->_qdAddFilters( $paginate );

					$this->paginate = $paginate;
					$cohorteindu = $this->paginate( 'Dossier' );

					$this->Dossier->commit();

					$this->set( 'cohorteindu', $cohorteindu );
				}
			}

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}

			$this->set( 'comparators', $comparators );
		}

		/**
		*
		*/

		public function exportcsv(){
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$_limit = 10;
			$querydata = $this->Cohorteindu->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->params['named'], '__' ), $this->Jetons->ids() );
			$querydata = $this->_qdAddFilters( $querydata );

			unset( $querydata['limit'] );
			$indus = $this->Dossier->find( 'all', $querydata );


			$this->layout = ''; // FIXME ?
			$this->set( compact( 'headers', 'indus' ) );
		}
	}
?>