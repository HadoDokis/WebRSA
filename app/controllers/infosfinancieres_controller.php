<?php
	class InfosfinancieresController  extends AppController
	{
		public $name = 'Infosfinancieres';
		public $uses = array( 'Infofinanciere', 'Option', 'Dossier', 'Personne', 'Foyer', 'Cohorteindu' );
		public $helpers = array( 'Paginator', 'Locale', 'Csv' );

		public $commeDroit = array( 'view' => 'Infosfinancieres:index' );

		public $components = array( 'Prg' => array( 'actions' => array( 'indexdossier' ) ) );

		/**
		*
		*/

//		function __construct() {
//			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'indexdossier' ) ) ) );
//			parent::__construct();
//		}

		/**
		*
		*/

		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			parent::beforeFilter();
			$this->set( 'type_allocation', $this->Option->type_allocation() );
			$this->set( 'natpfcre', $this->Option->natpfcre() );
			$this->set( 'typeopecompta', $this->Option->typeopecompta() );
			$this->set( 'sensopecompta', $this->Option->sensopecompta() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
		}

		/**
		*
		*/

		public function indexdossier() {
			$this->set( 'annees', $this->Infofinanciere->range() );
			if( !empty( $this->data ) ) {
				$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
				$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

				$this->Dossier->begin(); // Pour les jetons

				$this->paginate = $this->Infofinanciere->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
				$this->paginate['limit'] = 15;
				$infosfinancieres = $this->paginate( 'Infofinanciere' );

				$this->set( 'infosfinancieres', $infosfinancieres );

				$this->Dossier->commit();
			}
		}

		/**
		*
		*/

		public function index( $dossier_id = null ) {
			//Vérification du format de la variable
			$this->assert( valid_int( $dossier_id ), 'invalidParameter' );

			//Recherche des adresses du foyer
			$infosfinancieres = $this->Infofinanciere->find(
				'all',
				array(
					'conditions' => array( 'Infofinanciere.dossier_id' => $dossier_id ),
					'recursive' => 0
				)
			);

			$foyer = $this->Dossier->Foyer->findByDossierId( $dossier_id, null, null, -1 );

			$personne = $this->Dossier->Foyer->Personne->find(
				'first',
				array(
					'conditions' => array(
						'Personne.foyer_id' => $foyer['Foyer']['id'],
						'( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
						'( Prestation.rolepers = \'DEM\' )',
					)
				)
			);

			$this->assert( !empty( $personne ), 'invalidParameter' );
			$this->set( 'personne', $personne );

			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'infosfinancieres', $infosfinancieres );
		}

		/**
		*
		*/

		public function view( $infofinanciere_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $infofinanciere_id ), 'error404' );

			$infofinanciere = $this->Infofinanciere->find(
				'first',
				array(
					'conditions' => array(
						'Infofinanciere.id' => $infofinanciere_id
					),
				'recursive' => -1
				)
			);

			$this->assert( !empty( $infofinanciere ), 'error404' );
			$dossier_id = Set::classicExtract( $infofinanciere, 'Infofinanciere.dossier_id' );

			$foyer = $this->Dossier->Foyer->findByDossierId( $dossier_id, null, null, -1 );

			$personne = $this->Dossier->Foyer->Personne->find(
				'first',
				array(
					'conditions' => array(
						'Personne.foyer_id' => $foyer['Foyer']['id'],
						'( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
						'( Prestation.rolepers = \'DEM\' )',
					),
					'recursive' => 0
				)
			);

			$this->assert( !empty( $personne ), 'invalidParameter' );
			$this->set( 'personne', $personne );

			// Assignations à la vue
			$this->set( 'dossier_id', $infofinanciere['Infofinanciere']['dossier_id'] );
			$this->set( 'infofinanciere', $infofinanciere );
			$this->set( 'urlmenu', '/infosfinancieres/index/'.$foyer['Foyer']['id'] );
		}

		/**
		*
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$options = $this->Infofinanciere->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ) );

			unset( $options['limit'] );
			$infos = $this->Infofinanciere->find( 'all', $options );

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'headers', 'infos' ) );
		}
	}
?>